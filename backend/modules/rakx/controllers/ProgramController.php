<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\Program;
use backend\modules\rakx\models\ProgramHasWaktu;
use backend\modules\rakx\models\search\ProgramSearch;
use backend\modules\rakx\models\DetilProgram;
use backend\modules\rakx\models\search\DetilProgramSearch;
use backend\modules\rakx\models\ProgramHasSumberDana;
use backend\modules\rakx\models\search\ProgramHasSumberDanaSearch;
use backend\modules\rakx\models\ReviewProgram;
use backend\modules\rakx\models\search\ReviewProgramSearch;
use backend\modules\rakx\models\StatusProgram;
use backend\modules\rakx\models\TahunAnggaran;
use backend\modules\rakx\models\MataAnggaran;
use backend\modules\rakx\models\RencanaStrategis;
use backend\modules\rakx\models\Satuan;
use backend\modules\rakx\models\SumberDana;
use backend\modules\inst\models\InstApiModel;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;
use backend\modules\rakx\models\Bulan;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\inst\models\Pejabat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use PHPExcel;
use PHPExcel_Writer_Excel2007;
use PHPExcel_IOFactory;

/**
 * ProgramController implements the CRUD actions for Program model.
 */
class ProgramController extends Controller
{
    public $menuGroup = 'rakx-program';
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => [],
                ],
                
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
        if(empty($pegawai)){
            \Yii::$app->messenger->addErrorFlash('Data Pegawai anda belum tercatat di CIS, harap menghubungi HRD !');
            $this->redirect(\Yii::$app->request->referrer);
        }
        if($action->id == "index" || $action->id == "export-program" || $action->id == "program-legitimate" || $action->id == "program-kompilasi-view")
        {
            return true;
        }
        $inst_api = new InstApiModel();
        $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
        foreach($temp as $s){
            if($s->mata_anggaran==1){
                return true;
            }
        }
        \Yii::$app->messenger->addErrorFlash('You have no rights accessing the feature !');
        $this->redirect(\Yii::$app->request->referrer);
        return false;
    }

    /**
     * Lists all Program models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->menuGroup = 'rakx-kompilasi-program';
        $searchModel = new ProgramSearch();

        $status_program = StatusProgram::find()->where('deleted != 1')->andWhere(['in', 'status_program_id', [3,5,8]])->All();
        $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->all();
        $struktur_jabatan = StrukturJabatan::find()->where('deleted!=1')->andWhere(['mata_anggaran' => 1])->orderBy(['jabatan' => SORT_ASC])->all();

        if (!isset(Yii::$app->request->queryParams['ProgramSearch']['tahun_anggaran'])){
            $searchModel->tahun_anggaran = $tahun_anggaran[0]->tahun_anggaran_id;
        }
        if (!isset(Yii::$app->request->post()['ProgramSearch']['status_program_id'])){
            $searchModel->status_program_id = $status_program[0]->status_program_id;
        }
        
        // mendapatkan list bawahan jabatan yg dipilih user dr dropdown jabatan lalu di assign ke properti objek search model
        $strukturJabatanIdList = array();
        if (isset(Yii::$app->request->post()['ProgramSearch']['struktur_jabatan']) && Yii::$app->request->post()['ProgramSearch']['struktur_jabatan']!=''){
            $strukturJabatanIdList[] = Yii::$app->request->post()['ProgramSearch']['struktur_jabatan'];
        }else{
            $temp = StrukturJabatan::find()->where('deleted!=1')->andWhere(['mata_anggaran' => 1])->andWhere(['parent' => null])->orWhere(['parent' => ''])->orWhere(['parent' => 0])->all();
            foreach($temp as $t){
                $strukturJabatanIdList[] = $t->struktur_jabatan_id;
            } 
        }
        Program::getBawahanRecursive($strukturJabatanIdList, $strukturJabatanIdList);
        $searchModel->struktur_jabatan_list = $strukturJabatanIdList;
        
        // mendapatkan array of jabatan yg ada di list struktur
        $struktur_jabatan_list = array();
        foreach($strukturJabatanIdList as $s){
            $struktur_jabatan_list[] = StrukturJabatan::find()->where('deleted!=1')->andWhere(['struktur_jabatan_id' => $s])->one();
        }
        $struktur_jabatan_list = $this->_cleanBuildStrukturs(StrukturJabatan::_buildStrukturs($struktur_jabatan_list));

        $dataProvider = $searchModel->search(Yii::$app->request->post(), $strukturJabatanIdList);

        $mataAnggaranJabatan = StrukturJabatanHasMataAnggaran::find()->select(['mata_anggaran_id'])->where('deleted!=1')->andWhere(['in', 'struktur_jabatan_id', $searchModel->struktur_jabatan_list])->andWhere(['tahun_anggaran_id' => $searchModel->tahun_anggaran])->all();
        $mata_anggaran = MataAnggaran::find()->where('deleted!=1')->andWhere(['in', 'mata_anggaran_id', $mataAnggaranJabatan])->orderBy(['kode_anggaran' => SORT_ASC])->all();
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_program' => $status_program,
            'tahun_anggaran' => $tahun_anggaran,
            'mata_anggaran' => $mata_anggaran,
            'struktur_jabatan' => $struktur_jabatan,
            'struktur_jabatan_list' => $struktur_jabatan_list,
        ]);
    }

    public function actionExportProgram()
    {
            $this->menuGroup = '';
            $model = new ProgramSearch();
            $struktur_jabatan_list = StrukturJabatan::find()->where('deleted!=1')->andWhere(['mata_anggaran' => 1])->orderBy(['parent' => SORT_ASC])->all();
            $struktur_jabatan_list = $this->_cleanBuildStrukturs(StrukturJabatan::_buildStrukturs($struktur_jabatan_list));

            if($model->load(Yii::$app->request->post()))
            {   
                $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->andWhere(['tahun_anggaran_id' => $model->tahun_anggaran])->one();
                $sj_name = '';
                $sj_title = '';
                $first = true;
                foreach($struktur_jabatan_list as $sj){
                    if(in_array($sj->struktur_jabatan_id, $model->struktur_jabatan_list)){
                        $sj_name .= '_'.preg_replace('/\s+/', '', $sj->jabatan);
                        if(!$first) $sj_title .= ', '.$sj->jabatan; 
                        else{
                            $sj_title .= $sj->jabatan;
                            $first = false;
                        }
                    }
                }
                $_PHPExcel = new PHPExcel();
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'Unit Kerja: '.$sj_title);
                $thead = 3;
                $digit = 1000;
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$thead,'No');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$thead,'Standar');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$thead,'Mata Anggaran');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$thead,'Nama Program dan Kegiatan');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$thead,'Tujuan Kegiatan');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$thead,'Target Luaran/Indikator Capaian');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$thead,'Sasaran Kegiatan/Peserta Kegiatan');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$thead,'Bulan 1');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$thead,'2');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$thead,'3');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$thead,'4');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$thead,'5');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$thead,'6');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$thead,'7');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$thead,'8');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$thead,'9');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$thead,'10');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$thead,'11');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$thead,'12');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$thead,'Volume');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$thead,'Satuan');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$thead,'Harga Satuan ('.$digit.' Rupiah)');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$thead,'Jumlah ('.$digit.' Rupiah)');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23,$thead,'Sumber Dana ('.$digit.' Rupiah) IT Del');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24,$thead,'Sumber Dana ('.$digit.' Rupiah) Lainnya');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25,$thead,'Pelaksana Kegiatan');
                $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26,$thead,'Program Strategis (Renstra)');

                $data = $model->searchToExport();
                $no = 1;
                $sumberDanaDel = SumberDana::find()->where('deleted!=1')->andWhere(['name' => 'IT Del'])->orderBy(['created_at' => SORT_ASC])->one();
                
                foreach($data as $d){
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$thead+$no,$no);
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$thead+$no,'Standar '.$d->strukturJabatanHasMataAnggaran->mataAnggaran->standar->nomor.': '.$d->strukturJabatanHasMataAnggaran->mataAnggaran->standar->name);
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$thead+$no,$d->strukturJabatanHasMataAnggaran->mataAnggaran->kode_anggaran.' '.$d->strukturJabatanHasMataAnggaran->mataAnggaran->name);
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$thead+$no,$d->kode_program.' '.$d->name);
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$thead+$no,strip_tags($d->tujuan));
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$thead+$no,strip_tags($d->target));
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$thead+$no,strip_tags($d->sasaran));

                    $ProgramHasWaktu = ProgramHasWaktu::find()->where('deleted!=1')->andWhere(['program_id' => $d->program_id])->all();
                    $colWaktuFirst = 7;
                    foreach($ProgramHasWaktu as $p){
                        $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colWaktuFirst+$p->bulan->bulan-1,$thead+$no,'X');    
                    }
                    
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$thead+$no,$d->volume);
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$thead+$no,$d->satuan->name);
                    
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$thead+$no,number_format($d->harga_satuan/$digit,2,',','.'));
                    $_PHPExcel->getActiveSheet()->getStyleByColumnAndRow(21,$thead+$no)->getNumberFormat()->setFormatCode("#.##0,00");

                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$thead+$no,number_format($d->jumlah/$digit,2,',','.'));
                    $_PHPExcel->getActiveSheet()->getStyleByColumnAndRow(22,$thead+$no)->getNumberFormat()->setFormatCode("#.##0,00");

                    $ProgramHasSumberDanaDel = ProgramHasSumberDana::find()->where('deleted!=1')->andWhere(['program_id' => $d->program_id, 'sumber_dana_id' => $sumberDanaDel->sumber_dana_id])->orderBy(['created_at' => SORT_DESC])->one();
                    if(!empty($ProgramHasSumberDanaDel)){
                        $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23,$thead+$no,number_format($ProgramHasSumberDanaDel->jumlah/$digit,2,',','.'));
                        $_PHPExcel->getActiveSheet()->getStyleByColumnAndRow(23,$thead+$no)->getNumberFormat()->setFormatCode("#.##0,00");
                    }
                    else $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23,$thead+$no,'0');

                    $ProgramHasSumberDanaNonDel = ProgramHasSumberDana::find()->where('deleted!=1')->andWhere(['program_id' => $d->program_id])->andWhere(['not', ['sumber_dana_id' => $sumberDanaDel->sumber_dana_id]])->all();
                    $temp = 0;
                    foreach($ProgramHasSumberDanaNonDel as $p){
                        $temp += $p->jumlah;
                    }
                    if($temp>0){
                        $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24,$thead+$no,number_format($temp/$digit,2,',','.'));
                        $_PHPExcel->getActiveSheet()->getStyleByColumnAndRow(24,$thead+$no)->getNumberFormat()->setFormatCode("#.##0,00");
                    }

                    if(isset($d->dilaksanakan_oleh))
                        $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25,$thead+$no,$d->dilaksanakanOleh->jabatan.(isset($d->dilaksanakanOleh->unit->inisial)?' - '.$d->dilaksanakanOleh->unit->inisial:''));
                    $_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26,$thead+$no,$d->rencanaStrategis->nomor.' ');
                    $no++;
                }
                $_objWriter = PHPExcel_IOFactory::createWriter($_PHPExcel,'Excel2007');
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="RKA_'.$tahun_anggaran->tahun.''.$sj_name.'".xlsx"');
                $_objWriter->save('php://output');
            }
            else{
                $status_program = StatusProgram::find()->where('deleted != 1')->andWhere(['in', 'status_program_id', [3,5,8]])->All();
                $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->All();

                $model->status_program = array();
                foreach($status_program as $sp){
                    $model->status_program[] = $sp->status_program_id;
                }
                $model->struktur_jabatan_list = array();
                foreach($struktur_jabatan_list as $sj){
                    $model->struktur_jabatan_list[] = $sj->struktur_jabatan_id;
                }
                return $this->render ('ExportProgram',
                [
                    'model' => $model,
                    'status_program' => $status_program,
                    'tahun_anggaran' => $tahun_anggaran,
                    'struktur_jabatan_list' => $struktur_jabatan_list,
                ]);
          }
    }

    public function _cleanBuildStrukturs($strukturs){
        $temp = array();
        foreach($strukturs as $s){
            if(isset($s->jabatan))
                $temp[] = $s;
        }
        return $temp;
    }

    public function actionJabatanIndex()
    {
        /*
            $struktur_jabatan = dropdown
            $search_model->struktur_jabatan2 = dataProvider
            $search_model->struktur_jabatan_old = cek, apakah dropdown berubah
            $struktur_jabatan_list = checkboxlist
        */
        $searchModel = new ProgramSearch();
        $inst_api = new InstApiModel();

        /*
            - mendapatkan semua jabatan yg dimiliki pegawai yg memiliki mata anggaran
            - mendapatkan semua bawahan dari jabatan yg dimiliki pegawai yg memiliki mata anggaran
        */
        $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
        $jabatanByPegawai = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
        //$jabatanIdAnggaranByPegawai = array();
        $struktur_jabatan = array();
        foreach($jabatanByPegawai as $s){
            if($s->mata_anggaran==1){
                $struktur_jabatan[] = $s;
                //$jabatanIdAnggaranByPegawai[] = $s->struktur_jabatan_id;
            }
        }

        // set tahun anggaran default tahun anggaran terakhir/sekarang
        $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->All();
        if (!isset(Yii::$app->request->post()['ProgramSearch']['tahun_anggaran'])){
            $searchModel->tahun_anggaran = $tahun_anggaran[0]->tahun_anggaran_id;
        }

        // mendapatkan list bawahan jabatan yg dipilih user dr dropdown jabatan lalu di assign ke properti objek search model
        $strukturJabatanIdList = array();
        if (!isset(Yii::$app->request->post()['ProgramSearch']['struktur_jabatan'])){
            $searchModel->struktur_jabatan = $struktur_jabatan[0]->struktur_jabatan_id;
            $searchModel->struktur_jabatan_old = $struktur_jabatan[0]->struktur_jabatan_id;
            $strukturJabatanIdList[] = $struktur_jabatan[0]->struktur_jabatan_id;
        }else{
            $strukturJabatanIdList[] = Yii::$app->request->post()['ProgramSearch']['struktur_jabatan'];
        }
        Program::getBawahanRecursive($strukturJabatanIdList, $strukturJabatanIdList);
        $searchModel->struktur_jabatan_list = $strukturJabatanIdList;

        // mendapatkan pejabat id dr jabatan yg dipilih
        $pejabat_id = null;
        $pejabat_id = $inst_api->getPejabatByPegawaiIdJabatan($pegawai->pegawai_id, $strukturJabatanIdList[0]);
        $pejabat_id = $pejabat_id->pejabat_id;

        // mendapatkan array of jabatan yg ada di list struktur
        $struktur_jabatan_list = array();
        foreach($strukturJabatanIdList as $s){
            $struktur_jabatan_list[] = StrukturJabatan::find()->where('deleted!=1')->andWhere(['struktur_jabatan_id' => $s])->one();
        }
        $struktur_jabatan_list = $this->_cleanBuildStrukturs(StrukturJabatan::_buildStrukturs($struktur_jabatan_list));

        // mendapatkan array of pejabat id by pegawai
        /*$pejabats = $inst_api->getPejabatByPegawaiId(1$pegawai->pegawai_id);
        $pejabatIdByPegawai = array();
        foreach($pejabats as $p){
            $pejabatIdByPegawai[] = $p->pejabat_id;
        }*/

        $dataProvider = $searchModel->searchByJabatan(Yii::$app->request->post(), $strukturJabatanIdList);
        
        // mendapatkan list mata anggaran dari jabatan yg dipilih pada checkbox dan by tahun anggaran yg dipilih pada dropdown
        $mataAnggaranJabatan = StrukturJabatanHasMataAnggaran::find()->select(['mata_anggaran_id'])->where('deleted!=1')->andWhere(['in', 'struktur_jabatan_id', $searchModel->struktur_jabatan_list])->andWhere(['tahun_anggaran_id' => $searchModel->tahun_anggaran])->all();
        $mata_anggaran = MataAnggaran::find()->where('deleted!=1')->andWhere(['in', 'mata_anggaran_id', $mataAnggaranJabatan])->orderBy(['kode_anggaran' => SORT_ASC])->all();

        $status_program = StatusProgram::find()->where('deleted != 1')->All();

        $penugasan_anggaran = StrukturJabatanHasMataAnggaran::find()->where('rakx_struktur_jabatan_has_mata_anggaran.deleted!=1')->andWhere(['rakx_struktur_jabatan_has_mata_anggaran.tahun_anggaran_id' => $searchModel->tahun_anggaran])->joinWith([
                'strukturJabatan' => function($query){
                    $query->where('inst_struktur_jabatan.deleted!=1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC]);
                },
                'mataAnggaran' => function($query){
                    $query->where('rakx_mata_anggaran.deleted!=1')->orderBy(['rakx_mata_anggaran.kode_anggaran' => SORT_ASC, 'rakx_mata_anggaran.name' => SORT_ASC]);
                }
            ])->all();

        return $this->render('JabatanIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_program' => $status_program,
            'tahun_anggaran' => $tahun_anggaran,
            'mata_anggaran' => $mata_anggaran,
            'struktur_jabatan' => $struktur_jabatan,
            'struktur_jabatan_list' => $struktur_jabatan_list,
            //'jabatanIdAnggaranByPegawai' => $jabatanIdAnggaranByPegawai,
            'pejabat_id' => $pejabat_id,
            'penugasan_anggaran' => $penugasan_anggaran,
            //'pegawai_id' => 1,//$pegawai->pegawai_id,
            //'inst_api' => $inst_api,
            //'pejabatIdByPegawai' => $pejabatIdByPegawai,
        ]);
    }

    public function actionUsulanIndex()
    {
        $this->menuGroup = 'rakx-program-usulan';
        $searchModel = new ProgramSearch();

        $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
        $inst_api = new InstApiModel();
        $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
        //$struktur_jabatan = array();
        $sj = array();
        foreach($temp as $s){
            if($s->mata_anggaran==1){
                //$struktur_jabatan[] = $s;
                $sj[] = $s->struktur_jabatan_id;
            }
        }

        $temp = $inst_api->getPejabatByPegawaiId($pegawai->pegawai_id);
        $pj = array();
        foreach($temp as $p){
            $pj[] = $p->pejabat_id;
        }

        $status_program = StatusProgram::find()->where('deleted != 1')->All();
        $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->All();
        //$mata_anggaran = MataAnggaran::find()->where('deleted != 1')->orderBy(['kode_anggaran' => SORT_ASC])->All();

        if (!isset(Yii::$app->request->queryParams['ProgramSearch']['tahun_anggaran'])){
            $searchModel->tahun_anggaran = $tahun_anggaran[0]->tahun_anggaran_id;
        }
        
        $dataProvider = $searchModel->searchByUsulan(Yii::$app->request->queryParams, $sj, $pj);

        $strukturJabatanIdList = array();
        $ma2 = array();
        foreach($dataProvider->models as $m){
            $strukturJabatanIdList[] = $m->strukturJabatanHasMataAnggaran->struktur_jabatan_id;
            $ma2[] = $m->strukturJabatanHasMataAnggaran->mata_anggaran_id;
        }

        $struktur_jabatan = StrukturJabatan::find()->where('deleted!=1')->andWhere(['in', 'struktur_jabatan_id', $strukturJabatanIdList])->all();
        $mata_anggaran = MataAnggaran::find()->where('deleted!=1')->andWhere(['in', 'mata_anggaran_id', $ma2])->orderBy(['kode_anggaran' => SORT_ASC])->all();

        return $this->render('UsulanIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_program' => $status_program,
            'tahun_anggaran' => $tahun_anggaran,
            'mata_anggaran' => $mata_anggaran,
            'struktur_jabatan' => $struktur_jabatan,

        ]);
    }

    /**
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramView($id, $pejabat_id=null, $tab=null)
    {
        $model = $this->findModel($id);

        $searchModelDetil = new DetilProgramSearch();
        $dataProviderDetil = $searchModelDetil->search(Yii::$app->request->queryParams, $id);
        $count_detil = DetilProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $searchModelDana = new ProgramHasSumberDanaSearch();
        $dataProviderDana = $searchModelDana->search(Yii::$app->request->queryParams, $id);

        //$searchModelReview = new ReviewProgramSearch();
        //$dataProviderReview = $searchModelReview->search(Yii::$app->request->queryParams, $id);
        $model_review = new ReviewProgram();
        $review_program = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->orderBy(['tanggal_review' => SORT_DESC])->all();
        $count_review = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $tab_program = false;
        $tab_status = false;
        $tab_dana = false;
        $tab_detil = false;
        $tab_review = false;
        if($tab == 'data_program' || $tab == null)
        {
            $tab_program = true;
        }
        else if($tab == 'data_detil')
        {
            $tab_detil = true;
        }
        else if($tab == 'data_review')
        {
            $tab_review = true;
        }
        else if($tab == 'data_status')
        {
            $tab_status = true;
        }
        else if($tab == 'data_dana')
        {
            $tab_dana = true;
        }

        if($model_review->load(Yii::$app->request->post())){
            if($model->status_program_id == 3 || $model->status_program_id >= 5)
                Yii::$app->jobControl->setAsJob('proses-anggaran');
            $model_review->program_id = $model->program_id;
            $model_review->tanggal_review = date('Y-m-d H:i:s');
            $model_review->pejabat_id = $pejabat_id;

            if($model_review->save()){
                $this->_afterReview($model);
                
                $model_review = new ReviewProgram();
                $review_program = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->orderBy(['tanggal_review' => SORT_DESC])->all();
                $count_review = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();
                $tab_program = false;
                $tab_status = false;
                $tab_dana = false;
                $tab_detil = false;
                $tab_review = true;
                return $this->render('ProgramView', [
                    'model' => $model,
                    'searchModelDetil' => $searchModelDetil,
                    'dataProviderDetil' => $dataProviderDetil,
                    'searchModelDana' => $searchModelDana,
                    'dataProviderDana' => $dataProviderDana,
                    //'searchModelReview' => $searchModelReview,
                    //'dataProviderReview' => $dataProviderReview,
                    'model_review' => $model_review,
                    'review_program' => $review_program,
                    //'pejabat' => $pejabat,
                    'tab_program'=>$tab_program,
                    'tab_detil'=>$tab_detil,
                    'count_detil' => $count_detil,
                    'tab_review'=>$tab_review,
                    'tab_status'=>$tab_status,
                    'tab_dana'=>$tab_dana,
                    'count_review' => $count_review,
                    'review_program' => $review_program,
                    'pejabat_id' => $pejabat_id,
                ]);
            }
        }

        return $this->render('ProgramView', [
            'model' => $model,
            'searchModelDetil' => $searchModelDetil,
            'dataProviderDetil' => $dataProviderDetil,
            'searchModelDana' => $searchModelDana,
            'dataProviderDana' => $dataProviderDana,
            //'searchModelReview' => $searchModelReview,
            //'dataProviderReview' => $dataProviderReview,
            'model_review' => $model_review,
            'review_program' => $review_program,
            //'pejabat' => $pejabat,
            'tab_program'=>$tab_program,
            'tab_detil'=>$tab_detil,
            'count_detil' => $count_detil,
            'tab_review'=>$tab_review,
            'tab_status'=>$tab_status,
            'tab_dana'=>$tab_dana,
            'count_review' => $count_review,
            'review_program' => $review_program,
            'pejabat_id' => $pejabat_id,
        ]);
    }

    public function _afterReview($model){
        if($model->status_program_id==1 || $model->status_program_id==3){
            $model->status_program_id = 2;
        }else if($model->status_program_id==5 || $model->status_program_id==8){
            $model->status_program_id = 7;
        }
        $model->save(); 
    }

    public function actionProgramUsulanView($id, $tab=null)
    {
        $this->menuGroup = 'rakx-program-usulan';
        $model = $this->findModel($id);

        $searchModelDetil = new DetilProgramSearch();
        $dataProviderDetil = $searchModelDetil->search(Yii::$app->request->queryParams, $id);
        $count_detil = DetilProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $searchModelDana = new ProgramHasSumberDanaSearch();
        $dataProviderDana = $searchModelDana->search(Yii::$app->request->queryParams, $id);

        //$searchModelReview = new ReviewProgramSearch();
        //$dataProviderReview = $searchModelReview->search(Yii::$app->request->queryParams, $id);
        $model_review = new ReviewProgram();
        $review_program = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->orderBy(['tanggal_review' => SORT_DESC])->all();
        $count_review = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $tab_program = false;
        $tab_status = false;
        $tab_dana = false;
        $tab_detil = false;
        $tab_review = false;
        if($tab == 'data_program' || $tab == null)
        {
            $tab_program = true;
        }
        else if($tab == 'data_detil')
        {
            $tab_detil = true;
        }
        else if($tab == 'data_review')
        {
            $tab_review = true;
        }
        else if($tab == 'data_status')
        {
            $tab_status = true;
        }
        else if($tab == 'data_dana')
        {
            $tab_dana = true;
        }

        return $this->render('ProgramUsulanView', [
            'model' => $model,
            'searchModelDetil' => $searchModelDetil,
            'dataProviderDetil' => $dataProviderDetil,
            'searchModelDana' => $searchModelDana,
            'dataProviderDana' => $dataProviderDana,
            //'searchModelReview' => $searchModelReview,
            //'dataProviderReview' => $dataProviderReview,
            'model_review' => $model_review,
            'review_program' => $review_program,
            //'pejabat' => $pejabat,
            'tab_program'=>$tab_program,
            'tab_detil'=>$tab_detil,
            'count_detil' => $count_detil,
            'tab_review'=>$tab_review,
            'tab_status'=>$tab_status,
            'tab_dana'=>$tab_dana,
            'count_review' => $count_review,
            'review_program' => $review_program,
        ]);
    }

    public function actionProgramKompilasiView($id, $tab=null)
    {
        $this->menuGroup = '';
        $model = $this->findModel($id);

        $searchModelDetil = new DetilProgramSearch();
        $dataProviderDetil = $searchModelDetil->search(Yii::$app->request->queryParams, $id);
        $count_detil = DetilProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $searchModelDana = new ProgramHasSumberDanaSearch();
        $dataProviderDana = $searchModelDana->search(Yii::$app->request->queryParams, $id);

        //$searchModelReview = new ReviewProgramSearch();
        //$dataProviderReview = $searchModelReview->search(Yii::$app->request->queryParams, $id);
        $model_review = new ReviewProgram();
        $review_program = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->orderBy(['tanggal_review' => SORT_DESC])->all();
        $count_review = ReviewProgram::find()->where('deleted != 1')->andWhere(['program_id' => $id])->count();

        $tab_program = false;
        $tab_status = false;
        $tab_dana = false;
        $tab_detil = false;
        $tab_review = false;
        if($tab == 'data_program' || $tab == null)
        {
            $tab_program = true;
        }
        else if($tab == 'data_detil')
        {
            $tab_detil = true;
        }
        else if($tab == 'data_review')
        {
            $tab_review = true;
        }
        else if($tab == 'data_status')
        {
            $tab_status = true;
        }
        else if($tab == 'data_dana')
        {
            $tab_dana = true;
        }

        return $this->render('ProgramKompilasiView', [
            'model' => $model,
            'searchModelDetil' => $searchModelDetil,
            'dataProviderDetil' => $dataProviderDetil,
            'searchModelDana' => $searchModelDana,
            'dataProviderDana' => $dataProviderDana,
            //'searchModelReview' => $searchModelReview,
            //'dataProviderReview' => $dataProviderReview,
            'model_review' => $model_review,
            'review_program' => $review_program,
            //'pejabat' => $pejabat,
            'tab_program'=>$tab_program,
            'tab_detil'=>$tab_detil,
            'count_detil' => $count_detil,
            'tab_review'=>$tab_review,
            'tab_status'=>$tab_status,
            'tab_dana'=>$tab_dana,
            'count_review' => $count_review,
            'review_program' => $review_program,
        ]);
    }

    /**
     * Creates a new Program model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProgramAdd()
    {
        $this->menuGroup = '';
        Yii::$app->jobControl->setAsJob('proses-anggaran');
        $model = new Program();
        $tahun_anggaran_now = TahunAnggaran::find()->where('deleted!=1')->orderBy(['tahun' => SORT_DESC])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $lastProgram = Program::find()->joinWith([
                'strukturJabatanHasMataAnggaran' => function ($query) use($model) {
                    $query->where(['tahun_anggaran_id' => $model->strukturJabatanHasMataAnggaran->tahun_anggaran_id, 'mata_anggaran_id' => $model->strukturJabatanHasMataAnggaran->mata_anggaran_id]);
                }
            ])->orderBy(['program_id' => SORT_DESC])->one();

            $countProgram = 0;
            if(!empty($lastProgram))
                $countProgram = explode('.',$lastProgram->kode_program)[count(explode('.',$lastProgram->kode_program))-1];
            $model->kode_program = $model->strukturJabatanHasMataAnggaran->mataAnggaran->kode_anggaran.'.'.($countProgram+1);
            $model->jumlah = (string)($model->volume * $model->harga_satuan);
            $model->jumlah_sebelum_revisi = $model->jumlah;

            $peg = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            $inst_api = new InstApiModel();
            $pej = $inst_api->getPejabatByPegawaiIdJabatan($peg->pegawai_id, $model->strukturJabatanHasMataAnggaran->strukturJabatan->struktur_jabatan_id);
            $model->diusulkan_oleh = $pej->pejabat_id;
            $model->dilaksanakan_oleh = $pej->strukturJabatan->struktur_jabatan_id;
            
            $model->tanggal_diusulkan = date('Y-m-d H:i:s');
            $model->status_program_id = 0;
            if($model->save()){
                if(!empty($model->waktu)){
                    foreach($model->waktu as $w){
                        $program_has_waktu = new ProgramHasWaktu();
                        $program_has_waktu->program_id = $model->program_id;
                        $program_has_waktu->bulan_id = $w;
                        $program_has_waktu->save();
                    }
                }
                return $this->redirect(['program-view', 'id' => $model->program_id, 'is_review' => false, 'tab' => 'data_dana']);
            }
            else {
                echo '<pre>';
                print_r($model->errors);
            }
        } else {
            $rencana_strategis = RencanaStrategis::find()->where('deleted != 1')->orderBy(['nomor' => SORT_ASC])->All();
            $satuan = Satuan::find()->where('deleted != 1')->orderBy(['name' => SORT_ASC])->All();

            $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            $inst_api = new InstApiModel();
            $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
            $jabatans = array();
            foreach($temp as $s){
                if($s->mata_anggaran==1)
                    $jabatans[] = $s->struktur_jabatan_id;
            }
            $struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('rakx_struktur_jabatan_has_mata_anggaran.deleted != 1')->andWhere(['in', 'rakx_struktur_jabatan_has_mata_anggaran.struktur_jabatan_id', $jabatans])->andWhere(['rakx_struktur_jabatan_has_mata_anggaran.tahun_anggaran_id' => $tahun_anggaran_now->tahun_anggaran_id])->joinWith([
                'strukturJabatan' => function($query){
                    $query->where('inst_struktur_jabatan.deleted!=1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC]);
                },
                'mataAnggaran' => function($query){
                    $query->where('rakx_mata_anggaran.deleted!=1')->orderBy(['rakx_mata_anggaran.kode_anggaran' => SORT_ASC]);
                }
            ])->all();

            $waktu = Bulan::find()->all();

            $pelaksana = StrukturJabatan::find()->where('deleted != 1')->andWhere(['mata_anggaran' => 1])->orderBy(['jabatan' => SORT_ASC])->all();
            
            return $this->render('ProgramAdd', [
                'model' => $model,
                'rencana_strategis' => $rencana_strategis,
                'satuan' => $satuan,
                'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
                'waktu' => $waktu,
                'pelaksana' => $pelaksana,
            ]);
        }
    }

    public function actionProgramUsulanAdd()
    {
        $this->menuGroup = '';
        Yii::$app->jobControl->setAsJob('proses-anggaran');
        $model = new Program();

        $tahun_anggaran_now = TahunAnggaran::find()->where('deleted!=1')->orderBy(['tahun' => SORT_DESC])->one();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $lastProgram = Program::find()->joinWith([
                'strukturJabatanHasMataAnggaran' => function ($query) use($model){
                    $query->where(['tahun_anggaran_id' => $model->strukturJabatanHasMataAnggaran->tahun_anggaran_id, 'mata_anggaran_id' => $model->strukturJabatanHasMataAnggaran->mata_anggaran_id]);
                }
            ])->orderBy(['program_id' => SORT_DESC])->one();

            $countProgram = 0;
            if(!empty($lastProgram))
                $countProgram = explode('.',$lastProgram->kode_program)[count(explode('.',$lastProgram->kode_program))-1];
            $model->kode_program = $model->strukturJabatanHasMataAnggaran->mataAnggaran->kode_anggaran.'.'.($countProgram+1);
            $model->jumlah = (string)($model->volume * $model->harga_satuan);
            $model->jumlah_sebelum_revisi = $model->jumlah;
            $pej = Pejabat::find()->where(['pejabat_id' => $model->diusulkan_oleh])->andWhere('deleted!=1')->one();
            $model->dilaksanakan_oleh = $pej->strukturJabatan->struktur_jabatan_id;//$model->strukturJabatanHasMataAnggaran->strukturJabatan->struktur_jabatan_id;

            /***************/
                // $inst_api = new InstApiModel();
                // $pej_temp = $inst_api->getPejabatByJabatan($model->dilaksanakan_oleh);
                // $model->diusulkan_oleh = $pej_temp[0]->pejabat_id;
            /***************/

            $model->tanggal_diusulkan = date('Y-m-d H:i:s');
            $model->status_program_id = 0;
            if($model->save()){
                if(!empty($model->waktu)){
                    foreach($model->waktu as $w){
                        $program_has_waktu = new ProgramHasWaktu();
                        $program_has_waktu->program_id = $model->program_id;
                        $program_has_waktu->bulan_id = $w;
                        $program_has_waktu->save();
                    }
                }
                return $this->redirect(['program-usulan-view', 'id' => $model->program_id, 'tab' => 'data_dana']);
            }
            else echo 'error';
        } else {
            $rencana_strategis = RencanaStrategis::find()->where('deleted != 1')->orderBy(['nomor' => SORT_ASC])->All();
            $satuan = Satuan::find()->where('deleted != 1')->orderBy(['name' => SORT_ASC])->All();

            $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            $inst_api = new InstApiModel();
            $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
            $jabatans = array();
            foreach($temp as $s){
                if($s->mata_anggaran==1)
                    $jabatans[] = $s->struktur_jabatan_id;
            }

            $struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('rakx_struktur_jabatan_has_mata_anggaran.deleted != 1')->andWhere(['not in', 'rakx_struktur_jabatan_has_mata_anggaran.struktur_jabatan_id', $jabatans])->andWhere(['rakx_struktur_jabatan_has_mata_anggaran.tahun_anggaran_id' => $tahun_anggaran_now->tahun_anggaran_id])->joinWith([
                'strukturJabatan' => function($query){
                    $query->where('inst_struktur_jabatan.deleted!=1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC]);
                },
                'mataAnggaran' => function($query){
                    $query->where('rakx_mata_anggaran.deleted!=1')->orderBy(['rakx_mata_anggaran.kode_anggaran' => SORT_ASC]);
                }
            ])->all();

            $waktu = Bulan::find()->all();

            $pengusul = $inst_api->getPejabatByPegawaiId($pegawai->pegawai_id);
            $temp = array();
            foreach($pengusul as $p){
                if($p->strukturJabatan->mata_anggaran==1)
                    $temp[] = $p;
            }
            $pengusul = $temp;

            return $this->render('ProgramUsulanAdd', [
                'model' => $model,
                'rencana_strategis' => $rencana_strategis,
                'satuan' => $satuan,
                'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
                'waktu' => $waktu,
                'pengusul' => $pengusul,
            ]);
        }
    }

    /**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramEdit($id)
    {
        Yii::$app->jobControl->setAsJob('proses-anggaran');
        $model = $this->findModel($id);
        
        // if($model->status_program_id == 3 || $model->status_program_id >= 5)
        //     Yii::$app->jobControl->setAsJob('proses-penambahan-anggaran');
        $tahun_anggaran_now = TahunAnggaran::find()->where('deleted!=1')->orderBy(['tahun' => SORT_DESC])->one();
        $program_has_waktu = ProgramHasWaktu::find()->where(['program_id' => $id])->andWhere('deleted != 1')->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->status_program_id == 5){
                $model->is_revisi = 1;
                $inst_api = new InstApiModel();
                $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
                $pej = $inst_api->getPejabatByPegawaiIdJabatan($pegawai->pegawai_id, $model->strukturJabatanHasMataAnggaran->strukturJabatan->struktur_jabatan_id);
                $model->direvisi_oleh = $pej->pejabat_id;
                $model->tanggal_direvisi = date('Y-m-d H:i:s');
                $model->save();
            }else{
                $model->jumlah = (string)($model->volume * $model->harga_satuan);
                $model->jumlah_sebelum_revisi = $model->jumlah;
                $model->save();
            }
            $temp = array();
            foreach($program_has_waktu as $pw){
                $temp[] = $pw->bulan_id;
                if($model->waktu === '')
                    $model->waktu = array();
                if(!in_array($pw->bulan_id, $model->waktu)){
                    $program_has_waktu2 = ProgramHasWaktu::find()->where(['bulan_id' => $pw->bulan_id, 'program_id' => $model->program_id])->andWhere('deleted != 1')->orderBy(['created_at' => SORT_DESC])->one();
                    $program_has_waktu2->softDelete();   
                }
            }
            if(!empty($model->waktu)){
                foreach($model->waktu as $w){
                    if(!in_array($w, $temp)){
                        $program_has_waktu2 = new ProgramHasWaktu();
                        $program_has_waktu2->program_id = $model->program_id;
                        $program_has_waktu2->bulan_id = $w;
                        $program_has_waktu2->save();
                    }
                }
            }

            Program::toProgramValidity($model->program_id);
            return $this->redirect(['program-view', 'id' => $model->program_id, 'is_review' => false]);
        } else {
            $rencana_strategis = RencanaStrategis::find()->where('deleted != 1')->All();
            $satuan = Satuan::find()->where('deleted != 1')->orderBy(['name' => SORT_ASC])->All();

            $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            $inst_api = new InstApiModel();
            $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
            $jabatans = array();
            foreach($temp as $s){
                if($s->mata_anggaran==1)
                    $jabatans[] = $s->struktur_jabatan_id;
            }
            // $struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('deleted != 1')/*->andWhere(['struktur_jabatan_has_mata_anggaran_id' => $model->struktur_jabatan_has_mata_anggaran_id])*/->all();

            $struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('rakx_struktur_jabatan_has_mata_anggaran.deleted != 1')->andWhere(['in', 'rakx_struktur_jabatan_has_mata_anggaran.struktur_jabatan_id', $jabatans])->andWhere(['rakx_struktur_jabatan_has_mata_anggaran.tahun_anggaran_id' => $tahun_anggaran_now->tahun_anggaran_id])->joinWith([
                'strukturJabatan' => function($query){
                    $query->where('inst_struktur_jabatan.deleted!=1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC]);
                },
                'mataAnggaran' => function($query){
                    $query->where('rakx_mata_anggaran.deleted!=1')->orderBy(['rakx_mata_anggaran.kode_anggaran' => SORT_ASC]);
                }
            ])->all();

            $waktu = Bulan::find()->all();
            $model->waktu = array();
            foreach($program_has_waktu as $pw){
                $model->waktu[] = $pw->bulan_id;
            }

            $pelaksana = StrukturJabatan::find()->where('deleted != 1')->andWhere(['mata_anggaran' => 1])->orderBy(['jabatan' => SORT_ASC])->all();

            return $this->render('ProgramEdit', [
                'model' => $model,
                'rencana_strategis' => $rencana_strategis,
                'satuan' => $satuan,
                'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
                'waktu' => $waktu,
                'pelaksana' => $pelaksana,
            ]);
        }
    }

    public function actionProgramUsulanEdit($id)
    {
        Yii::$app->jobControl->setAsJob('proses-anggaran');
        $this->menuGroup = 'rakx-program-usulan';
        $model = $this->findModel($id);
        $program_has_waktu = ProgramHasWaktu::find()->where(['program_id' => $id])->andWhere('deleted != 1')->all();
        $tahun_anggaran_now = TahunAnggaran::find()->where('deleted!=1')->orderBy(['tahun' => SORT_DESC])->one();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                /***************/
                    // $inst_api = new InstApiModel();
                    // $pej_temp = $inst_api->getPejabatByJabatan($model->dilaksanakan_oleh);
                    // $model->diusulkan_oleh = $pej_temp[0]->pejabat_id;
                /***************/
            
            if($model->status_program_id == 5){
                $model->is_revisi = 1;
                $inst_api = new InstApiModel();
                $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
                $pej = $inst_api->getPejabatByPegawaiIdJabatan($pegawai->pegawai_id, $model->strukturJabatanHasMataAnggaran->strukturJabatan->struktur_jabatan_id);
                $model->direvisi_oleh = $pej->pejabat_id;
                $model->tanggal_direvisi = date('Y-m-d H:i:s');
                $model->save();
            }else{
                $model->jumlah = (string)($model->volume * $model->harga_satuan);
                $model->jumlah_sebelum_revisi = $model->jumlah;
                $model->save();
            }
            $temp = array();
            foreach($program_has_waktu as $pw){
                $temp[] = $pw->bulan_id;
                if($model->waktu === '')
                    $model->waktu = array();
                if(!in_array($pw->bulan_id, $model->waktu)){
                    $program_has_waktu2 = ProgramHasWaktu::find()->where(['bulan_id' => $pw->bulan_id, 'program_id' => $model->program_id])->andWhere('deleted != 1')->orderBy(['created_at' => SORT_DESC])->one();
                    $program_has_waktu2->softDelete();   
                }
            }
            if(!empty($model->waktu)){
                foreach($model->waktu as $w){
                    if(!in_array($w, $temp)){
                        $program_has_waktu2 = new ProgramHasWaktu();
                        $program_has_waktu2->program_id = $model->program_id;
                        $program_has_waktu2->bulan_id = $w;
                        $program_has_waktu2->save();
                    }
                }
            }

            Program::toProgramValidity($model->program_id);
            //StrukturJabatanHasMataAnggaran::updateSubtotal($model->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(['program-usulan-view', 'id' => $model->program_id]);
        } else {
            $rencana_strategis = RencanaStrategis::find()->where('deleted != 1')->All();
            $satuan = Satuan::find()->where('deleted != 1')->orderBy(['name' => SORT_ASC])->All();

            $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            $inst_api = new InstApiModel();
            $temp = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
            $jabatans = array();
            foreach($temp as $s){
                if($s->mata_anggaran==1)
                    $jabatans[] = $s->struktur_jabatan_id;
            }
            //$struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('deleted != 1')->andWhere(['struktur_jabatan_has_mata_anggaran_id' => $model->struktur_jabatan_has_mata_anggaran_id])->all();

            $struktur_jabatan_has_mata_anggaran = StrukturJabatanHasMataAnggaran::find()->where('rakx_struktur_jabatan_has_mata_anggaran.deleted != 1')->andWhere(['not in', 'rakx_struktur_jabatan_has_mata_anggaran.struktur_jabatan_id', $jabatans])->andWhere(['rakx_struktur_jabatan_has_mata_anggaran.tahun_anggaran_id' => $tahun_anggaran_now->tahun_anggaran_id])->joinWith([
                'strukturJabatan' => function($query){
                    $query->where('inst_struktur_jabatan.deleted!=1')->orderBy(['inst_struktur_jabatan.jabatan' => SORT_ASC]);
                },
                'mataAnggaran' => function($query){
                    $query->where('rakx_mata_anggaran.deleted!=1')->orderBy(['rakx_mata_anggaran.kode_anggaran' => SORT_ASC]);
                }
            ])->all();

            $waktu = Bulan::find()->all();
            $model->waktu = array();
            foreach($program_has_waktu as $pw){
                $model->waktu[] = $pw->bulan_id;
            }
            $pengusul = $inst_api->getPejabatByPegawaiId($pegawai->pegawai_id);
            $pelaksana = StrukturJabatan::find()->where('deleted != 1')->andWhere(['mata_anggaran' => 1])->orderBy(['jabatan' => SORT_ASC])->all();
            return $this->render('ProgramUsulanEdit', [
                'model' => $model,
                'rencana_strategis' => $rencana_strategis,
                'satuan' => $satuan,
                'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
                'waktu' => $waktu,
                'pengusul' => $pengusul,
                'pelaksana' => $pelaksana,
            ]);
        }
    }

    public function actionProgramReject($id, $pejabat_id)
    {
        $program = $this->findModel($id);
        $program->status_program_id = 4;
        $program->ditolak_oleh = $pejabat_id;
        $program->tanggal_ditolak = date('Y-m-d H:i:s');
           
        if($program->save()){
            //StrukturJabatanHasMataAnggaran::updateSubtotal($program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    public function actionProgramAccept($id, $pejabat_id)
    {
        Yii::$app->jobControl->setAsJob('proses-anggaran');
        $program = $this->findModel($id);
        $program->status_program_id = 3;
        $program->disetujui_oleh = $pejabat_id;
        $program->tanggal_disetujui = date('Y-m-d H:i:s');
           
        if($program->save()){
            //StrukturJabatanHasMataAnggaran::updateSubtotal($program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    public function actionProgramLegitimate($id)
    {
        $program = $this->findModel($id);
        $program->status_program_id = 5;
           
        if($program->save()){
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * Deletes an existing Program model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramDel($id)
    {
        $program = $this->findModel($id);
        if($program->softDelete()){
            //StrukturJabatanHasMataAnggaran::updateSubtotal($program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * Finds the Program model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Program the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Program::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

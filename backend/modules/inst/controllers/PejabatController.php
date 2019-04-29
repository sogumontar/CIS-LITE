<?php

namespace backend\modules\inst\controllers;

use Yii;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\search\PejabatSearch;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\search\StrukturJabatanSearch;
use backend\modules\inst\models\Instansi;
use backend\modules\inst\models\Pegawai;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PejabatController implements the CRUD actions for Pejabat model.
 */
class PejabatController extends Controller
{
    public $menuGroup = 'inst-pejabat';
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            /*'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => [],
                ],*/
                
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new PejabatSearch();

        //Set the search/filter parameter
        $status_expired = Yii::$app->request->get('status_expired');
        if($status_expired == 1 || $status_expired == 2 || $status_expired == 3){
            $params['PejabatSearch']['status_expired'] = $status_expired;
        }

        $dataProvider = $searchModel->search($params);
        //$struktur_jabatan = StrukturJabatan::find()->where(['not', ['deleted' => 1]])->All();
        $instansi = Instansi::find()->where('deleted != 1')->All();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'instansi' => $instansi,
            'status_expired' => $status_expired,
        ]);
    }

    public function actionPejabatHistoryAllView()
    {
        //$jabatan = StrukturJabatan::find()->where('deleted != 1')->all();

        $searchModel = new StrukturJabatanSearch();
        $dataProvider = $searchModel->searchForHistoryView(Yii::$app->request->queryParams);
        //$dataProviderOld = $searchModel->searchByPeriodByPegawai($pegawai_id, 'old', Yii::$app->request->queryParams);
        
        return $this->render('PejabatHistoryAllView', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPejabatByPegawaiView($pegawai_id)
    {
        $pegawai = Pegawai::find()->where(['pegawai_id' => $pegawai_id])->andWhere('deleted != 1')->one();

        $searchModel = new PejabatSearch();
        $dataProviderNow = $searchModel->searchByPeriodByPegawai($pegawai_id, 'now', Yii::$app->request->queryParams);
        $dataProviderOld = $searchModel->searchByPeriodByPegawai($pegawai_id, 'old', Yii::$app->request->queryParams);
        
        return $this->render('PejabatByPegawaiView', [
            'pegawai_id' => $pegawai_id,
            'pegawai' => $pegawai,
            'searchModel' => $searchModel,
            'dataProviderNow' => $dataProviderNow,
            'dataProviderOld' => $dataProviderOld,
        ]);
    }

    public function actionPejabatByJabatanView($jabatan_id, $otherRenderer = false)
    {
        $jabatan = StrukturJabatan::find()->where(['struktur_jabatan_id' => $jabatan_id])->andWhere('deleted != 1')->one();

        $searchModel = new PejabatSearch();
        $dataProviderNow = $searchModel->searchByPeriodByJabatan($jabatan_id, 'now', Yii::$app->request->queryParams);
        $dataProviderOld = $searchModel->searchByPeriodByJabatan($jabatan_id, 'old', Yii::$app->request->queryParams);

        return $this->render('PejabatByJabatanView', [
            'jabatan_id' => $jabatan_id,
            'jabatan' => $jabatan,
            'searchModel' => $searchModel,
            'dataProviderNow' => $dataProviderNow,
            'dataProviderOld' => $dataProviderOld,
            'otherRenderer' => $otherRenderer,
            'instansi_id' => $jabatan->instansi_id,
            'instansi_name' => $jabatan->instansi->name,
        ]);
    }

    public function actionPejabatView($id)
    {
        return $this->render('PejabatView', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPejabatAdd($jabatan_id = null, $pegawai_id = null)
    {
        $model = new Pejabat();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            //$file_sk = UploadedFile::getInstance($model, 'file_sk');
            //$_FILES['files']['name'][0] != null
            //print_r($_FILES);
            if(isset($_FILES['Pejabat']['name'])){

                $result = \Yii::$app->fileManager->saveUploadedFiles();
                if(isset($result)){
                    if($result->status == 'success'){
                        $model->kode_file = $result->fileinfo[0]->id;
                        $model->file_sk = $result->fileinfo[0]->name;
                    }
                    else{
                        \Yii::$app->messenger->addErrorFlash('Error while uploading file !');
                        return $this->redirect(\Yii::$app->request->referrer);
                    }
                }
                /*else{
                    \Yii::$app->messenger->addWarningFlash('Error while saving to database !');
                    return $this->redirect(\Yii::$app->request->referrer);
                }*/

                if($model->awal_masa_kerja <= date('Y-m-d') && $model->akhir_masa_kerja >= date('Y-m-d'))
                    $model->status_aktif = 1;

                if($model->save()){
                    return $this->redirect(['pejabat-view', 'id' => $model->pejabat_id]);
                }
                else{
                    \Yii::$app->messenger->addErrorFlash('Error while saving to database !');
                    return $this->redirect(\Yii::$app->request->referrer);
                }
            }
            else{
                \Yii::$app->messenger->addWarningFlash('Upload File SK dengan ekstensi .pdf !');
                return $this->redirect(\Yii::$app->request->referrer);
            }
            
            return 'error';
        } else {
            //jabatan
            $struktur_jabatan = StrukturJabatan::find()->where('deleted != 1')->orderBy(['instansi_id' => SORT_ASC, 'jabatan' => SORT_ASC])->All();
            //pegawai yg aktif
            $pegawai = Pegawai::find()->where(['in', 'status_aktif_pegawai_id', [1,2]])->andWhere('deleted != 1')->orderBy(['nama' => SORT_ASC])->All();

            if(!is_null($jabatan_id)){
                //validasi untuk yg sedang menjabat
                $pej = Pejabat::find()->select(['pegawai_id'])->where(['struktur_jabatan_id' => $jabatan_id])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();
                //$pegawai = Pegawai::find()->where(['not in', 'pegawai_id', $pej])->andWhere(['in', 'status_aktif_pegawai_id', [1,2]])->andWhere('deleted != 1')->orderBy(['nama' => SORT_ASC])->All();          

                $jab = StrukturJabatan::find()->where(['struktur_jabatan_id' => $jabatan_id])->andWhere('deleted != 1')->one();
                if($jab->is_multi_tenant == 0 && !empty($pej))
                {
                    \Yii::$app->messenger->addWarningFlash('Jabatan ini memiliki <b>Status Tenant: Single</b> dan sedang dijabat oleh seorang Pegawai !');
                    return $this->redirect(\Yii::$app->request->referrer);
                }
                $model->struktur_jabatan_id = $jabatan_id;
            }
            
            if(!is_null($pegawai_id)){
                $peg = Pegawai::find()->where(['pegawai_id' => $pegawai_id])->andWhere('deleted != 1')->one();
                if($peg->status_aktif_pegawai_id!=1 && $peg->status_aktif_pegawai_id!=2){
                    \Yii::$app->messenger->addWarningFlash("Pegawai ".$peg->nama." dalam Status <b>".$peg->statusAktifPegawai->desc."</b>, sehingga tidak bisa ditambahkan Jabatan.");
                    return $this->redirect(\Yii::$app->request->referrer);
                }
                
                $model->pegawai_id = $pegawai_id;

                //validasi untuk yg sedang menjabat
                //$pej = Pejabat::find()->select(['struktur_jabatan_id'])->where(['pegawai_id' => $pegawai_id])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

                //validasi untuk single tenant
                //$pejabats = Pejabat::find()->select(['struktur_jabatan_id'])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all(); 
                //$isSingle = StrukturJabatan::find()->select(['struktur_jabatan_id'])->where(['is_multi_tenant' => 0])->andWhere(['in', 'struktur_jabatan_id', $pejabats])->andWhere('deleted != 1')->All();
                
                //$struktur_jabatan = StrukturJabatan::find()->where(['not in', 'struktur_jabatan_id', $pej])->andWhere(['not in', 'struktur_jabatan_id', $isSingle])->andWhere('deleted != 1')->orderBy(['instansi_id' => SORT_ASC, 'jabatan' => SORT_ASC])->All();
            }
            
            return $this->render('PejabatAdd', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'pegawai' => $pegawai,
            ]);
        }
    }

    public function actionPejabatExtendKontrakAdd($id)
    {
        $oldModel = $this->findModel($id);
        $model = new Pejabat();
        $model->pegawai_id = $oldModel->pegawai_id;
        $model->struktur_jabatan_id = $oldModel->struktur_jabatan_id;
        
        $da = strtotime($oldModel->akhir_masa_kerja.' +1 days');
        $da = date('Y-m-d', $da);
        $model->awal_masa_kerja = $da;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if(isset($_FILES['Pejabat']['name'])){

                $result = \Yii::$app->fileManager->saveUploadedFiles();
                if($result->status == 'success'){
                    $model->kode_file = $result->fileinfo[0]->id;
                    $model->file_sk = $result->fileinfo[0]->name;
                }
                else{
                    \Yii::$app->messenger->addErrorFlash('Error while uploading file !');
                    return $this->redirect(\Yii::$app->request->referrer);
                }

                if($model->awal_masa_kerja <= date('Y-m-d') && $model->akhir_masa_kerja >= date('Y-m-d'))
                    $model->status_aktif = 1;

                if($model->save()){
                    return $this->redirect(['pejabat-view', 'id' => $model->pejabat_id]);
                }
                else{
                    \Yii::$app->messenger->addErrorFlash('Error while saving to database !');
                    return $this->redirect(\Yii::$app->request->referrer);
                }
            }
            else{
                \Yii::$app->messenger->addWarningFlash('Upload File SK dengan ekstensi .pdf !');
                return $this->redirect(\Yii::$app->request->referrer);
            }
            
            return 'error';
        } else {
            $peg = Pegawai::find()->where(['pegawai_id' => $oldModel->pegawai_id])->andWhere('deleted != 1')->one();
            if($peg->status_aktif_pegawai_id!=1 && $peg->status_aktif_pegawai_id!=2){
                \Yii::$app->messenger->addWarningFlash("Pegawai ".$peg->nama." dalam Status <b>".$peg->statusAktifPegawai->desc."</b>, sehingga tidak bisa Diperbaharui Kontraknya.");
                return $this->redirect(\Yii::$app->request->referrer);
            }

            $struktur_jabatan = StrukturJabatan::find()->where(['struktur_jabatan_id' => $oldModel->struktur_jabatan_id])->andWhere('deleted != 1')->all();
            $pegawai = Pegawai::find()->where(['pegawai_id' => $oldModel->pegawai_id])->andWhere('deleted != 1')->all();
            
            return $this->render('PejabatExtendKontrakAdd', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'pegawai' => $pegawai,
            ]);
        }
    }

    public function actionPejabatEdit($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if(isset($_FILES['Pejabat']['name'])){
                $result = \Yii::$app->fileManager->saveUploadedFiles();
                if($result->status == 'success'){
                    $model->kode_file = $result->fileinfo[0]->id;
                    $model->file_sk = $result->fileinfo[0]->name;
                }else{ 
                    $model->file_sk = $oldModel->file_sk;
                }
            }
            else{
                $model->file_sk = $oldModel->file_sk;
            }
            if(!$this->_isMasaKerjaSamePeriod($oldModel, $model)){
                \Yii::$app->messenger->addWarningFlash("Perubahan Tanggal Masa Kerja pada sebuah kontrak hanya bisa dilakukan dalam 1 Periode yang sama.");
                return $this->redirect(\Yii::$app->request->referrer);
            }
            if($model->save())
                return $this->redirect(['pejabat-view', 'id' => $model->pejabat_id]);
        } else {
            //jabatan
            $struktur_jabatan = StrukturJabatan::find()->where('deleted != 1')->orderBy(['instansi_id' => SORT_ASC, 'jabatan' => SORT_ASC])->All();
            //pegawai yg aktif
            $pegawai = Pegawai::find()->where(['in', 'status_aktif_pegawai_id', [1,2]])->andWhere('deleted != 1')->orderBy(['nama' => SORT_ASC])->All();
            return $this->render('PejabatEdit', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'pegawai' => $pegawai,
            ]);
        }
    }

    public function _isMasaKerjaSamePeriod($oldModel, $model)
    {
        return $this->_masaKerjaPeriod($oldModel)==$this->_masaKerjaPeriod($model);
    }

    public function _masaKerjaPeriod($model)
    {
        if($model->awal_masa_kerja < date('Y-m-d') && $model->akhir_masa_kerja <= date('Y-m-d'))
            return 0;
        else if($model->awal_masa_kerja <= date('Y-m-d') && $model->akhir_masa_kerja > date('Y-m-d'))
            return 1;
        else if($model->awal_masa_kerja > date('Y-m-d') && $model->akhir_masa_kerja > date('Y-m-d'))
            return 2;
        else{
            die;
        }
    }

    public function actionPejabatStatusNonaktifEdit($id, $renderer=0, $confirm=false)
    {
        if($confirm){
            $model = $this->findModel($id);
            if($model->akhir_masa_kerja > date('Y-m-d'))
                $model->akhir_masa_kerja = date('Y-m-d');
            $model->status_aktif = 0;
            if($model->save()){
                \Yii::$app->messenger->addInfoFlash("Pejabat <b>".$model->pegawai->nama."</b> untuk Jabatan <b>".$model->strukturJabatan->jabatan."</b> berhasil Dinonaktifkan.");
                if($renderer==2)
                    return $this->redirect(['pejabat/pejabat-by-jabatan-view', 'jabatan_id' => $model->struktur_jabatan_id]);
                else if($renderer==1)
                    return $this->redirect(['pejabat/pejabat-by-pegawai-view', 'pegawai_id' => $model->pegawai_id]);
                else
                    return $this->redirect(['pejabat/pejabat-view', 'id' => $model->pejabat_id]);
            }
            else {
                \Yii::$app->messenger->addWarningFlash("Pejabat <b>".$model->pegawai->nama."</b> untuk Jabatan <b>".$model->strukturJabatan->jabatan."</b> tidak berhasil Dinonaktifkan.");
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }
        $p = Pejabat::findOne(['pejabat_id' => $id, 'deleted' => 0]);
        return $this->render('confirmInactivate', ['id' => $id, 'model' => $p, 'renderer' => $renderer]);
    }

    public function actionPejabatStatusAktifEdit($id)
    {
        $model = $this->findModel($id);
        $model->status_aktif = 1;
        if($model->save()){
            \Yii::$app->messenger->addInfoFlash("Pejabat <b>".$model->pegawai->nama."</b> untuk Jabatan <b>".$model->strukturJabatan->jabatan."</b> berhasil Diaktifkan.");
        }
        else {
            \Yii::$app->messenger->addWarningFlash("Pejabat <b>".$model->pegawai->nama."</b> untuk Jabatan <b>".$model->strukturJabatan->jabatan."</b> tidak berhasil Diaktifkan.");
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }
    
    public function actionPejabatDel($id)
    {
        $_pejabat = Pejabat::find()
            ->where(['pejabat_id'=>$id])
            ->andWhere(['not', ['deleted' => 1]])
            ->one();
        if($_pejabat->softDelete())
        {
            //hapus file dari puro dan dari database
            //\Yii::$app->fileManager->delete($_complaint->image);
            \Yii::$app->messenger->addInfoFlash("Pejabat <b>".$_pejabat->pejabat_id."</b> berhasil di hapus");
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = Pejabat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

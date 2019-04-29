<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use backend\modules\hrdx\models\SuratTugas;
use backend\modules\hrdx\models\search\SuratTugasSearch;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\PenerimaTugas;
use backend\modules\hrdx\models\FasilitasPerjalanan;
use backend\modules\hrdx\models\JenisFasilitas;
use backend\modules\hrdx\models\StrukturJabatan;
use backend\modules\hrdx\models\PegawaiStrukturJabatan;

use backend\modules\admin\models\UserHasRole;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;

/**
 * SuratTugasController implements the CRUD actions for SuratTugas model.
 */
class SuratTugasController extends Controller
{
    public function behaviors()
    {
        return [
            'privilege' => [
                'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                'skipActions' => ['*'],
            ],
        ];
    }

    /**
     * Lists all SuratTugas models.
     * @return mixed
     */
    public function actionBrowse()
    {
        $user_id=Yii::$app->user->id;

        $searchModel = new SuratTugasSearch();

        $user_role=UserHasRole::find()
        ->joinWith('role')
        ->where("(sysx_role.name LIKE 'hrd' OR sysx_role.name LIKE 'wr2') AND sysx_user_has_role.user_id=".$user_id)
        ->one();

        $pegawai=Pegawai::find()
        ->where(['user_id'=>$user_id])
        ->one();

        if(isset($pegawai)){
            $searchModel['perequest']=$pegawai->pegawai_id;
        }else{
            $searchModel['perequest']=0;
        }
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Yii::$app->debugger->print_array($searchModel->attributes);

        return $this->render('browse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_role'=> $user_role,
            'pegawai' => $pegawai,
        ]);
    }

    public function actionAntrianSuratTugas()
    {
        $searchModel = new SuratTugasSearch();

        $searchModel['status']=0;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Yii::$app->debugger->print_array(Yii::$app->request->queryParams);

        return $this->render('antrian_surat_tugas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSuratTugasDiterima()
    {
        $searchModel = new SuratTugasSearch();

        $searchModel['status']=1;
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Yii::$app->debugger->print_array(Yii::$app->request->queryParams);

        return $this->render('surat_tugas_diterima', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetailAntrian($id)
    {
        $user_id=Yii::$app->user->id;

        $user_role=UserHasRole::find()
        ->joinWith('role')
        ->where("(sysx_role.name LIKE 'hrd' OR sysx_role.name LIKE 'wr2') AND sysx_user_has_role.user_id=".$user_id)
        ->one();

        $model = $this->findModel($id);

        $penerima_tugas=PenerimaTugas::find()
        ->where('surat_tugas_id='.$id)
        ->all();

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('struktur_jabatan_id='.$model->pemberi_tugas)
        ->one();

        $dataProvider = new ActiveDataProvider([
            'query' => FasilitasPerjalanan::find()->where('surat_tugas_id='.$id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail_antrian', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pemberi_tugas' => $pemberi_tugas,
            'penerima_tugas' => $penerima_tugas,
            'user_role' => $user_role,
        ]);
    }

    /**
     * Displays a single SuratTugas model.
     * @param integer $id
     * @return mixed
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);

        $penerima_tugas=PenerimaTugas::find()
        ->where('surat_tugas_id='.$id)
        ->all();

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('struktur_jabatan_id='.$model->pemberi_tugas)
        ->one();

        $dataProvider = new ActiveDataProvider([
            'query' => FasilitasPerjalanan::find()->where('surat_tugas_id='.$id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pemberi_tugas' => $pemberi_tugas,
            'penerima_tugas' => $penerima_tugas,
        ]);
    }

    /**
     * Creates a new SuratTugas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new SuratTugas();

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('aktif=0')
        ->all();

        $penerima_tugas_1= [];

        $penerima_tugas_2= Pegawai::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if(!isset($_POST['penerima_tugas'])){
                Yii::$app->messenger->addWarningFlash("Isi data dengan benar.");
                return $this->redirect(['add']);
            }

            $penerima_tugas_post=$_POST['penerima_tugas'];
            $now_date = date('Y-m-d h:i:s a', time());
            $model->updated_at=$now_date;
            if ($model->save()) {
                
                foreach ($penerima_tugas_post as $key => $value) {
                    $penerima_tugas= new PenerimaTugas;
                    $penerima_tugas->surat_tugas_id=$model->surat_tugas_id;
                    $penerima_tugas->pegawai_id=$value;
                    $penerima_tugas->save();
                }

                return $this->redirect(['detail', 'id' => $model->surat_tugas_id]);
            }
            
        } else {
            return $this->render('add', [
                'model' => $model,
                'pemberi_tugas' => $pemberi_tugas,
                'penerima_tugas_1' =>$penerima_tugas_1,
                'penerima_tugas_2' =>$penerima_tugas_2,
            ]);
        }
    }

    /**
     * Updates an existing SuratTugas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        // Jika surat tugas sudah di-approve
        if(isset($model)){
            if ($model->status!=0) {
                Yii::$app->messenger->addWarningFlash("Surat tugas sudah diterima dan tidak bisa diperbaiki lagi.");
                return $this->redirect(['detail', 'id' => $model->surat_tugas_id]);
            }
        }

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('aktif=0')
        ->all();

        $penerima_tugas_1= Pegawai::find()
        ->where("EXISTS (SELECT pegawai_id FROM hrdx_penerima_tugas WHERE hrdx_pegawai.pegawai_id=hrdx_penerima_tugas.pegawai_id AND surat_tugas_id=".$id.") AND deleted=0")
        ->all();

        $penerima_tugas_2= Pegawai::find()
        ->where("NOT EXISTS (SELECT pegawai_id FROM hrdx_penerima_tugas WHERE hrdx_pegawai.pegawai_id=hrdx_penerima_tugas.pegawai_id AND surat_tugas_id=".$id.") AND deleted=0")
        ->all();

        if ($model->load(Yii::$app->request->post())) {
            $penerima_tugas_post=$_POST['penerima_tugas'];

            $user_id=Yii::$app->user->id;

            $pegawai=Pegawai::find()
            ->where(['user_id'=>$user_id])
            ->one();

            $model->perequest=$pegawai->pegawai_id;

            if ($model->save()) {
                $penerima_tugas_deletd=PenerimaTugas::deleteAll('surat_tugas_id='.$id);
                foreach ($penerima_tugas_post as $key => $value) {
                    $penerima_tugas= new PenerimaTugas;
                    $penerima_tugas->surat_tugas_id=$model->surat_tugas_id;
                    $penerima_tugas->pegawai_id=$value;
                    $penerima_tugas->save();
                }
                return $this->redirect(['detail', 'id' => $model->surat_tugas_id]);
            }
            
        }  else {
            return $this->render('edit', [
                'model' => $model,
                'pemberi_tugas' => $pemberi_tugas,
                'penerima_tugas_1' => $penerima_tugas_1,
                'penerima_tugas_2' => $penerima_tugas_2,
            ]);
        }
    }

    /**
     * Deletes an existing SuratTugas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['browse']);
    }

    public function actionAturFasilitasPerjalanan($id)
    {
        $model = $this->findModel($id);

        // Jika surat tugas sudah di-approve
        if(isset($model)){
            if ($model->status!=0) {
                Yii::$app->messenger->addWarningFlash("Fasilitas sudah tidak dapat diperbaharui.");
                return $this->redirect(['detail-antrian', 'id' => $model->surat_tugas_id]);
            }
        }

        $penerima_tugas=PenerimaTugas::find()
        ->where('surat_tugas_id='.$id)
        ->all();

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('struktur_jabatan_id='.$model->pemberi_tugas)
        ->one();

        $dataProvider = new ActiveDataProvider([
            'query' => FasilitasPerjalanan::find()->where('surat_tugas_id='.$id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $fasilitas_1= FasilitasPerjalanan::find()
        ->join('right join', 'hrdx_r_jenis_fasilitas', 'hrdx_r_jenis_fasilitas.jenis_fasilitas_id=hrdx_fasilitas_perjalanan.jenis_fasilitas_id')
        ->where('surat_tugas_id='.$id)
        ->all();

        $fasilitas_2= JenisFasilitas::find()
        //->join('right join', 'hrdx_r_jenis_fasilitas', 'hrdx_r_jenis_fasilitas.jenis_fasilitas_id=hrdx_fasilitas_perjalanan.jenis_fasilitas_id')
        ->where('NOT EXISTS (SELECT jenis_fasilitas_id FROM hrdx_fasilitas_perjalanan WHERE hrdx_r_jenis_fasilitas.jenis_fasilitas_id=hrdx_fasilitas_perjalanan.jenis_fasilitas_id AND surat_tugas_id='.$id.')')
        ->all();

        if(isset($_POST['jenis_fasilitas'])){
            $empty=true;

            $fasilitas_perjalanan=FasilitasPerjalanan::deleteAll('surat_tugas_id='.$id);

            foreach ($_POST['jenis_fasilitas'] as $key => $value) {
                if($value!=''){
                    $empty=false;
                    $fasilitas_perjalanan= new FasilitasPerjalanan;
                    $fasilitas_perjalanan->surat_tugas_id=$id;
                    $fasilitas_perjalanan->jenis_fasilitas_id=$key;
                    $fasilitas_perjalanan->keterangan=$value;
                    $fasilitas_perjalanan->save();
                }
            }

            if($empty==false){
                Yii::$app->messenger->addSuccessFlash("Fasilitas berhasil ditambahkan.");
                return $this->redirect(['detail-antrian', 'id' => $id]);
            }

            Yii::$app->messenger->addWarningFlash("Isi fasilitas yang akan diberikan.");
            
        }

        return $this->render('fasilitas', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pemberi_tugas' => $pemberi_tugas,
            'penerima_tugas' => $penerima_tugas,
            'fasilitas_1' => $fasilitas_1,
            'fasilitas_2' => $fasilitas_2,
        ]);
    }

    public function actionCetakSuratTugas($id)
    {
        $model=$this->findModel($id);

        // Jika surat tugas belum di-approve
        if(isset($model)){
            if ($model->status==0) {
                Yii::$app->messenger->addWarningFlash("Surat tugas belum/ tidak dapat dicetak.");
                return $this->redirect(['detail-antrian', 'id' => $model->surat_tugas_id]);
            }
        }

        $pemberi_tugas=PegawaiStrukturJabatan::find()
        ->where('struktur_jabatan_id='.$model->pemberi_tugas)
        ->one();

        $penerima_tugas=PenerimaTugas::find()
        ->where('surat_tugas_id='.$id)
        ->all();

        $fasilitas_transportasi=FasilitasPerjalanan::find()
        ->where('surat_tugas_id='.$id.' AND jenis_fasilitas_id=1')
        ->one();

        $fasilitas=FasilitasPerjalanan::find()
        ->where('surat_tugas_id='.$id)
        ->all();

        $content= $this->renderAjax('cetak_surat_tugas', [
            'model' => $model,
            'pemberi_tugas' => $pemberi_tugas,
            'penerima_tugas' => $penerima_tugas,
            'fasilitas_transportasi' => $fasilitas_transportasi,
            'fasilitas' => $fasilitas,
        ]);

            //return $content;
            
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4'
        ]);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit;
    }

    public function actionTerimaSuratTugas($id)
    {
        $model=$this->findModel($id);
        $model->status=1;
        $model->save();
        Yii::$app->messenger->addSuccessFlash("Surat tugas sudah diterima.");
        return $this->redirect(['antrian-surat-tugas']);
    }

    public function actionUnggahLaporan($id)
    {
        $model=$this->findModel($id);

        // Jika surat tugas belum di-approve
        if(isset($model)){
            if ($model->status==0) {
                Yii::$app->messenger->addWarningFlash("Surat tugas belum diterima.");
                return $this->redirect(['detail', 'id' => $id]);
            }
        }

        // Jika surat tugas sudah di-approve
        if (Yii::$app->request->isPost){
            //\Yii::$app->debugger->print_array($_FILES);
            if($_FILES['files']['name'][0] != null){
                 $status = \Yii::$app->fileManager->saveUploadedFiles();
                 if($status->status == 'success'){
                    $model->nama_file = $status->fileinfo[0]->name;
                    $model->kode_file = $status->fileinfo[0]->id;
                    $model->save();
                    return $this->redirect(['detail', 'id' => $id]);
                 }
            }
        }

        return $this->render('unggah_laporan',[
            'model'=>$model,
        ]);
    }

    public function actionHapusLaporan($id)
    {
        $model=$this->findModel($id);
        if(isset($model->kode_file)){
              Yii::$app->fileManager->delete($model->kode_file);
              $model->nama_file = NULL;
            $model->kode_file = NULL;
            $model->save();
        }
        return $this->redirect(['detail', 'id' => $id]);
    }

    /**
     * Finds the SuratTugas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SuratTugas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SuratTugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

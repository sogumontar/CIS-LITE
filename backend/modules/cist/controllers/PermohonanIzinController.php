<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\PermohonanIzin;
use backend\modules\cist\models\search\PermohonanIzinSearch;
use backend\modules\cist\models\AtasanIzin;
use backend\modules\cist\models\search\AtasanIzinSearch;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\StatusIzin;
use backend\modules\cist\models\pegawai_id;
use backend\modules\inst\models\InstApiModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;


/**
 * PermohonanIzinController implements the CRUD actions for PermohonanIzin model.
 * controller-id: permohonan-izin
 * controller-desc: Controller untuk me-manage data Permohonan Izin
 */
class PermohonanIzinController extends Controller
{
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

    /**
     * action-id: index-by-staf
     * action-desc: Display index of izin for staf
     * */
    public function actionIndexByStaf(){
        $searchModel = new PermohonanIzinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $dataProvider->query->andWhere(['hrdx_pegawai.pegawai_id' => $pegawai->pegawai_id])->all();

        return $this->render('indexByStaf',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-hrd
     * action-desc: Display index of izin for HRD
     * */
    public function actionIndexByHrd(){
        $searchModel = new PermohonanIzinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByHrd',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-atasan
     * action-desc: Display index of izin for supervisor
     * */
    public function actionIndexByAtasan(){
        $atasanSearchModel = new AtasanIzinSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $atasanSearchModel->pegawai_id = $pegawai['pegawai_id'];

        $dataProvider = $atasanSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByAtasan',[
            'searchModel' => $atasanSearchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-wr2
     * action-desc: Display index of izin for vice rector 2
     * */
    public function actionIndexByWr2(){
        $searchModel = new PermohonanIzinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByWr2',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: view-by-staf
     * action-desc: Display detail view of izin for staf
     * */
    public function actionViewByStaf($id){
        return $this->render('viewByStaf',[
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-hrd
     * action-desc: Display detail view of izin for HRD
     * */
    public function actionViewByHrd($id){
        return $this->render('viewByHrd',[
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-hrd-staf
     * action-desc: Display detail view of izin for HRD-staf
     * */
    public function actionViewByHrdStaf($id){
        return $this->render('viewByHrdStaf',[
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-atasan
     * action-desc: Display detail view of izin for supervisor
     * */
    public function actionViewByAtasan($id){
        $atasanSearchModel = new AtasanIzinSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $atasanSearchModel->pegawai_id = $pegawai['pegawai_id'];

        $model = $atasanSearchModel->search(Yii::$app->request->queryParams)->query->where(['cist_atasan_izin.permohonan_izin_id' => $id])->one();

        return $this->render('viewByAtasan',[
            'model' => $model,
        ]);
    }

    /**
     * action-id: view-by-wr2
     * action-desc: Display detail view of izin for Vice Rector 2
     * */
    public function actionViewByWr2($id){
        return $this->render('viewByWr2',[
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Add a new PermohonanIzin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddByStaf()
    {
        $model = new PermohonanIzin();
        $inst_api = new InstApiModel();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $jabatanByPegawai = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);

        $model_atasan = array();
        foreach($jabatanByPegawai as $data){
            $model_atasan[] = $inst_api->getAtasanByJabatan($data->struktur_jabatan_id);
        }

        $atasan_nama = array();
        foreach($model_atasan as $data) {
            if($data != NULL)
                $atasan_nama[] = $inst_api->getPejabatByJabatan($data->struktur_jabatan_id);
        }

        $atasan = array();
        foreach($atasan_nama as $data) {
            foreach($data as $dat){
                $atasan[] =  $dat['pegawai_id'];
            }
        }

        $namaAtasan = array();
        foreach($atasan as $data){
            $namaAtasan[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post())) {
            // $kategori = $model->kategori_id;
            $model->pegawai_id = $pegawai['pegawai_id'];

            //Handling File
            // if($kategori == 1) {
            //     $model->file = UploadedFile::getInstance($model, 'file');
            //     if ($model->file != null) {
            //         $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
            //         $model->file_surat = 'uploads/' . $model->file->baseName . '.' . $model->file->extension;
            //     }
            //     else if ($model->file == null) {
            //         \Yii::$app->messenger->addErrorFlash("Harap melampirkan surat keterangan sakit!");
            //         return $this->render('addByStaf', [
            //             'model' => $model,
            //             'namaAtasan' => $namaAtasan,
            //         ]);
            //     }
            // }

            if ($model->atasan_id == null) {
               \Yii::$app->messenger->addErrorFlash("Harap memilih atasan anda!");
               return $this->redirect(\Yii::$app->request->referrer);
            }

            $result = \Yii::$app->fileManager->saveUploadedFiles();
            if(isset($result)){
                if($result->status == 'success'){
                    $model->kode_file_surat = $result->fileinfo[0]->id;
                    $model->file_surat = $result->fileinfo[0]->name;
                }
            }

            // Handling Status
            $modelStatus = new StatusIzin();
            if ($modelStatus->save()) {
                $model->status_izin_id = $modelStatus->status_izin_id;
                if ($model->save()) {
                    $modelStatus->permohonan_izin_id = $model->permohonan_izin_id;
                    $modelStatus->save();
                    //Handling Atasan
                    if($model->atasan_id != NULL){
                        foreach($model->atasan_id as $data){
                            $atasan_model = new AtasanIzin();
                            $atasan_model->permohonan_izin_id = $model->permohonan_izin_id;
                            $atasan_model->pegawai_id = $data;
                            //$atasan_model->nama = $data;
                            $atasan_model->save();
                        }
                    }
                    return $this->redirect(['view-by-staf', 'id' => $model->permohonan_izin_id]);
                } else {
                    return $this->redirect(\Yii::$app->request->referrer);
                }
            }
        } else {
            return $this->render('addByStaf', [
                'model' => $model,
                'namaAtasan' => $namaAtasan,
            ]);
        }
    }

    /**
     * action-id: accept-by-hrd
     * action-desc: Accepting the izin by HRD
     * */
    public function actionAcceptByHrd($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_hrd = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-accept oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: accept-by-atasan
     * action-desc: Accepting the izin by Supervisor
     * */
    public function actionAcceptByAtasan($id, $redback=null){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 6;
            $m->status_by_wr2 = 1;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-accept oleh Atasan");
            if(is_null($redback))
                return $this->redirect(['index-by-atasan']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('viewByAtasan', [
                'model'=>$model
            ]);
        }
    }

    public function actionAcceptByAtasanHrd($id, $redback=null){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 6;
            $m->status_by_wr2 = 1;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-accept Atasan oleh HRD");
            if(is_null($redback))
                return $this->redirect(['index-by-hrd']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: accept-by-wr2
     * action-desc: Accepting the izin by Vice Rector2
     * */
    public function actionAcceptByWr2($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-accept oleh WR 2");
            return $this->redirect(['index-by-wr2']);
        } else {
            return $this->render('viewByWr2', [
                'model'=>$model
            ]);
        }
    }

    public function actionAcceptByWr2Hrd($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-accept WR2 oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: reject-by-hrd
     * action-desc: Rejecting the izin by HRD
     * */
    public function actionRejectByHrd($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_hrd = 4;
            $m->status_by_atasan = 4;
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: reject-by-atasan
     * action-desc: Rejecting the izin by Supervisor
     * */
    public function actionRejectByAtasan($id, $redback=null){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 4;
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-reject oleh Atasan");
            if(is_null($redback))
                return $this->redirect(['index-by-atasan']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('viewByAtasan', [
                'model'=>$model
            ]);
        }
    }

    public function actionRejectByAtasanHrd($id, $redback=null){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 4;
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Izin telah di-reject Atasan oleh HRD");
            if(is_null($redback))
                return $this->redirect(['index-by-hrd']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: reject-by-wr2
     * action-desc: Rejecting the izin by Vice Rector2
     * */
    public function actionRejectByWr2($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject oleh WR 2");
            return $this->redirect(['index-by-wr2']);
        } else {
            return $this->render('viewByWr2', [
                'model'=>$model
            ]);
        }
    }

    public function actionRejectByWr2Hrd($id){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject WR2 oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: cancel-by-staf
     * action-desc: Rejecting the izin by HRD
     * */
    public function actionCancelByStaf($id, $confirm=false){
        $model = StatusIzin::find()->where(['permohonan_izin_id' => $id])->all();

        if ($confirm) {
            foreach ($model as $m) {
                $m->status_by_hrd = 5;
                $m->status_by_atasan = 5;
                $m->status_by_wr2 = 5;
            }
            if($m->save()){
                \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah dibatalkan");
                return $this->redirect(['index-by-staf']);
            }
        }

        return $this->render('confirmCancel', ['id' => $id]);
    }

    /**
     * action-id: download
     * action-desc: Download surat
     * */
    public function actionDownload($id){
        $model = $this->findModel($id);
        $file = $model->file_surat;
        $path = Yii::getAlias('@webroot').'/'.$file;
        if(file_exists($path)){
            Yii::$app->response->sendFile($path);
        }else{
            $this->render('download404');
        }
    }

    /**
     * action-id: edit
     * action-desc: Edit izin
     * */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->permohonan_izin_id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * action-id: del
     * action-desc: Deleting izin
     * */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermohonanIzin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermohonanIzin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermohonanIzin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

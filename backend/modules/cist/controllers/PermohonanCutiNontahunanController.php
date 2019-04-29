<?php

namespace backend\modules\cist\controllers;

use backend\modules\cist\models\KategoriCutiNontahunan;
use backend\modules\cist\models\StatusCutiNontahunan;
use Yii;
use backend\modules\cist\models\PermohonanCutiNontahunan;
use backend\modules\cist\models\search\PermohonanCutiNontahunanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\inst\models\InstApiModel;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\AtasanCutiNontahunan;
use backend\modules\cist\models\search\AtasanCutiNontahunanSearch;

/**
 * PermohonanCutiNontahunanController implements the CRUD actions for PermohonanCutiNontahunan model.
    * controller-id: permohonan-cuti-nontahunan
 * controller-desc: Controller untuk me-manage data Permohonan Cuti Non-Tahunan
 */
class PermohonanCutiNontahunanController extends Controller
{
    public function behaviors()
    {
        return [
            // TODO: crud controller actions are bypassed by default, set the appropriate privilege
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
     * Lists all PermohonanCutiNontahunan models.
     * @return mixed
     */

    /**
     * action-id: index
     * action-desc: Display index of cuti nontahunan by default
     * */
    public function actionIndex()
    {
        $searchModel = new PermohonanCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-staf
     * action-desc: Display index of cuti nontahunan for staf
     * */
    public function actionIndexByStaf(){
        $searchModel = new PermohonanCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $dataProvider->query->andWhere(['cist_permohonan_cuti_nontahunan.pegawai_id' => $pegawai->pegawai_id])->all();

        return $this->render('indexByStaf',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-hrd
     * action-desc: Display index of cuti nontahunan for HRD
     * */
    public function actionIndexByHrd(){
        $searchModel = new PermohonanCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByHrd',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-atasan
     * action-desc: Display index of cuti nontahunan for supervisor
     * */
    public function actionIndexByAtasan(){
        $atasanSearchModel = new AtasanCutiNontahunanSearch();
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
     * action-desc: Display index of cuti nontahunan for vice rector 2
     * */
    public function actionIndexByWr2(){
        $searchModel = new PermohonanCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByWr2',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermohonanCutiNontahunan model.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: view
     * action-desc: Display detail view of cuti tahunan by default
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-staf
     * action-desc: Display detail view of cuti nontahunan for staf
     * */
    public function actionViewByStaf($id)
    {
        return $this->render('viewByStaf', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-hrd
     * action-desc: Display detail view of cuti nontahunan for HRD
     * */
    public function actionViewByHrd($id)
    {
        return $this->render('viewByHrd', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-atasan
     * action-desc: Display detail view of cuti nontahunan for supervisor
     * */
    public function actionViewByAtasan($id)
    {
        $atasanSearchModel = new AtasanCutiNontahunanSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $atasanSearchModel->pegawai_id = $pegawai['pegawai_id'];

        $model = $atasanSearchModel->search(Yii::$app->request->queryParams)->query->where(['cist_atasan_cuti_nontahunan.permohonan_cuti_nontahunan_id' => $id])->one();

        return $this->render('viewByAtasan',[
            'model' => $model,
        ]);
    }

    /**
     * action-id: view-by-wr2
     * action-desc: Display detail view of cuti tahunan for Vice Rector 2
     * */
    public function actionViewByWr2($id)
    {
        return $this->render('viewByWr2', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PermohonanCutiNontahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * action-id: add
     * action-desc: Adding new cuti nontahunan by default
     * */
    public function actionAdd()
    {
        $model = new PermohonanCutiNontahunan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * action-id: add-by-staf
     * action-desc: Adding new cuti nontahunan by staf
     * */
    public function actionAddByStaf(){
        $model = new PermohonanCutiNontahunan();
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->pegawai_id = $pegawai['pegawai_id'];
            $cuti = KategoriCutiNontahunan::find()->where(['kategori_cuti_nontahunan_id' => $model->kategori_id])->one();
            $model->lama_cuti = $cuti->lama_pelaksanaan;
            $modelStatus = new StatusCutiNontahunan();
            if ($model->atasan == null) {
               \Yii::$app->messenger->addErrorFlash("Harap memilih atasan anda!");
               return $this->redirect(\Yii::$app->request->referrer);
            }
            if ($model->save()) {
                //Handling Status
                $modelStatus->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
                if($modelStatus->save()){
                    $model->status_cuti_nontahunan_id = $modelStatus->status_cuti_nontahunan_id;
                    $model->save();

                    //Handling Atasan
                    if($model->atasan != NULL){
                        foreach($model->atasan as $data){
                            $atasan_model = new AtasanCutiNontahunan();
                            $atasan_model->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
                            $atasan_model->pegawai_id = $data;
                            //$atasan_model->nama = $data;
                            $atasan_model->save();
                        }
                    }
                    return $this->redirect(['view-by-staf', 'id' => $model->permohonan_cuti_nontahunan_id]);
                } else {
                    return $this->render('addByStaf', [
                        'model' => $model,
                        'namaPegawai' => $namaPegawai,
                    ]);
                }
            }
        } else {
            return $this->render('addByStaf', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: add-by-hrd
     * action-desc: Adding new cuti nontahunan by HRD
     * */
    public function actionAddByHrd(){
        $model = new PermohonanCutiNontahunan();
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->pegawai_id = $pegawai['pegawai_id'];
            $cuti = KategoriCutiNontahunan::find()->where(['kategori_cuti_nontahunan_id' => $model->kategori_id])->one();
            $model->lama_cuti = $cuti->lama_pelaksanaan;
            $model->save();

            //Handling Atasan
            if($model->atasan != NULL){
                foreach($model->atasan as $data){
                    $atasan_model = new AtasanCutiNontahunan();
                    $atasan_model->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
                    $atasan_model->pegawai_id = $data;
                    //$atasan_model->nama = $data;
                    $atasan_model->save();
                }
            }

            //Handling Status
            $modelStatus = new StatusCutiNontahunan();
            $modelStatus->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
            $modelStatus->status_by_hrd = 1;
            $modelStatus->status_by_atasan = 1;
            $modelStatus->status_by_wr2 = 1;
            $modelStatus->save();

            return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('addByHrd', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: add-by-atasan
     * action-desc: Adding new cuti nontahunan by Supervisor
     * */
    public function actionAddByAtasan(){
        $model = new PermohonanCutiNontahunan();
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->pegawai_id = $pegawai['pegawai_id'];
            $cuti = KategoriCutiNontahunan::find()->where(['kategori_cuti_nontahunan_id' => $model->kategori_id])->one();
            $model->lama_cuti = $cuti->lama_pelaksanaan;
            $model->save();

            //Handling Atasan
            if($model->atasan != NULL){
                foreach($model->atasan as $data){
                    $atasan_model = new AtasanCutiNontahunan();
                    $atasan_model->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
                    $atasan_model->pegawai_id = $data;
                    //$atasan_model->nama = $data;
                    $atasan_model->save();
                }
            }

            //Handling Status
            $modelStatus = new StatusCutiNontahunan();
            $modelStatus->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
            $modelStatus->status_by_hrd = 1;
            $modelStatus->status_by_atasan = 1;
            $modelStatus->status_by_wr2 = 1;
            $modelStatus->save();

            return $this->redirect(['view-by-atasan', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('addByAtasan', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: add-by-wr2
     * action-desc: Adding new cuti nontahunan by Vice Rector 2
     * */
    public function actionAddByWr2(){
        $model = new PermohonanCutiNontahunan();
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->pegawai_id = $pegawai['pegawai_id'];
            $cuti = KategoriCutiNontahunan::find()->where(['kategori_cuti_nontahunan_id' => $model->kategori_id])->one();
            $model->lama_cuti = $cuti->lama_pelaksanaan;
            $model->save();

            //Handling Atasan
            if($model->atasan != NULL){
                foreach($model->atasan as $data){
                    $atasan_model = new AtasanCutiNontahunan();
                    $atasan_model->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
                    $atasan_model->pegawai_id = $data;
                    //$atasan_model->nama = $data;
                    $atasan_model->save();
                }
            }

            //Handling Status
            $modelStatus = new StatusCutiNontahunan();
            $modelStatus->permohonan_cuti_nontahunan_id = $model->permohonan_cuti_nontahunan_id;
            $modelStatus->status_by_hrd = 1;
            $modelStatus->status_by_atasan = 1;
            $modelStatus->status_by_wr2 = 1;
            $modelStatus->save();

            return $this->redirect(['view-by-wr2', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('addByWr2', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: accept-by-hrd
     * action-desc: Accepting the Cuti by HRD
     * */
    public function actionAcceptByHrd($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_hrd = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: accept-by-atasan
     * action-desc: Accepting the Cuti by Supervisor
     * */
    public function actionAcceptByAtasan($id, $redback=null){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 6;
            $m->status_by_wr2 = 1;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh Atasan");
           if(is_null($redback))
                return $this->redirect(['index-by-atasan']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            \Yii::$app->messenger->addErrorFlash("Maaf, telah terjadi masalah pada sistem");
            return $this->redirect(['index-by-atasan']);
        }
    }
    
    public function actionAcceptByAtasanHrd($id, $redback=null){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 6;
            $m->status_by_wr2 = 1;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept Atasan oleh HRD");
           if(is_null($redback))
                return $this->redirect(['index-by-hrd']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            \Yii::$app->messenger->addErrorFlash("Maaf, telah terjadi masalah pada sistem");
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: accept-by-wr2
     * action-desc: Accepting the Cuti by Vice Rector2
     * */
    public function actionAcceptByWr2($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh WR 2");
            return $this->redirect(['index-by-wr2']);
        } else {
            return $this->redirect(['index-by-wr2']);
        }
    }

    public function actionAcceptByWr2Hrd($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 6;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept WR2 oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: reject-by-hrd
     * action-desc: Rejecting the Cuti by HRD
     * */
    public function actionRejectByHrd($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_hrd = 4;
            $m->status_by_atasan = 5;
            $m->status_by_wr2 = 5;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Cuti telah di-reject oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: reject-by-atasan
     * action-desc: Rejecting the Cuti by Supervisor
     * */
    public function actionRejectByAtasan($id, $redback=null){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 4;
            $m->status_by_wr2 = 5;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-reject oleh Atasan");
            if(is_null($redback))
                return $this->redirect(['index-by-atasan']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index-by-atasan']);
        }
    }

    public function actionRejectByAtasanHrd($id, $redback=null){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_atasan = 4;
            $m->status_by_wr2 = 5;
        }

        if($m->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-reject Atasan oleh HRD");
            if(is_null($redback))
                return $this->redirect(['index-by-hrd']);
            else
                return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: reject-by-wr2
     * action-desc: Rejecting the Cuti by Vice Rector2
     * */
    public function actionRejectByWr2($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Cuti telah di-reject oleh WR 2");
            return $this->redirect(['index-by-wr2']);
        } else {
            return $this->redirect(['index-by-wr2']);
        }
    }

    public function actionRejectByWr2Hrd($id){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        foreach ($model as $m) {
            $m->status_by_wr2 = 4;
        }

        if($m->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Cuti telah di-reject WR2 oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->redirect(['index-by-hrd']);
        }
    }

    /**
     * action-id: cancel-by-staf
     * action-desc: Cancel the izin by pemohon
     * */
    public function actionCancelByStaf($id, $confirm=false){
        $model = StatusCutiNontahunan::find()->where(['permohonan_cuti_nontahunan_id' => $id])->all();

        if ($confirm) {
            foreach ($model as $m) {
                $m->status_by_hrd = 5;
                $m->status_by_atasan = 5;
                $m->status_by_wr2 = 5;
            }
            if($m->save()){
                \Yii::$app->messenger->addErrorFlash("Permohonan cuti telah dibatalkan");
                return $this->redirect(['index-by-staf']);
            }
        }

        return $this->render('confirmCancel', ['id' => $id]);
    }

    /**
     * action-id: edit
     * action-desc: Edit cuti nontahunan by default
     * */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * action-id: edit-by-staf
     * action-desc: Edit cuti nontahunan by staf
     * */
    public function actionEditByStaf($id)
    {
        $model = $this->findModel($id);
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-by-staf', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('editByStaf', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: edit-by-hrd
     * action-desc: Edit cuti nontahunan by hrd
     * */
    public function actionEditByHrd($id)
    {
        $model = $this->findModel($id);
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('editByHrd', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: edit-by-atasan
     * action-desc: Edit cuti nontahunan by Supervisor
     * */
    public function actionEditByAtasan($id)
    {
        $model = $this->findModel($id);
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-by-atasan', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('editByAtasan', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * action-id: edit-by-wr2
     * action-desc: Edit cuti nontahunan by Vice Rector2
     * */
    public function actionEditByWr2($id)
    {
        $model = $this->findModel($id);
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

        $namaPegawai = array();
        foreach($atasan as $data){
            $namaPegawai[] = Pegawai::find()->where(['pegawai_id' => $data])->one();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-by-wr2', 'id' => $model->permohonan_cuti_nontahunan_id]);
        } else {
            return $this->render('editByWr2', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
            ]);
        }
    }

    /**
     * Deletes an existing PermohonanCutiNontahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: del
     * action-desc: Deleting cuti nontahunan
     * */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermohonanCutiNontahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermohonanCutiNontahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermohonanCutiNontahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * action-id: kategori-cuti
     * action-desc: Finding duration of cuti nontahunan by selecting from dropdown
     * */
    public function actionKategoriCuti($kategori_id){
        $kat = KategoriCutiNontahunan::find()
            ->where('deleted!=1')
            ->andWhere(['kategori_cuti_nontahunan_id' => $kategori_id])
            ->one();

        return empty($kat)?null:json_encode(['lama_pelaksanaan' => $kat->lama_pelaksanaan, 'satuan' => $kat->satuan]);
    }
}

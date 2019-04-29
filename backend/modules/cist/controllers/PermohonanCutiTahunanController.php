<?php

namespace backend\modules\cist\controllers;
use backend\modules\cist\models\StatusCutiTahunan;
use Yii;
use backend\modules\cist\models\AtasanCutiTahunan;
use backend\modules\cist\models\PermohonanCutiTahunan;
use backend\modules\cist\models\search\PermohonanCutiTahunanSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\cist\models\KuotaCutiTahunan;
use backend\modules\inst\models\InstApiModel;
use backend\modules\cist\models\Pegawai;
use yii\helpers\ArrayHelper;
use backend\modules\cist\models\search\AtasanCutiTahunanSearch;
/**
 * PermohonanCutiTahunanController implements the CRUD actions for PermohonanCutiTahunan model.
   * controller-id: permohonan-cuti-tahunan
 * controller-desc: Controller untuk me-manage data Permohonan Cuti Tahunan
 */
class PermohonanCutiTahunanController extends Controller
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
     * Lists all PermohonanCutiTahunan models.
     * @return mixed
     */

    /**
     * action-id: index
     * action-desc: Display index of cuti tahunan by default
     * */
    public function actionIndex()
    {
        $searchModel = new PermohonanCutiTahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-staf
     * action-desc: Display index of cuti tahunan for staf
     * */
    public function actionIndexByStaf()
    {
        $searchModel = new PermohonanCutiTahunanSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['cist_permohonan_cuti_tahunan.pegawai_id' => $pegawai->pegawai_id])->all();

        $year = date('Y');
        $modelKuota = PermohonanCutiTahunan::find()->joinWith(['statusCutiTahunan'])->where(['pegawai_id' => $pegawai->pegawai_id])
        ->andWhere(['cist_status_cuti_tahunan.status_by_wr2' => 6])
        ->andWhere(['like', 'cist_permohonan_cuti_tahunan.created_at', $year])->sum('lama_cuti');
        $kuotaCuti = KuotaCutiTahunan::find()->where(['pegawai_id' => $pegawai->pegawai_id])->one();
        $sisa_kuota = 0;
        if ($modelKuota == 0 || $modelKuota == null) {
            $sisa_kuota = empty($kuotaCuti)?0:$kuotaCuti->kuota;
        } else if ($modelKuota != 0 || $modelKuota != null){
            $sisa_kuota = $kuotaCuti->kuota - $modelKuota;
        }

        return $this->render('indexByStaf', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sisa_kuota' => $sisa_kuota
        ]);
    }

    /**
     * action-id: index-by-hrd
     * action-desc: Display index of cuti tahunan for HRD
     * */
    public function actionIndexByHrd()
    {
        $searchModel = new PermohonanCutiTahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByHrd', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-atasan
     * action-desc: Display index of cuti tahunan for supervisor
     * */
    public function actionIndexByAtasan()
    {
        $atasanSearchModel = new AtasanCutiTahunanSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $atasanSearchModel->pegawai_id = $pegawai['pegawai_id'];

        $dataProvider = $atasanSearchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByAtasan', [
            'searchModel' => $atasanSearchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: index-by-wr2
     * action-desc: Display index of cuti tahunan for vice rector 2
     * */
    public function actionIndexByWr2()
    {
        $searchModel = new PermohonanCutiTahunanSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indexByWr2', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermohonanCutiTahunan model.
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
     * action-desc: Display detail view of cuti tahunan for staf
     * */
    public function actionViewByStaf($id)
    {
        return $this->render('viewByStaf', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-hrd
     * action-desc: Display detail view of cuti tahunan for HRD
     * */
    public function actionViewByHrd($id)
    {
        return $this->render('viewByHrd', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * action-id: view-by-atasan
     * action-desc: Display detail view of cuti tahunan for supervisor
     * */
    public function actionViewByAtasan($id)
    {
        $atasanSearchModel = new AtasanCutiTahunanSearch();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $atasanSearchModel->pegawai_id = $pegawai['pegawai_id'];

        $model = $atasanSearchModel->search(Yii::$app->request->queryParams)->query->where(['cist_atasan_cuti_tahunan.permohonan_cuti_tahunan_id' => $id])->one();

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
     * Creates a new PermohonanCutiTahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * action-id: add
     * action-desc: Adding new cuti tahunan by default
     * */
    public function actionAdd()
    {
        $model = new PermohonanCutiTahunan();
        $kuota = new KuotaCutiTahunan();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['pegawai_id'=> $pegawai])->one();
        $kuotaa = $kuot['kuota'];
        if ($model->load(Yii::$app->request->post())) {
            $post =Yii::$app->request->post('PermohonanCutiTahunan');
            $lama = $post['lama_cuti'];
            if($lama>$kuot['kuota']){
                \Yii::$app->messenger->addErrorFlash("Kuota tidak mencukupi!");
                return $this->render('_form', [
                'model' => $model,
                ]);
            } else {
                //$kuota->kuota = $sisa;
                //$kuota->save();

                $model->pegawai_id = $pegawai['pegawai_id'];
                $model->save();

                // return $this->render('_form', [
                // 'model' => $model,
                // ]);
                return $this->redirect(['view', 'id' => $model->pmhnn_cuti_thn_id]);
            }
        } else {
            return $this->render('add', [
                'model' => $model,
                'kuota' => $kuotaa,
            ]);
        }
    }

    /**
     * action-id: add-by-staf
     * action-desc: Adding new cuti tahunan by staf
     * */
    public function actionAddByStaf()
    {
        $model = new PermohonanCutiTahunan();
        $inst_api = new InstApiModel();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $jabatanByPegawai = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
        $year = date('Y');
        $modelKuota = PermohonanCutiTahunan::find()->joinWith(['statusCutiTahunan'])->where(['pegawai_id' => $pegawai->pegawai_id])
        ->andWhere(['cist_status_cuti_tahunan.status_by_wr2' => 6])
        ->andWhere(['like', 'cist_permohonan_cuti_tahunan.created_at', $year])->sum('lama_cuti');
        $kuotaCuti = KuotaCutiTahunan::find()->where(['pegawai_id' => $pegawai->pegawai_id])->one();
        if ($modelKuota == 0 || $modelKuota == null) {
            $sisa_kuota = empty($kuotaCuti)?0:$kuotaCuti->kuota;
        } else if ($modelKuota != 0 || $modelKuota != null){
            $sisa_kuota = $kuotaCuti->kuota - $modelKuota;
        }


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
            $modelStatus = new StatusCutiTahunan();
            $model->pegawai_id = $pegawai['pegawai_id'];

            if ($model->atasan == null) {
               \Yii::$app->messenger->addErrorFlash("Harap memilih atasan anda!");
               return $this->redirect(\Yii::$app->request->referrer);
            }

            $lama = $model->lama_cuti;

            //Handling Kuota
            if($lama > $sisa_kuota){
                \Yii::$app->messenger->addErrorFlash("Kuota tidak mencukupi!");
                return $this->render('_formByStaf', [
                    'model' => $model,
                    'sisa_kuota' => $sisa_kuota,
                    'namaPegawai' => $namaPegawai,
                ]);
            }

            if ($model->save()) {
                // Handling status
                $modelStatus->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                if ($modelStatus->save()) {
                    $model->status_izin_id = $modelStatus->status_cuti_tahunan_id;
                    $model->save();
                }

                if ($modelKuota != 0 || $modelKuota != null){
                    $sisa_kuota = $sisa_kuota - $lama;
                }

                //Handling Atasan
                if($model->atasan != NULL){
                    foreach($model->atasan as $data){
                        $atasan_model = new AtasanCutiTahunan();
                        $atasan_model->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                        $atasan_model->pegawai_id = $data;
                        //$atasan_model->nama = $data;
                        $atasan_model->save();
                    }
                }

                return $this->redirect(['view-by-staf', 'id' => $model->permohonan_cuti_tahunan_id]);
            }
        } else {
            return $this->render('addByStaf', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
                'sisa_kuota' => $sisa_kuota,
            ]);
        }


    }

    /**
     * action-id: add-by-hrd
     * action-desc: Adding new cuti tahunan by HRD
     * */
    public function actionAddByHrd()
    {
        $model = new PermohonanCutiTahunan();
        $inst_api = new InstApiModel();
        $kuota = new KuotaCutiTahunan();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['pegawai_id'=> $pegawai])->one();
        $kuotaa = $kuot['kuota'];
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
            $post =Yii::$app->request->post('PermohonanCutiTahunan');
            $lama = $post['lama_cuti'];


            //Handling Kuota
            if($lama>$kuot['kuota']){
                \Yii::$app->messenger->addErrorFlash("Kuota tidak mencukupi!");
                return $this->render('_formByHrd', [
                    'model' => $model,
                    'kuota' => $kuotaa,
                    'namaPegawai' => $namaPegawai,
                ]);
            } else {
                //Handling Atasan
                if($model->atasan != NULL){
                    foreach($model->atasan as $data){
                        $atasan_model = new AtasanCutiTahunan();
                        $atasan_model->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                        $atasan_model->pegawai_id = $data;
                        //$atasan_model->nama = $data;
                        $atasan_model->save();
                    }
                }

                $model->pegawai_id = $pegawai['pegawai_id'];
                $model->save();

                //Handling Status
                $modelStatus = new StatusCutiTahunan();
                $modelStatus->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                $modelStatus->status_by_hrd = "Waiting";
                $modelStatus->status_by_atasan = "Waiting";
                $modelStatus->status_by_wr2 = "Waiting";
                $modelStatus->save();

                return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
            }
        } else {
            return $this->render('addByHrd', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
                'kuota' => $kuotaa,
            ]);
        }


    }

    /**
     * action-id: add-by-atasan
     * action-desc: Adding new cuti tahunan by Supervisor
     * */
    public function actionAddByAtasan()
    {
        $model = new PermohonanCutiTahunan();
        $inst_api = new InstApiModel();
        $kuota = new KuotaCutiTahunan();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['pegawai_id'=> $pegawai])->one();
        $kuotaa = $kuot['kuota'];
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
            $post =Yii::$app->request->post('PermohonanCutiTahunan');
            $lama = $post['lama_cuti'];


            //Handling Kuota
            if($lama>$kuot['kuota']){
                \Yii::$app->messenger->addErrorFlash("Kuota tidak mencukupi!");
                return $this->render('_formByAtasan', [
                    'model' => $model,
                    'kuota' => $kuotaa,
                    'namaPegawai' => $namaPegawai,
                ]);
            } else {
                //Handling Atasan
                if($model->atasan != NULL){
                    foreach($model->atasan as $data){
                        $atasan_model = new AtasanCutiTahunan();
                        $atasan_model->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                        $atasan_model->pegawai_id = $data;
                        //$atasan_model->nama = $data;
                        $atasan_model->save();
                    }
                }

                $model->pegawai_id = $pegawai['pegawai_id'];
                $model->save();

                //Handling Status
                $modelStatus = new StatusCutiTahunan();
                $modelStatus->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                $modelStatus->status_by_hrd = "Waiting";
                $modelStatus->status_by_atasan = "Waiting";
                $modelStatus->status_by_wr2 = "Waiting";
                $modelStatus->save();

                return $this->redirect(['view-by-atasan', 'id' => $model->permohonan_cuti_tahunan_id]);
            }
        } else {
            return $this->render('addByAtasan', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
                'kuota' => $kuotaa,
            ]);
        }


    }

    /**
     * action-id: add-by-wr2
     * action-desc: Adding new cuti tahunan by Vice Rector 2
     * */
    public function actionAddByWr2()
    {
        $model = new PermohonanCutiTahunan();
        $inst_api = new InstApiModel();
        $kuota = new KuotaCutiTahunan();

        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['pegawai_id'=> $pegawai])->one();
        $kuotaa = $kuot['kuota'];

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
            $post =Yii::$app->request->post('PermohonanCutiTahunan');
            $lama = $post['lama_cuti'];


            //Handling Kuota
            if($lama>$kuot['kuota']){
                \Yii::$app->messenger->addErrorFlash("Kuota tidak mencukupi!");
                return $this->render('_formByWr2', [
                    'model' => $model,
                    'kuota' => $kuotaa,
                    'namaPegawai' => $namaPegawai,
                ]);
            } else {
                //Handling Atasan
                if($model->atasan != NULL){
                    foreach($model->atasan as $data){
                        $atasan_model = new AtasanCutiTahunan();
                        $atasan_model->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                        $atasan_model->pegawai_id = $data;
                        //$atasan_model->nama = $data;
                        $atasan_model->save();
                    }
                }

                $model->pegawai_id = $pegawai['pegawai_id'];
                $model->save();

                //Handling Status
                $modelStatus = new StatusCutiTahunan();
                $modelStatus->permohonan_cuti_tahunan_id = $model->permohonan_cuti_tahunan_id;
                $modelStatus->status_by_hrd = "Waiting";
                $modelStatus->status_by_atasan = "Waiting";
                $modelStatus->status_by_wr2 = "Waiting";
                $modelStatus->save();

                return $this->redirect(['view-by-wr2', 'id' => $model->permohonan_cuti_tahunan_id]);
            }
        } else {
            return $this->render('addByWr2', [
                'model' => $model,
                'namaPegawai' => $namaPegawai,
                'kuota' => $kuotaa,
            ]);
        }


    }

    /**
     * action-id: accept-by-hrd
     * action-desc: Accepting the cuti tahunan by HRD
     * */
    public function actionAcceptByHrd($id){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_hrd = 6;

        if($modelStatus->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh HRD");
            return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: accept-by-atasan
     * action-desc: Accepting the cuti tahunan by Supervisor
     * */
    public function actionAcceptByAtasan($id, $redback=null){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_atasan = 6;

        if($modelStatus->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh Atasan");
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
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_atasan = 6;

        if($modelStatus->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept Atasan oleh HRD");
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
     * action-desc: Accepting the cuti tahunan by Vice Rector2
     * */
    public function actionAcceptByWr2($id){
        $kuota = new KuotaCutiTahunan();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['kuota_cuti_tahunan_id'=> $pegawai])->one();

        $model = $this->findModel($id);
        $lama = $model['lama_cuti'];

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_wr2 = 6;

        if($modelStatus->save()){
            $sisa = $kuot['kuota'] - $lama;

            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept oleh WR 2");
            return $this->redirect(['view-by-wr2', 'id' => $model->permohonan_cuti_tahunan_id]);
        } else {
            return $this->render('viewByWr2', [
                'model'=>$model
            ]);
        }
    }

    public function actionAcceptByWr2Hrd($id){
        $kuota = new KuotaCutiTahunan();
        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->user_id])->one();
        $kuot = $kuota->find()->where(['kuota_cuti_tahunan_id'=> $pegawai])->one();

        $model = $this->findModel($id);
        $lama = $model['lama_cuti'];

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_wr2 = 6;

        if($modelStatus->save()){
            $sisa = $kuot['kuota'] - $lama;

            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-accept WR2 oleh HRD");
            return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: reject-by-hrd
     * action-desc: Rejecting the cuti tahunan by HRD
     * */
     public function actionRejectByHrd($id){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_hrd = 4;
        $modelStatus->status_by_atasan = 5;
        $modelStatus->status_by_wr2 = 5;

        if($modelStatus->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject oleh HRD");
            return $this->redirect(['view-by-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * action-id: reject-by-atasan
     * action-desc: Rejecting the cuti tahunan by Supervisor
     * */
    public function actionRejectByAtasan($id, $redback=null){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_atasan = 4;
        $modelStatus->status_by_wr2 = 5;

        if($modelStatus->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-reject oleh Atasan");
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
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_atasan = 4;
        $modelStatus->status_by_wr2 = 5;

        if($modelStatus->save()){
            \Yii::$app->messenger->addSuccessFlash("Permohonan Cuti telah di-reject Atasan oleh HRD");
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
     * action-desc: Rejecting the cuti tahunan by Vice Rector2
     * */
    public function actionRejectByWr2($id){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_wr2 = 4;

        if($modelStatus->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject oleh WR 2");
            return $this->redirect(['view-by-wr2', 'id' => $model->permohonan_cuti_tahunan_id]);
        } else {
            return $this->render('viewByWr2', [
                'model'=>$model
            ]);
        }
    }

    public function actionRejectByWr2Hrd($id){
        $model = $this->findModel($id);

        $modelStatus = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $model['permohonan_cuti_tahunan_id'] ])->one();
        $modelStatus->status_by_wr2 = 4;

        if($modelStatus->save()){
            \Yii::$app->messenger->addErrorFlash("Permohonan Izin telah di-reject WR2 oleh HRD");
            return $this->redirect(['index-by-hrd']);
        } else {
            return $this->render('viewByHrd', [
                'model'=>$model
            ]);
        }
    }

    /**
     * Updates an existing PermohonanCutiTahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: edit
     * action-desc: Edit cuti tahunan
     * */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pmhnn_cuti_thn_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * action-id: cancel-by-staf
     * action-desc: Cancel the izin by pemohon
     * */
    public function actionCancelByStaf($id, $confirm=false){
        $model = StatusCutiTahunan::find()->where(['permohonan_cuti_tahunan_id' => $id])->all();

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
     * action-id: del
     * action-desc: Deleting cuti tahunan
     * */
    public function actionDel($id)
    {
        $this->findModel($id)->softdelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermohonanCutiTahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermohonanCutiTahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermohonanCutiTahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

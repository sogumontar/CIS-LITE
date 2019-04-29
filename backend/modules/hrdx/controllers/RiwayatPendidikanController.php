<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use backend\modules\hrdx\models\Jenjang;
use backend\modules\hrdx\models\RiwayatPendidikan;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\search\RiwayatPendidikanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\filters\VerbFilter;


/**
 * Needed for AJAX
 */
use yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * RiwayatPendidikanController implements the CRUD actions for RiwayatPendidikan model.
 */
class RiwayatPendidikanController extends Controller
{
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 // 'skipActions' => ['*'],
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
     * Lists all RiwayatPendidikan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RiwayatPendidikanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RiwayatPendidikan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RiwayatPendidikan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id (pegawai_id)
     * @return mixed
     */
    public function actionAdd($id)
    {
        $model = new RiwayatPendidikan();
        $jenjang = Jenjang::find()->all();

        $modelPegawai = Pegawai::find()->with('dosen','staf')->where(['pegawai_id'=>$id, 'deleted'=>0])->one();
        
        //AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->pegawai_id = $id;
            $model->save();
            \Yii::$app->messenger->addSuccessFlash("Data Riwayat Pendidikan berhasil ditambahkan");
            if($modelPegawai->staf != NULL){
                return $this->redirect(Url::toRoute(['staf/view', 'id'=>$modelPegawai->staf->staf_id]));
            }elseif($modelPegawai->dosen != NULL){
                return $this->redirect(Url::toRoute(['dosen/view', 'id'=>$modelPegawai->dosen->dosen_id]));
            }
        } else {
            return $this->render('add', [
                'model' => $model,
                'modelPegawai'=>$modelPegawai,
                'jenjang'=>$jenjang,
            ]);
        }
    }

    /**
     * Updates an existing RiwayatPendidikan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id (riwayat_pendidikan_id)
     * @return mixed
     */
    public function actionEdit($id)
    {
        $user_login_id = Yii::$app->user->id;
        Yii::$app->privilegeControl->requiredPermission('edit-riwayat-pendidikan');
        $model = $this->findModel($id);
        $model= RiwayatPendidikan::find()->with('pegawai')->where(['riwayat_pendidikan_id'=>$id, 'deleted'=>0])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->messenger->addSuccessFlash("Riwayat Pendidikan sudah di ubah");
            if($model->pegawai->staf != NULL){
                return $this->redirect(Url::toRoute(['staf/view', 'id'=>$model->pegawai->staf->staf_id]));
            }elseif($model->pegawai->dosen != NULL){
                return $this->redirect(Url::toRoute(['dosen/view', 'id'=>$model->pegawai->dosen->dosen_id]));
            }
        } else {
            if($this->_userLoginCheck($id) || Yii::$app->privilegeControl->isHasWorkgroup('hrd')){
                return $this->render('edit', [
                    'model' => $model,
                ]);
            }
            else{
                \Yii::$app->messenger->addErrorFlash("Anda tidak boleh mengedit data yang bukan milik anda !!!");
                return $this->redirect(Url::toRoute(['pegawai/index']));                    
            }
        }
    }

    /**
     * Deletes an existing RiwayatPendidikan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id (riwayat_pendidikan_id)
     * @return mixed
     */
    public function actionDel($id)
    {
        Yii::$app->privilegeControl->requiredPermission('delete-riwayat-pendidikan');
        $model= RiwayatPendidikan::find()->with('pegawai')->where(['riwayat_pendidikan_id'=>$id, 'deleted'=>0])->one();
        $this->findModel($id)->forceDelete();

        \Yii::$app->messenger->addWarningFlash("Data Riwayat Pendidikan telah dihapus");
        if($model->pegawai->staf != NULL){
            return $this->redirect(Url::toRoute(['staf/view', 'id'=>$model->pegawai->staf->staf_id]));
        }elseif($model->pegawai->dosen != NULL){
            return $this->redirect(Url::toRoute(['dosen/view', 'id'=>$model->pegawai->dosen->dosen_id]));
        }
    }

    /**
     * Finds the RiwayatPendidikan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RiwayatPendidikan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RiwayatPendidikan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    static function _userLoginCheck($riwayat_id){
        $user_login_id = Yii::$app->user->id;
        $modelPegawai = Pegawai::find()->where(['user_id' => $user_login_id, 'deleted'=>0])->one();
        $modelRiwayat = RiwayatPendidikan::find()->where(['riwayat_pendidikan_id'=>$riwayat_id])->one();

        if($modelPegawai != null){
            if($modelPegawai->pegawai_id === $modelRiwayat->pegawai_id){
                return true;
            }
        }
        else
        {
            return false;
        }

    }
}

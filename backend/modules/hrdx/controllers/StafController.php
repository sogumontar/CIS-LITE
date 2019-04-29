<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use backend\modules\hrdx\models\Staf;
use backend\modules\hrdx\models\Dosen;
use backend\modules\hrdx\models\Prodi;
use backend\modules\hrdx\models\StafRole;
use backend\modules\hrdx\models\search\StafSearch;
use backend\modules\hrdx\models\RiwayatPendidikan;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\Jenjang;
use backend\modules\admin\models\Workgroup;
use backend\modules\admin\models\UserHasWorkgroup;
use backend\modules\admin\models\UserHasRole;
use backend\modules\admin\models\User;
use backend\modules\admin\models\Role;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * StafController implements the CRUD actions for Staf model.
 */
class StafController extends Controller
{
    public $menuGroup = "hrdx-controller";
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 //'skipActions' => ['*'],
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
     * Lists all Staf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StafSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $staf_role = StafRole::find()->where('deleted!=1')->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'staf_role' => $staf_role
        ]);
    }

    /**
     * Displays a single Staf model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Staf::find()
                        ->where(['staf_id'=> $id])
                        ->with('riwayatPendidikan')
                        ->one();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Staf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd($id)
    {
        $model = new Staf();
        $pendMdl = new RiwayatPendidikan();
        $modelStafRole = StafRole::find()->all();
        $pegawaistatus = Staf::find()
                            ->where(['pegawai_id' => $id])
                            ->one();

        $prodi = Prodi::find()->where(['is_hidden'=>0])->all();
        $jenjang = Jenjang::find()->all();
        $conf_roleTA = Yii::$app->appConfig->get('staf_role_asisten_dosen');
        $roleTA = StafRole::find()->where(['nama'=> $conf_roleTA])->one();

        $modelWorkgroup = Workgroup::find()->where(['name'=>'teaching-assistant'])->one();
        $modelRole = Role::find()->where(['name'=>'teaching-assistant'])->one();
        $modelPegawai = Pegawai::find()->where(['pegawai_id'=>$id])->one();
        $isWgroupTA = false;
        $isRoleTA = false;
// 
        if($modelWorkgroup == null || $modelRole == null){
            \Yii::$app->messenger->addWarningFlash("Role/workgroup sebagai staf belum di set. Hubungi admin !!!");
            return $this->redirect(Url::toRoute('pegawai/index'));
        }

        $wgroup = UserHasWorkgroup::find(['workgroup_id'])->where(['user_id'=>$modelPegawai->user_id])->asArray()->all();
        $role = UserHasRole::find(['workgroup_id'])->where(['user_id'=>$modelPegawai->user_id])->asArray()->all();


        if($modelPegawai->user_id !== null){
            $modelUser = User::findOne($modelPegawai->user_id);
        }
        else{
            \Yii::$app->messenger->addWarningFlash("Pegawai belum memiliki username. Silahkan tambahkan username pegawai terlebih dahulu.");
            return $this->redirect(Url::toRoute('pegawai/index')); 
        }

        //cek apakah id_pegawai sudah terdaftar sebagai dosen atau staf
        if(Dosen::find()->where(['pegawai_id'=> $id])->one() != null){
            \Yii::$app->messenger->addErrorFlash("Sudah terdaftar sebagai dosen");
            return $this->redirect(Url::toRoute('pegawai/index'));
        }else if(Staf::find()->where(['pegawai_id'=> $id])->one() != null){
            \Yii::$app->messenger->addErrorFlash("Sudah terdaftar sebagai staf");
            return $this->redirect(Url::toRoute('pegawai/index'));
        }

         //AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->pegawai_id = $id;

        if ($model->load(Yii::$app->request->post()) && $pendMdl->load(Yii::$app->request->post()) && $model->save()) {
            $pendMdl->pegawai_id = $model->pegawai_id;
            
            $pendMdl->save();          
                
            if($_POST['Staf']['staf_role_id'] == $roleTA->staf_role_id){
                // var_dump($isWgroupTA);
                if($modelWorkgroup !== null){
                    //assign workgroup as teaching-assistant
                    if($modelUser !== null){
                        foreach ($wgroup as $key => $value) {
                            if(in_array($modelWorkgroup['workgroup_id'], $value)){
                                $isWgroupTA= true;
                                break;
                            }
                        }
                    }
                } 

                if($modelRole !== null){
                    if($modelUser !== null){
                        foreach ($role as $key => $value) {
                            if(in_array($modelRole['role_id'], 
                                $value)){
                                $isRoleTA = true;
                                break;
                            }
                        }
                    }
                }

                if (!$isWgroupTA) {
                    $modelWorkgroup->link('users', $modelUser);
                }
                if(!$isRoleTA) {
                    $modelUser->link('roles', $modelRole);
                }
                
            }
            Yii::$app->messenger->addSuccessFlash('Berhasil menambahkan Staf');
            if($model->pegawai->user_id != null){
                Yii::$app->messenger->sendNotificationToUser($model->pegawai->user_id, "Anda sudah di assign sebagai staf");
            }
            return $this->redirect(['view', 'id' => $model->staf_id]);
            
        } else{
            return $this->render('add', [
                'model' => $model,
                'pendMdl' => $pendMdl,
                'prodi' => $prodi,
                'jenjang' => $jenjang,
                'stafRole' => $modelStafRole,
            ]);
        }
    }

    /*
    * Update role and workgroup as teaching assiten
    * @param integer $id -->staf id
    * @return mixed
    */

    public function actionAssign($id){
        $model = $this->findModel($id);
        $modelWorkgroup = workgroup::find()->where(['name'=>'teaching-assistant'])->one();
        $modelRole = Role::find()->where(['name'=>'teaching-assistant'])->one();
        
        if($model!==null){
            $modelUser =  User::findOne($model->pegawai->user_id);
        }
      
        $wgroup = UserHasWorkgroup::find(['workgroup_id'])->where(['user_id'=>$model->pegawai_id])->asArray()->all();
        $role = UserHasRole::find(['workgroup_id'])->where(['user_id'=>$model->pegawai_id])->asArray()->all();

        //assign workgroup as teaching-assistant
        if($modelWorkgroup !== null || $modelRole !== null){
            if (!in_array('teaching-assistant', $wgroup) || !in_array('teaching-assistant', $role)) {
                if (!in_array('teaching-assistant', $wgroup) ) {
                    $modelWorkgroup->link('users', $modelUser);
                }
                if (!in_array('teaching-assistant', $role)) {
                    $modelUser->link('roles', $modelRole);
                }
                Yii::$app->messenger->addSuccessFlash('Berhasil menambahkan TA');
                Yii::$app->messenger->sendNotificationToUser($model->pegawai->user_id, "Anda sudah di assign sebagai Teaching Assisten");
                return $this->redirect(['index']);
            }
            else{
                return $this->redirect(['index']);
            }
        }
        else
        {
            Yii::$app()->messenger->addSuccessFlash('Tidak dapat ditambakan sebagai TA');
            return $this->redirect(['index']);    
        }

        
    } 

    /**
     * Updates an existing Staf model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        Yii::$app->privilegeControl->requiredPermission('edit-staf');

        $model = $this->findModel($id);

        $modelStafRole = StafRole::find()->all();

        $prodi = Prodi::find()->where(['is_hidden'=>0])->All();
        $jenjang = Jenjang::find()->all();
        // echo '<pre>';
        // var_dump($model);die;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staf_id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
                'prodi' => $prodi,
                'jenjang' => $jenjang,
                'stafRole' => $modelStafRole,
            ]);
        }
    }

    /**
     * Deletes an existing Staf model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Staf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = Staf::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

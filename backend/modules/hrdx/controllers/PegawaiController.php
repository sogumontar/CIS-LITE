<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\Dosen;
use backend\modules\hrdx\models\Staf;
use backend\modules\admin\models\User;
use backend\modules\hrdx\models\search\PegawaiSearch;
use backend\modules\adak\models\search\PenugasanPengajaranSearch;
use backend\modules\mref\models\StatusAktifPegawai;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

//get publikasi
use backend\modules\lppm\models\Publikasi;
/**
 * Needed for AJAX
 */
use yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * PegawaiController implements the CRUD actions for Pegawai model.
 */
class PegawaiController extends Controller
{
    public $menuGroup = 'hrdx-controller';
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
     * Lists all Pegawai models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pegawai model.
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
     * Creates a new Pegawai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Pegawai();
         //AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        //AJAX Validation end

        if($model->load(Yii::$app->request->post())){
            $username = ($_POST['Pegawai']['user_id']);
           // echo $username;// die;
            $user = User::find()->where(['username' => $username])->one();
           
            if(!is_null($user)){
                $uname_status = Pegawai::find()->where(['user_id'=>$user->user_id])->one();
                 /*echo "<pre>";
            var_dump($uname_status);
            die;*/
                if(is_null($uname_status)){
                    $model->user_id = $user->user_id;

                    if($model->save()){
                        Yii::$app->messenger->addSuccessFlash("Pegawai sudah ditambahkan");
                        return $this->redirect(['view', 'id' => $model->pegawai_id]);
                    }
                }
                else{
                    Yii::$app->messenger->addErrorFlash("Username sudah digunakan oleh user lain.");
                    
                    return $this->render('add', ['model' => $model]);
                }
                
            }
            else {
                Yii::$app->messenger->addErrorFlash("Username yang anda masukkan belum terdaftar. 
                        Silahkan hubungi administrator untuk mendaftar");
                return $this->render('add', [
                    'model' => $model,
                ]);
            }

        }else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Pegawai model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModelUpdate($id);

        //AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        //AJAX Validation end

        if ($model->load(Yii::$app->request->post()))
        {

            $user_edited = Pegawai::find()->with('user')->where(['pegawai_id' => $id])->one();
            $username = ($_POST['Pegawai']['user_id']);
            $user = User::find()->where(['username' => $username])->one();
           
            if(!is_null($user)){
               $uname_status = Pegawai::find()->where(['user_id'=>$user->user_id])->one();
              
               if(is_null($uname_status) || $username == $user_edited->user->username){
                    $model->user_id = $user->user_id;

                    if($model->save()){
                        Yii::$app->messenger->addSuccessFlash("Pegawai sudah diedit");
                        return $this->redirect(['view', 'id' => $model->pegawai_id]);
                    }
                }
                else{
                    Yii::$app->messenger->addErrorFlash("Username sudah digunakan oleh user lain.");
                    
                    return $this->render('add', ['model' => $model]);
                }

            }
            else {
                Yii::$app->messenger->addErrorFlash("Username yang anda masukkan belum terdaftar. 
                        Silahkan hubungi administrator untuk mendaftar");
                return $this->render('add', [
                    'model' => $model,
                ]);
            }
        }else {
            return $this->render('edit', ['model' => $model]);
        }

    }

    /**
     * Change an status aktif pegawai->nonaktif.
     * If status change is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNonaktif($id){
        $model = $this->findModel($id);
        $status = Yii::$app->appConfig->get('status_pegawai_aktif');

        $modelStatusAktif = StatusAktifPegawai::find()->where(['desc'=>$status])->one();
        // var_dump(expression)
        if(! empty($model)){
            $model->status_aktif_pegawai_id = $modelStatusAktif->status_aktif_pegawai_id;
            $model->update();

            if(!empty($modelUser)){
                $modelUser->status = 0;
                $modelUser->update();

                Yii::$app->messenger->addWarningFlash("Pegawai ini tidak memiliku user pada sistem");
            }
            // echo "<pre>";
            // var_dump($model);
            // // echo "<pre>";
            // // var_dump($modelUser);
            // die;
            Yii::$app->messenger->addSuccessFlash("Pegawai sudah di nonaktifkan");
            return $this->redirect(['index']);
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


    }

    public function actionViewDataDiri()
    {
        
        $user_login = Yii::$app->user->id;
        $model = Pegawai::find()->where(['user_id'=>$user_login])->one();

        
        if(Yii::$app->privilegeControl->isHasWorkgroup('dosen')){
            $dosen = Dosen::find()
                        ->joinWith('pegawai')
                        -> where(['hrdx_pegawai.user_id' => $user_login, 'hrdx_dosen.deleted'=>0])
                        ->with('riwayatPendidikan')
                        ->one(); 
           if($dosen != null){
                $searchModel = new PenugasanPengajaranSearch();
                $dataProvider = $searchModel->searchUnionByPengajar($dosen->pegawai_id, Yii::$app->request->queryParams);
                
                //get publikasi
                $_providerPublikasi = new ActiveDataProvider([
                                    'query' => Publikasi::find()
                                        ->where(['pegawai_id'=>$model->pegawai_id])
                                        ->andWhere(['deleted'=>0])
                                        ->andWhere(['reward'=>1])
                                        ->orderBy(['tanggal_publish'=>SORT_DESC]),
                                    'pagination' => [
                                        'pageSize' => 20,
                                    ],
                ]);
                return $this->render('viewDataDiri', [
                        'model' => $model,
                        'dosen'=> $dosen,
                        'dataProvider'=>$dataProvider,
                        '_providerPublikasi'=>$_providerPublikasi,
                    ]);
            }else{
                \Yii::$app->messenger->addErrorFlash("Data dosen anda belum terdaftar");
                return $this->redirect(Url::toRoute('dosen/index'));
            }
        }elseif(Yii::$app->privilegeControl->isHasWorkgroup('teaching-assistant')){
            $staf = Staf::find()
                        ->joinWith('pegawai')
                        -> where(['hrdx_pegawai.user_id' => $user_login, 'hrdx_staf.deleted'=>0])
                        ->with('riwayatPendidikan')
                        ->one();
            
            if($staf != null){
                  
                $searchModel = new PenugasanPengajaranSearch();
                $dataProvider = $searchModel->searchUnionByPengajar($staf->pegawai_id, Yii::$app->request->queryParams);
                
                return $this->render('viewDataDiri', [
                        'model' => $model,
                        'staf'=> $staf,
                        'dataProvider'=>$dataProvider,
                ]);
            }else{
                \Yii::$app->messenger->addErrorFlash("Data staf anda belum terdaftar");
                return $this->redirect(Url::toRoute('staf/index'));
            }
        }
    }

    /**
     * Finds the Pegawai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pegawai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function findModelUpdate($id)
    {
        if (($model = Pegawai::findOne($id)) !== null) {
            $user = User::find()->where(['user_id' => $model->user_id])->one();
            //  echo "<pre>";
            // var_dump($user);
            // die;
            if(!is_null($user)){
                $model->user_id = $user->username;
            }
            return $model;    
            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

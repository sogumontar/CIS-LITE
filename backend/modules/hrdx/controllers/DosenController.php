<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Dosen;
use backend\modules\hrdx\models\search\DosenSearch;
use backend\modules\hrdx\models\RiwayatPendidikan;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\Staf;
use backend\modules\mref\models\GolonganKepangkatan;
use backend\modules\mref\models\JabatanAkademik;
use backend\modules\mref\models\StatusIkatanKerjaDosen;
use backend\modules\mref\models\Gbk;
use backend\modules\mref\models\RoleDosen;
use backend\modules\hrdx\models\Jenjang;
use backend\modules\hrdx\models\Prodi;
use backend\modules\adak\models\search\PenugasanPengajaranSearch;
use backend\modules\admin\models\Workgroup;
use backend\modules\admin\models\UserHasWorkgroup;
use backend\modules\admin\models\UserHasRole;
use backend\modules\admin\models\User;
use backend\modules\adak\models\Registrasi;
use backend\modules\admin\models\Role;
use yii\helpers\Url;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

//get publikasi
use backend\modules\lppm\models\Publikasi;
/**
 * Needed for AJAX
 */
use yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/**
 * DosenController implements the CRUD actions for Dosen model.
 */
class DosenController extends Controller
{
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
     * Lists all Dosen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DosenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       /* $model = new Dosen();
        $model->myCourses;*/

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider, 
        ]);
    }

    /**
     * Displays a single Dosen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $isActiveTab = null)
    {

        $dosen = Dosen::find()
                        ->where(['dosen_id' => $id])
                        ->with('riwayatPendidikan')
                        ->one();
        //get publikasi
        $publikasi  = $this->getPublikasi($dosen->pegawai_id);
        if($dosen != null){
                $searchModel = new PenugasanPengajaranSearch();
                $dataProvider = $searchModel->searchUnionByPengajar($dosen->pegawai_id, Yii::$app->request->queryParams);
        
            return $this->render('view', [
                'model' => $dosen,
                'dataProvider'=>$dataProvider,
                'publikasi'=>$publikasi,
            ]);
        }
        else{
            return $this->redirect(Url::toRoute('dosen/index'));
        }
    }

    /**
     * Creates a new Dosen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * param $id (pegawai_id)
     * @return mixed
     */
    public function actionAdd($id)
    {   
        $model = new Dosen();
        $modelWorkgroup = workgroup::find()->where(['name'=>'dosen'])->one();
        $modelRole = Role::find()->where(['name'=>'dosen'])->one();
        $modelPegawai = Pegawai::find()->where(['pegawai_id'=>$id])->one();
        $isWgroupDosen = false;
        $isRoleDosen = false;

        if($modelWorkgroup == null || $modelRole == null){
            \Yii::$app->messenger->addWarningFlash("Role/workgroup sebagai dosen belum di set. Hubungi admin !!!");
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

        $pendMdl = new RiwayatPendidikan();
        $pangkat = GolonganKepangkatan::find()->all();
        $jabatan =JabatanAkademik::find()->all();
        $ikatanKerja = StatusIkatanKerjaDosen::find()->all();
        $jenjang = Jenjang::find()->all();
        $prodi = Prodi::find()->where(['is_hidden'=> 0])->all();
        // echo "<pre>";
        // var_dump($prodi);
        // die;
        $gbk = Gbk::find()->all();


        $dosen = Dosen::find()->where(['pegawai_id'=> $id])->one();
        $staf = Staf::find()->where(['pegawai_id'=> $id])->one();

        $roleTA = Yii::$app->appConfig->get('staf_role_asisten_dosen');

        //cek apakah id_pegawai sudah terdaftar sebagai dosen atau staf
        if( $dosen != null){
          \Yii::$app->messenger->addErrorFlash("Sudah terdaftar sebagai dosen");
            return $this->redirect(Url::toRoute('pegawai/index'));
        }
        else if($staf != null){   
            \Yii::$app->messenger->addWarningFlash("Sudah terdaftar sebagai staf");
        }

        // AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        //AJAX Validation end

         $model->pegawai_id = $id;


        if ($model->load(Yii::$app->request->post()) && $pendMdl->load(Yii::$app->request->post())) {
            //cek apakah sudah terdaftar sebagai staff
            //jika sudah, set aktif_end pada tabel staf
            if($staf != NULL){
                $staf->aktif_end = date('Y-m-d');
                $staf->update();
            }

            if($model->save()){
                $pendMdl->pegawai_id = $model->pegawai_id;
                $pendMdl->save() ;
                
                if($modelWorkgroup !== null || $modelRole !== null){
                    //assign workgroup as dosen
                    if($modelUser !== null){
                        foreach ($wgroup as $key => $value) {
                            if(in_array($modelWorkgroup->workgroup_id, $value)){
                                $isWgroupDosen = true;
                                break;
                            }
                        }
                        
                        foreach ($role as $key => $value) {
                            if(in_array($modelRole->role_id, $value)){
                                $isRoleDosen = true;
                                break;
                            }
                        }
                        if (!$isWgroupDosen) {
                            $modelWorkgroup->link('users', $modelUser);
                        }
                        if (!$isRoleDosen) {
                            $modelUser->link('roles', $modelRole);
                        }
                    }
                    Yii::$app->messenger->addSuccessFlash('Berhasil menambahkan Dosen');
                    if($model->pegawai->user_id != null){
                        Yii::$app->messenger->sendNotificationToUser($model->pegawai->user_id, "Anda sudah di assign sebagai Dosen");
                    }
                    return $this->redirect(['view', 'id' => $model->dosen_id]);
                }
            }
            else
            {
                print_r($model->getErrors());
            }
           
        } 
        return $this->render('add', [
                'model' => $model,
                'pendMdl' =>$pendMdl,
                'pangkat' =>$pangkat,
                'jabatan' => $jabatan,
                'ikatanKerja' => $ikatanKerja,
                'jenjang' => $jenjang,
                'prodi' => $prodi,
                'gbk' => $gbk,
            ]);
    }

    /**
     * Updates an existing Dosen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        Yii::$app->privilegeControl->requiredPermission('edit-dosen');

        $model = $this->findModel($id);
        $pendMdl = new RiwayatPendidikan();

        $jabatan =JabatanAkademik::find()->all();
        $ikatanKerja = StatusIkatanKerjaDosen::find()->all();
        $jenjang = Jenjang::find()->all();
        $prodi = Prodi::find()->where(['is_hidden' => 0])->all();
        $gbk = Gbk::find()->all();
        $pangkat = GolonganKepangkatan::find()->all();

         //AJAX Validation start
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        //AJAX Validation end

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           Yii::$app->messenger->addSuccessFlash('Data dosen berhasil di ubah');
            return $this->redirect(['view', 'id' => $model->dosen_id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
                'jabatan' => $jabatan,
                'ikatanKerja' => $ikatanKerja,
                'jenjang' => $jenjang,
                'prodi' => $prodi,
                'gbk' => $gbk,
                'pangkat' =>$pangkat,
            ]);
        }
    }


    public function actionViewAnakWali(){
        $user_login = Yii::$app->user->id;
        $cur_ta = Yii::$app->appConfig->get('tahun_ajaran');
        $cur_sem_ta = Yii::$app->appConfig->get('semester_tahun_ajaran');
        $searchModel = new DosenSearch();
        $dataProvider = $searchModel->searchAnakWaliInTaSem(Yii::$app->request->queryParams);

        $modelPegawai = Pegawai::find()->where(['user_id' => $user_login, 'deleted'=>0])->one();

        if(empty($modelPegawai)){
            \Yii::$app->messenger->addErrorFlash("Anda bukan dosen");
            return $this->redirect(Url::toRoute('/hrdx/'));
        }

        $listThnAjaran = Registrasi::find()->select(['ta'])
                                        ->where(['dosen_wali_id' => $modelPegawai->pegawai_id])
                                        ->orderBy(['ta' => SORT_DESC])
                                        ->groupBy(['ta'])
                                        ->all();
        $listSemTa = Registrasi::find()->select(['sem_ta'])
                                        ->where(['dosen_wali_id' =>$modelPegawai->pegawai_id])
                                        ->orderBy(['sem_ta' => SORT_DESC])
                                        ->groupBy(['sem_ta'])
                                        ->all();
        $listSem = Registrasi::find()->select(['sem'])
                                        ->where(['dosen_wali_id' => $modelPegawai->pegawai_id])
                                        ->orderBy(['sem' => SORT_DESC])
                                        ->groupBy(['sem'])
                                        ->all();

        return $this->render('viewAnakWali',[
            'dataProvider' => $dataProvider,
            'ta' => $cur_ta,
            'sem_ta' => $cur_sem_ta,
            'modelPegawai' => $modelPegawai,
            'listThnAjaran' => $listThnAjaran,
            'listSemTa' => $listSemTa,
            'listSem' => $listSem,
            ]);
    }

//get publikasi
    protected function getPublikasi($pegawai_id)
    {
            $dataProvider = new ActiveDataProvider([
                'query' => Publikasi::find()
                    ->where(['pegawai_id'=>$pegawai_id])
                    ->andWhere(['deleted'=>0])
                    ->andWhere(['reward'=>1])
                    ->orderBy(['tanggal_publish'=>SORT_DESC]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            return $dataProvider;
    }
    /**
     * Deletes an existing Dosen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dosen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dosen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dosen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

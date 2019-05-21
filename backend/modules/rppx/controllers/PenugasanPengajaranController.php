<?php

namespace backend\modules\rppx\controllers;

use backend\modules\cist\models\Pegawai;
use Yii;
use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\AdakPengajaran;
use backend\modules\rppx\models\Kuliah;
use backend\modules\rppx\models\search\PenugasanPengajaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\rppx\models\Staf;
use backend\modules\rppx\models\HrdxPegawai;

/**
 * PenugasanPengajaranController implements the CRUD actions for PenugasanPengajaran model.
 */
class PenugasanPengajaranController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'allow'=>true,
                    'roles'=>['@'],
                ],
            ],
        ];
    }

    /**
     * Lists all PenugasanPengajaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenugasanPengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PenugasanPengajaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new PenugasanPengajaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionMenu(){
        return $this->render('menu');
    }

    public function actionCreate($semester)
    {
        $model = new PenugasanPengajaran();
        $modelPengajaran = Kuliah::find()->where(['sem' => $semester])->all();
        $jlhDosen = 1;
        $jlhAsdos = 1;
        $baris=0;
        $colom=0;

        if ($model->load(Yii::$app->request->post())) {

            $model->load2=1;
            // var_dump($model->load);
            // die();
            $model->save(false);

            return $this->redirect(['view', 'id' => $model->penugasan_pengajaran_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $semester,
            ]);
            echo $model;
        }
    }

    /**
     * Updates an existing PenugasanPengajaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new PenugasanPengajaran();
        $modelPengajaran = Kuliah::find()->where(['sem' => 3])->all();
        $jlhDosen = 1;
        $jlhAsdos = 1;
        $baris=0;
        $colom=0;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->penugasan_pengajaran_id]);
        } else {
            return $this->render('update', [
              'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> 3,
            ]);
        }
    }

    public function actionDekan(){
        return $this->render('dekan');
    }
    public function actionApprove(){
        return $this->render('Approval');
    }
    public function actionXx(){
        return $this->render('dekan');
    }
    /**
     * Deletes an existing PenugasanPengajaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        echo $id;   
        // $this->findModel($id)->delete();
        Yii::$app->db->createCommand()->delete('rppx_penugasan_pengajaran','penugasan_pengajaran_id='.$id)->execute();

        // PenugasanPengajaran::findModel($id)->delete();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the PenugasanPengajaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenugasanPengajaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenugasanPengajaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionPegawais()
    {
        if(null !== Yii::$app->request->post()){
            $pegawais = Pegawai::find()->orderBy(['nama' => SORT_ASC])->asArray()->all();
            return json_encode($pegawais);
        }else{
            return "Ajax failed";
        }
    }
    public function actionPegawai(){
        if(null !== Yii::$app->request->post()){
            $staf=Staf::find()->select('pegawai_id')->asArray()->all();
            
                $pegawai = HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all();
            
            return json_encode($pegawai);
        }else{
            return "Ajax failed";
        }
    }
    public function actionRequest(){
        $searchModel = new PenugasanPengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('request', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionInsret(){
       die();
    }
     public function actionTtg(){
      return $this->render('data_convert');
    }
    public function actionConvert(){
       return $this->render('data_convert');
    }
}

<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use backend\modules\hrdx\models\JenisAbsen;
use backend\modules\hrdx\models\search\JenisAbsenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JenisAbsenController implements the CRUD actions for JenisAbsen model.
 */
class JenisAbsenController extends Controller
{
    public $menuGroup = "pegawai-absensi";

    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
                ],
                
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionEditCutiBersama($id=null){
        $cutiBersamaConf = Yii::$app->appConfig->get('cuti_bersama');
        $modelCutiBersama = JenisAbsen::find()->where(['nama'=>$cutiBersamaConf,'deleted'=>0])->one();
        $searchModel = new JenisAbsenSearch();

        if($modelCutiBersama == null){
            return $this->redirect('add');
        }
        else {
            $cuti_bersama = $searchModel->searchCutiBersama(Yii::$app->request->queryParams,$modelCutiBersama->jenis_absen_id);
        }    

        if ($modelCutiBersama->load(Yii::$app->request->post()) && $modelCutiBersama->save()) {
            return $this->redirect(['index']);
        } else {
                return $this->render('edit', [
                    'model' => $modelCutiBersama,
                ]);
     
        }
    }
    /**
     * Lists all JenisAbsen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JenisAbsenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JenisAbsen model.
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
     * Creates a new JenisAbsen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new JenisAbsen();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JenisAbsen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing JenisAbsen model.
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
     * Finds the JenisAbsen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JenisAbsen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JenisAbsen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

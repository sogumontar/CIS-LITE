<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\RencanaStrategis;
use backend\modules\rakx\models\search\RencanaStrategisSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RencanaStrategisController implements the CRUD actions for RencanaStrategis model.
 */
class RencanaStrategisController extends Controller
{
    public $menuGroup = 'rakx-rencana-strategis';
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
     * Lists all RencanaStrategis models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RencanaStrategisSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RencanaStrategis model.
     * @param integer $id
     * @return mixed
     */
    public function actionRencanaStrategisView($id)
    {
        return $this->render('RencanaStrategisView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RencanaStrategis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRencanaStrategisAdd()
    {
        $this->menuGroup = '';
        $model = new RencanaStrategis();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['rencana-strategis-view', 'id' => $model->rencana_strategis_id]);
        } else {
            return $this->render('RencanaStrategisAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RencanaStrategis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRencanaStrategisEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['rencana-strategis-view', 'id' => $model->rencana_strategis_id]);
        } else {
            return $this->render('RencanaStrategisEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RencanaStrategis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRencanaStrategisDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RencanaStrategis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RencanaStrategis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RencanaStrategis::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

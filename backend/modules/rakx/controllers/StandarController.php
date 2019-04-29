<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\Standar;
use backend\modules\rakx\models\search\StandarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StandarController implements the CRUD actions for Standar model.
 */
class StandarController extends Controller
{
    public $menuGroup = 'rakx-standar';
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
     * Lists all Standar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StandarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Standar model.
     * @param integer $id
     * @return mixed
     */
    public function actionStandarView($id)
    {
        return $this->render('StandarView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Standar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionStandarAdd()
    {
        $this->menuGroup = '';
        $model = new Standar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['standar-view', 'id' => $model->standar_id]);
        } else {
            return $this->render('StandarAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Standar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStandarEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['standar-view', 'id' => $model->standar_id]);
        } else {
            return $this->render('StandarEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Standar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStandarDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Standar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Standar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Standar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

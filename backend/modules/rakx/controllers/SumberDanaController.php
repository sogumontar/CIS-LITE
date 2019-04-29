<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\SumberDana;
use backend\modules\rakx\models\search\SumberDanaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SumberDanaController implements the CRUD actions for SumberDana model.
 */
class SumberDanaController extends Controller
{
    public $menuGroup = 'rakx-sumber-dana';
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
     * Lists all SumberDana models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SumberDanaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SumberDana model.
     * @param integer $id
     * @return mixed
     */
    public function actionSumberDanaView($id)
    {
        return $this->render('SumberDanaView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SumberDana model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSumberDanaAdd()
    {
        $this->menuGroup = '';
        $model = new SumberDana();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('SumberDanaAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SumberDana model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSumberDanaEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('SumberDanaEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SumberDana model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSumberDanaDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SumberDana model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SumberDana the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SumberDana::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

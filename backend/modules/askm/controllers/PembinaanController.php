<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Pembinaan;
use backend\modules\askm\models\search\PembinaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PembinaanController implements the CRUD actions for Pembinaan model.
 * controller-id: pembinaan
 * controller-desc: Controller untuk me-manage data pembinaan
 */
class PembinaanController extends Controller
{
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
     * Lists all Pembinaan models.
     * action-id: index
     * action-desc: Display all data
     * Lists all Pembinaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PembinaanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists single Pembinaan models.
     * @return mixed
     */
    /*
    * action-id: view
    * action-desc: Display Single Data
    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Add new Pembinaan models.
     * @return mixed
     */
    /*
    * action-id: add
    * action-desc: Add Single Data
    */
    public function actionAdd()
    {
        $model = new Pembinaan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Edit existing Pembinaan models.
     * @return mixed
     */
    /*
    * action-id: edit
    * action-desc: Edit single existing data
    */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view']);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pembinaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus bentuk pembinaan
    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pembinaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pembinaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pembinaan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

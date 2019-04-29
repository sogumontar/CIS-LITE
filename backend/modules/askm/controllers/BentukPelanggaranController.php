<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\BentukPelanggaran;
use backend\modules\askm\models\search\BentukPelanggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BentukPelanggaranController implements the CRUD actions for BentukPelanggaran model.
 * controller-id: bentuk-pelanggaran
 * controller-desc: Controller untuk me-manage data bentuk pelanggaran
 */
class BentukPelanggaranController extends Controller
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
     * Lists all BentukPelanggaran models.
     * action-id: index
     * action-desc: Display all data
     * Lists all BentukPelanggaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BentukPelanggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists single BentukPelanggaran models.
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
     * Add new BentukPelanggaran models.
     * @return mixed
     */
    /*
    * action-id: add
    * action-desc: Add Single Data
    */
    public function actionAdd()
    {
        $model = new BentukPelanggaran();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Edit existing BentukPelanggaran models.
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
            return $this->redirect(['index']);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BentukPelanggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus bentuk pelanggaran
    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BentukPelanggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BentukPelanggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BentukPelanggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

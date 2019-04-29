<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\TingkatPelanggaran;
use backend\modules\askm\models\search\TingkatPelanggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TingkatPelanggaranController implements the CRUD actions for TingkatPelanggaran model.
  * controller-id: tingkat-pelanggaran
 * controller-desc: Controller untuk me-manage data tingkat pelanggaran
 */
class TingkatPelanggaranController extends Controller
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
     * Lists all TingkatPelanggaran models.
     * @return mixed
     */
    /*
    * action-id: index
    * action-desc: Display All Data
    */
    public function actionIndex()
    {
        $searchModel = new TingkatPelanggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists single TingkatPelanggaran models.
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
     * Add new TingkatPelanggaran models.
     * @return mixed
     */
    /*
    * action-id: add
    * action-desc: Add Single Data
    */
    public function actionAdd()
    {
        $model = new TingkatPelanggaran();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Edit existing TingkatPelanggaran models.
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
     * Deletes an existing TingkatPelanggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus tingkat pelanggaran
    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TingkatPelanggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TingkatPelanggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TingkatPelanggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

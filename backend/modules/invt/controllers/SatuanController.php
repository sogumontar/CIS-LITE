<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\Satuan;
use backend\modules\invt\models\search\SatuanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SatuanController implements the CRUD actions for Satuan model.
 */
class SatuanController extends Controller
{
    public $menuGroup = 'm-satuan';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Satuan models.
     * @return mixed
     */
    public function actionSatuanBrowse()
    {
        $searchModel = new SatuanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('SatuanBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Satuan model.
     * @param integer $id
     * @return mixed
     */
    public function actionSatuanView($id)
    {
        return $this->render('SatuanView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Satuan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSatuanAdd()
    {
        $model = new Satuan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['satuan-view', 'id' => $model->satuan_id]);
        } else {
            return $this->render('SatuanAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Satuan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSatuanEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['satuan-view', 'id' => $model->satuan_id]);
        } else {
            return $this->render('SatuanEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Satuan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSatuanDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['satuan-browse']);
    }

    /**
     * Finds the Satuan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Satuan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Satuan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

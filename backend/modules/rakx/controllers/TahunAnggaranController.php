<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\TahunAnggaran;
use backend\modules\rakx\models\search\TahunAnggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TahunAnggaranController implements the CRUD actions for TahunAnggaran model.
 */
class TahunAnggaranController extends Controller
{
    public $menuGroup = 'rakx-tahun-anggaran';
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
     * Lists all TahunAnggaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TahunAnggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TahunAnggaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionTahunAnggaranView($id)
    {
        return $this->render('TahunAnggaranView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TahunAnggaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionTahunAnggaranAdd()
    {
        $this->menuGroup = '';
        $model = new TahunAnggaran();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tahun-anggaran-view', 'id' => $model->tahun_anggaran_id]);
        } else {
            return $this->render('TahunAnggaranAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TahunAnggaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTahunAnggaranEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tahun-anggaran-view', 'id' => $model->tahun_anggaran_id]);
        } else {
            return $this->render('TahunAnggaranEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TahunAnggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTahunAnggaranDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TahunAnggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TahunAnggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TahunAnggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

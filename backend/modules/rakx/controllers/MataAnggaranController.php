<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\MataAnggaran;
use backend\modules\rakx\models\Standar;
use backend\modules\rakx\models\search\MataAnggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MataAnggaranController implements the CRUD actions for MataAnggaran model.
 */
class MataAnggaranController extends Controller
{
    public $menuGroup = 'rakx-mata-anggaran';
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
     * Lists all MataAnggaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MataAnggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $standar = Standar::find()->where('deleted != 1')->All();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'standar' => $standar,
        ]);
    }

    /**
     * Displays a single MataAnggaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionMataAnggaranView($id)
    {
        return $this->render('MataAnggaranView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MataAnggaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionMataAnggaranAdd()
    {
        $this->menuGroup = '';
        $model = new MataAnggaran();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mata-anggaran-view', 'id' => $model->mata_anggaran_id]);
        } else {
            $standar = Standar::find()->where('deleted != 1')->All();
            return $this->render('MataAnggaranAdd', [
                'model' => $model,
                'standar' => $standar,
            ]);
        }
    }

    /**
     * Updates an existing MataAnggaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMataAnggaranEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['mata-anggaran-view', 'id' => $model->mata_anggaran_id]);
        } else {
            $standar = Standar::find()->where('deleted != 1')->All();
            return $this->render('MataAnggaranEdit', [
                'model' => $model,
                'standar' => $standar,
            ]);
        }
    }

    /**
     * Deletes an existing MataAnggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMataAnggaranDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MataAnggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MataAnggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MataAnggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

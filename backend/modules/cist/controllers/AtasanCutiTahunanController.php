<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\AtasanCutiTahunan;
use backend\modules\cist\models\search\AtasanCutiTahunanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AtasanCutiTahunanController implements the CRUD actions for AtasanCutiTahunan model.
    * controller-id: atasan-cuti-tahunan
 * controller-desc: Controller untuk me-manage data Atasan Cuti Tahunan
 */
class AtasanCutiTahunanController extends Controller
{
    public function behaviors()
    {
        return [
            // TODO: crud controller actions are bypassed by default, set the appropriate privilege
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
     * Lists all AtasanCutiTahunan models.
     * @return mixed
     */
    /**
        *action-id : index
        *action-desc : Display all atasan cuti tahunan

    */
    public function actionIndex()
    {
        $searchModel = new AtasanCutiTahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AtasanCutiTahunan model.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : view
        *action-desc : Display view atasan cuti tahunan

    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AtasanCutiTahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
        *action-id : add
        *action-desc : add atasan cuti tahunan

    */
    public function actionAdd()
    {
        $model = new AtasanCutiTahunan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->atasan_cuti_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AtasanCutiTahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : edit
        *action-desc : edit atasan cuti tahunan

    */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->atasan_cuti_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AtasanCutiTahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : del
        *action-desc : delete atasan cuti tahunan

    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AtasanCutiTahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AtasanCutiTahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AtasanCutiTahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

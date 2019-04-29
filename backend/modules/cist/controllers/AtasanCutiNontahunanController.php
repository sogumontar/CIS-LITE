<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\AtasanCutiNontahunan;
use backend\modules\cist\models\search\AtasanCutiNontahunanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AtasanCutiNontahunanController implements the CRUD actions for AtasanCutiNontahunan model.
    * controller-id: atasan-cuti-nontahunan
 * controller-desc: Controller untuk me-manage data Atasan Cuti Non-Tahunan
 */
class AtasanCutiNontahunanController extends Controller
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
     * Lists all AtasanCutiNontahunan models.
     * @return mixed
     */

    /**
        *action-id : index
        *action-desc : Display all atasan cuti nontahunan

    */
    public function actionIndex()
    {
        $searchModel = new AtasanCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AtasanCutiNontahunan model.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : view
        *action-desc : Display view atasan cuti nontahunan

    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AtasanCutiNontahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
        *action-id : add
        *action-desc : add atasan cuti nontahunan

    */
    public function actionAdd()
    {
        $model = new AtasanCutiNontahunan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->atasan_cuti_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AtasanCutiNontahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
        *action-id : edit
        *action-desc : edit atasan cuti nontahunan

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
     * Deletes an existing AtasanCutiNontahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : del
        *action-desc : delete atasan cuti nontahunan

    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AtasanCutiNontahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AtasanCutiNontahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AtasanCutiNontahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

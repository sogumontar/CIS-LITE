<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\StatusProgram;
use backend\modules\rakx\models\search\StatusProgramSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StatusProgramController implements the CRUD actions for StatusProgram model.
 */
class StatusProgramController extends Controller
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
     * Lists all StatusProgram models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatusProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StatusProgram model.
     * @param integer $id
     * @return mixed
     */
    public function actionStatusProgramView($id)
    {
        return $this->render('StatusProgramView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StatusProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionStatusProgramAdd()
    {
        $model = new StatusProgram();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['status-program-view', 'id' => $model->status_program_id]);
        } else {
            return $this->render('StatusProgramAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StatusProgram model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatusProgramEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['status-program-view', 'id' => $model->status_program_id]);
        } else {
            return $this->render('StatusProgramEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StatusProgram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatusProgramDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StatusProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StatusProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StatusProgram::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

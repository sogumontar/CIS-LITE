<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\AtasanIzin;
use backend\modules\cist\models\search\AtasanIzinSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AtasanIzinController implements the CRUD actions for AtasanIzin model.
    * controller-id: atasan-izin
 * controller-desc: Controller untuk me-manage data Atasan Izin
 */
class AtasanIzinController extends Controller
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
     * Lists all AtasanIzin models.
     * @return mixed
     */
    /**
        *action-id : index
        *action-desc : Display all atasan izin

    */
    public function actionIndex()
    {
        $searchModel = new AtasanIzinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AtasanIzin model.
     * @param integer $id
     * @return mixed
     */

     /**
        *action-id : view
        *action-desc : Display view atasan izin

    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AtasanIzin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

       /**
        *action-id : add
        *action-desc : add atasan izin

    */
    public function actionAdd()
    {
        $model = new AtasanIzin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->atasan_izin_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AtasanIzin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
     /**
        *action-id : edit
        *action-desc : edit atasan izin

    */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->atasan_izin_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AtasanIzin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     /**
        *action-id : del
        *action-desc : delete atasan izin

    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AtasanIzin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AtasanIzin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AtasanIzin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

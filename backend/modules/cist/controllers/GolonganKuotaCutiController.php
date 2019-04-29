<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\GolonganKuotaCuti;
use backend\modules\cist\models\search\GolonganKuotaCutiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GolonganKuotaCutiController implements the CRUD actions for GolonganKuotaCuti model.
    * controller-id: golongan-kuota-cuti
 * controller-desc: Controller untuk me-manage data Golongan Kuota Cuti
 */
class GolonganKuotaCutiController extends Controller
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
     * Lists all GolonganKuotaCuti models.
     * @return mixed
     */

    /**
     * action-id: index
     * action-desc: Display index of golongan kuota cuti
     * */
    public function actionIndex()
    {
        $searchModel = new GolonganKuotaCutiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GolonganKuotaCuti model.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: view
     * action-desc: Display view of golongan kuota cuti
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GolonganKuotaCuti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
     * action-id: add
     * action-desc: add golongan kuota cuti
     * */
    public function actionAdd()
    {
        $model = new GolonganKuotaCuti();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->golongan_kuota_cuti_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GolonganKuotaCuti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /**
     * action-id: edit
     * action-desc: Edit golongan kuota cuti
     * */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->golongan_kuota_cuti_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GolonganKuotaCuti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /**
     * action-id: del
     * action-desc: Deleting golongan kuota cuti
     * */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GolonganKuotaCuti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GolonganKuotaCuti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GolonganKuotaCuti::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\modules\cist\controllers;

use Yii;
use backend\modules\cist\models\KategoriCutiNontahunan;
use backend\modules\cist\models\search\KategoriCutiNontahunanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KategoriCutiNontahunanController implements the CRUD actions for KategoriCutiNontahunan model.
    * controller-id: kategori-cuti-nontahunan
 * controller-desc: Controller untuk me-manage data Kategori Cuti Non-Tahunan
 */
class KategoriCutiNontahunanController extends Controller
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
     * Lists all KategoriCutiNontahunan models.
     * @return mixed
     */
     /**
        *action-id : index
        *action-desc : Display all kategori cuti

    */
    public function actionIndex()
    {
        $searchModel = new KategoriCutiNontahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single KategoriCutiNontahunan model.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : view
        *action-desc : Display view kategori cuti

    */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KategoriCutiNontahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /**
        *action-id : add
        *action-desc : add kategori cuti

    */
    public function actionAdd()
    {
        $model = new KategoriCutiNontahunan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $satuan = [['id' => 1, 'name' => 'Hari'], ['id' => 2, 'name' => 'Bulan']];
            return $this->render('Add', [
                'model' => $model,
                'satuan' => $satuan
            ]);
        }
    }

    /**
     * Updates an existing KategoriCutiNontahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : edit
        *action-desc : edit kategori cuti

    */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kategori_cuti_nontahunan_id]);
        } else {
            $satuan = [['id' => 1, 'name' => 'Hari'], ['id' => 2, 'name' => 'Bulan']];
            return $this->render('Edit', [
                'model' => $model,
                'satuan' => $satuan
            ]);
        }
    }

    /**
     * Deletes an existing KategoriCutiNontahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /**
        *action-id : del
        *action-desc : delete kategori cuti

    */

    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KategoriCutiNontahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KategoriCutiNontahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KategoriCutiNontahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

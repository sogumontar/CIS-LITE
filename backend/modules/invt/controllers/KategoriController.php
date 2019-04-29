<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\Kategori;
use backend\modules\invt\models\search\KategoriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KategoriController implements the CRUD actions for Kategori model.
 */
class KategoriController extends Controller
{
    public $menuGroup = "m-kategori";
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
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
     * Lists all Kategori models.
     * @return mixed
     */
    public function actionKategoriBrowse()
    {
        $searchModel = new KategoriSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('KategoriBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kategori model.
     * @param integer $id
     * @return mixed
     */
    public function actionKategoriView($id)
    {
        return $this->render('KategoriView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionKategoriAdd()
    {
        $model = new Kategori();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['kategori-view', 'id' => $model->kategori_id]);
        } else {
            return $this->render('KategoriAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Kategori model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionKategoriEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['kategori-view', 'id' => $model->kategori_id]);
        } else {
            return $this->render('KategoriEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Kategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionKategoriDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['kategori-browse']);
    }

    /**
     * Finds the Kategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kategori::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

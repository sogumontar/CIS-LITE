<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\JenisBarang;
use backend\modules\invt\models\search\JenisBarangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JenisBarangController implements the CRUD actions for JenisBarang model.
 */
class JenisBarangController extends Controller
{
    public $menuGroup = 'm-jenisbarang';
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
     * Lists all JenisBarang models.
     * @return mixed
     */
    public function actionJenisBarangBrowse()
    {
        $searchModel = new JenisBarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('JenisBarangBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JenisBarang model.
     * @param integer $id
     * @return mixed
     */
    public function actionJenisBarangView($id)
    {
        return $this->render('JenisBarangView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new JenisBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionJenisBarangAdd()
    {
        $model = new JenisBarang();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['jenis-barang-view', 'id' => $model->jenis_barang_id]);
        } else {
            return $this->render('JenisBarangAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing JenisBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionJenisBarangEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['jenis-barang-view', 'id' => $model->jenis_barang_id]);
        } else {
            return $this->render('JenisBarangEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing JenisBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionJenisBarangDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['jenis-barang-browse']);
    }

    /**
     * Finds the JenisBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JenisBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JenisBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\search\LokasiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LokasiController implements the CRUD actions for Lokasi model.
 */
class LokasiController extends Controller
{
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
     * Lists all Lokasi models.
     * @return mixed
     */
    public function actionLokasiBrowse()
    {
        $lokasis = Lokasi::find()->where(['deleted'=>0,'parent_id'=>0])->all();
        return $this->render('LokasiBrowse',[
            'lokasis'=>$lokasis,
        ]);
    }
    /**
     * Displays a single Lokasi model.
     * @param integer $id
     * @return mixed
     */
    public function actionLokasiView($lokasi_id)
    {
        $model = $this->findModel($lokasi_id);
        $jumlah = $model->getJumlahBarang($lokasi_id);
        echo $jumlah;
        die;
        return $this->render('LokasiView', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Lokasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionLokasiAdd($parent_id=null)
    {
        $model = new Lokasi();

        if ($model->load(Yii::$app->request->post())) {
            if($parent_id==null){
                $model->parent_id= 0;
            }else{
                $model->parent_id = $parent_id;
            }
            $model->save();
            return $this->redirect(['lokasi-view', 'lokasi_id' => $model->lokasi_id]);
        } else {
            return $this->render('LokasiAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lokasi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLokasiEdit($lokasi_id)
    {
        $model = $this->findModel($lokasi_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['lokasi-view', 'lokasi_id' => $model->lokasi_id]);
        } else {
            return $this->render('LokasiEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lokasi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLokasiDel($lokasi_id)
    {
        $model=$this->findModel($lokasi_id);
        //get all childs
        $childs = $model->getChilds();
        $model->softDelete();
        if($childs!=null){
            foreach ($childs as $key => $child) {
                $child->softDelete();
            }
        }
        return $this->redirect(['lokasi-browse']);
    }

    /**
     * Finds the Lokasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lokasi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lokasi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

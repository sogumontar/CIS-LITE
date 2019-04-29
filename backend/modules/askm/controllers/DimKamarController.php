<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\DimKamar;
use backend\modules\askm\models\Dim;
use backend\modules\askm\models\Kamar;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\search\DimKamar as DimKamarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\helpers\LinkHelper;
use yii\helpers\Url;


/**
 * DimKamarController implements the CRUD actions for DimKamar model.
 * controller-id: dim-kamar
 * controller-desc: Controller untuk me-manage data Mahasiswa Penghuni Asrama
 */
class DimKamarController extends Controller
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
    * action-id: index
     * action-desc: Display all data
     * Lists all DimKamar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DimKamarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * action-id: view
     * action-desc: Display a data
     * Displays a single DimKamar model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
    * action-id: add-penghuni
     * action-desc: Menambahkan data penghuni kamar
     * addPenghuniKamars a new DimKamar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddPenghuni($id_asrama)
    {
        $model = new DimKamar();
        $asrama = Asrama::find()->where(['asrama_id' => $id_asrama])->one();

        if ($model->load(Yii::$app->request->post())) {
            $dim_penghuni = DimKamar::find()->where('dim_id='.$model->dim_id)->andWhere('deleted!=1')->one();
            if (!empty($dim_penghuni)) {
                \Yii::$app->messenger->addWarningFlash("Penghuni telah di-assign ke kamar ".$dim_penghuni->kamar->nomor_kamar);
                return $this->redirect(['add-penghuni', 'id_asrama' => $id_asrama]);
            }
            $model->save();
            \Yii::$app->messenger->addSuccessFlash("Penghuni telah ditambahkan");
            return $this->redirect(['asrama/view-detail-asrama/', 'id' => $id_asrama]);
        } else {
            return $this->render('addPenghuniKamar', [
                'model' => $model,
                'asrama' => $asrama,
            ]);
        }
    }

/*
    * action-id: del-penghuni
     * action-desc: Menghapus data penghuni kamar
*/
    public function actionDelPenghuni($id, $id_kamar){
        $this->findModel($id)->forceDelete();
        \Yii::$app->messenger->addSuccessFlash("Penghuni telah dihapus");

        return $this->redirect(['/askm/asrama/view-detail-asrama', 'id' => $id_kamar]);

    }

    /**
    * action-id: move-penghuni-kamar
     * action-desc: memindahkan data penghuni kamar
     * Edits an existing DimKamar model.
     * If Edit is successful, the browser will be redirected to the 'view-detail-asrama' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMovePenghuniKamar($id, $id_kamar, $id_asrama)
    {
        $asrama = Asrama::find()->where(['asrama_id' => $id_asrama])->one();
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->messenger->addSuccessFlash("Penghuni telah dipindahkan");
            return $this->redirect(['asrama/view-detail-asrama', 'id' => $id_asrama]);
        } else {
            return $this->render('movePenghuniKamar', [
                'model' => $model,
                'asrama' => $asrama,
            ]);
        }
    }

    /**
    * action-id: pilih-asrama
     * action-desc: Memilih asrama untuk memindahkan penghuni
     * Edit an existing DimKamar model.
     * If edit is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPilihAsrama($id, $id_kamar, $id_asrama)
    {
        $asrama = Asrama::find()->where(['asrama_id' => $id_asrama])->one();
        $model = $this->findModel($id);

        return $this->render('pilihAsrama', [
            'model' => $model,
            'asrama' => $asrama,
        ]);
    }

    /**
    * action-id: del
     * action-desc: Menghapus data penghuni kamar
     * Deletes an existing DimKamar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDel($id, $id_kamar)
    {
        $this->findModel($id)->forceDelete();
        \Yii::$app->messenger->addSuccessFlash("Penghuni telah dihapus");

        return $this->redirect(['kamar/view', 'id' => $id_kamar]);
    }

    /*
    * action-id: list-mahasiswa
     * action-desc: Mengambil daftar mahasiswa aktif
    */
    public function actionListMahasiswa($query){
        $data = [];
        $dims = Dim::find()
                    ->select('dim_id,nim,nama')
                    ->where('deleted!=1')
                    ->andWhere(['status_akhir' => 'Aktif'])
                    ->andWhere('nama LIKE :query')
                    ->orWhere('nim LIKE :query')
                    ->addParams([':query' => '%'.$query.'%'])
                    ->limit(10)
                    ->asArray()
                    ->all();
        foreach ($dims as $dim) {
            $dataValue = $dim['nama'] .' ('. $dim['nim'].')';
            $data []  = [
                            'value' => $dim['dim_id'],
                            'data' => $dataValue

                        ];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        echo Json::encode($data);
    }

    /**
     * Finds the DimKamar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DimKamar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DimKamar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

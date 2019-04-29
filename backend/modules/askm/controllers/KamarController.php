<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Kamar;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\DimKamar;
use backend\modules\askm\models\search\KamarSearch;
use backend\modules\askm\models\search\DimKamarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KamarController implements the CRUD actions for Kamar model.
 * controller-id: kamar
 * controller-desc: Controller untuk me-manage data kamar di asrama
 */
class KamarController extends Controller
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
     * Lists all Kamar models.
     * @return mixed
     */
    /*
    * action-id: index
    * action-desc: Display all data
    */
    public function actionIndex($id_asrama)
    {
        $searchModel = new KamarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $asrama = Asrama::find()->where(['asrama_id' => $id_asrama])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'asrama' => $asrama,
        ]);
    }

    /*
    * action-id: reset-all-kamar
    * action-desc: Me-reset semua penghuni kamar
    */
    public function actionResetAllKamar($asrama_id, $confirm=false){
        $models = DimKamar::find()->from('askm_kamar.askm_dim_kamar')->where(['askm_kamar.asrama_id'=>$asrama_id])->all();
        $asrama = Asrama::find()->where(['asrama_id' => $asrama_id])->one();

        if ($confirm) {
            foreach($models as $model){
                $model->forceDelete();
            }
            $asrama->jumlah_mahasiswa = 0;
            $asrama->save();
            \Yii::$app->messenger->addInfoFlash("Penghuni kamar telah dikosongkan");
            return $this->redirect(['kamar/index', 'KamarSearch[asrama_id]' => $asrama_id, 'id_asrama' => $asrama_id]);
        }
        return $this->render('confirmDeleteAll', ['asrama_id' => $asrama_id, 'asrama' => $asrama]);
    }

    /*
    * action-id: reset-kamar
    * action-desc: Me-reset satu penghuni kamar
    */
    public function actionResetKamar($id, $confirm=false){
        $models = DimKamar::find()->where(['kamar_id' => $id])->all();
        $kamar = Kamar::find()->where(['kamar_id' => $id])->one();
        $asrama = Asrama::find()->where(['asrama_id' => $kamar->asrama_id])->one();
        if ($confirm) {
            foreach($models as $model){
                $model->forceDelete();
                $asrama->jumlah_mahasiswa -=1;
                $asrama->save();
            }
            \Yii::$app->messenger->addInfoFlash("Penghuni kamar telah dikosongkan");
            return $this->redirect(['view', 'id' => $id]);
        }
        $k = Kamar::findOne(['kamar_id' => $id, 'deleted' => 0]);
        return $this->render('confirmDelete', ['id' => $id, 'nomor_kamar' => $k->nomor_kamar, 'kamar' => $kamar, 'asrama' => $asrama]);
    }

    /*
    * action-id: del-kamar
    * action-desc: Menghapus kamar
    */
    public function actionDelKamar($id, $confirm=false){
        $model = $this->findModel($id);
        $kamar = Kamar::find()->where(['kamar_id' => $id])->one();
        $asrama = Asrama::find()->where(['asrama_id' => $kamar->asrama_id])->one();
        $dim_kamar = DimKamar::find()->where(['kamar_id' => $id])->all();

        // $kosong = true;
        // foreach ($dim_kamar->dim as $d) {
        //     $kosong = false;
        //     break;
        // }
        if (!empty($dim_kamar)) {
            \Yii::$app->messenger->addWarningFlash("Kamar masih memiliki penghuni, silahkan reset terlebih dahulu!");
            return $this->redirect(['kamar/index', 'KamarSearch[asrama_id]' => $asrama->asrama_id, 'id_asrama' => $asrama->asrama_id]);
        }

        if ($confirm) {
            $model['deleted']=1;
            $model->save();

            \Yii::$app->messenger->addInfoFlash("Kamar ".$model['nomor_kamar']." telah dihapus");
            return $this->redirect(['kamar/index', 'KamarSearch[asrama_id]' => $asrama->asrama_id, 'id_asrama' => $asrama->asrama_id]);
        }

        return $this->render('confirmDeleteKamar', ['id' => $id, 'kamar' => $kamar, 'asrama' => $asrama]);
    }

    /**
     * Displays a single Kamar model.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: view
    * action-desc: Menampilkan kamar
    */
    public function actionView($id)
    {
        $searchModel = new DimKamarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('askm_dim_kamar.kamar_id = '.$id);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kamar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*
    * action-id: add-kamar
    * action-desc: Menambahkan kamar
    */
    public function actionAddKamar($id_asrama)
    {
        $model = new Kamar();
        $asrama = Asrama::find()->where(['asrama_id' => $id_asrama])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kamar_id]);
        } else {
            return $this->render('addKamar', [
                'model' => $model,
                'asrama' => $asrama
            ]);
        }
    }

    /**
     * Updates an existing Kamar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: edit-kamar
    * action-desc: Memperbaharui kamar
    */
    public function actionEditKamar($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->kamar_id]);
        } else {
            return $this->render('editKamar', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Kamar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus kamar
    */
    public function actionDel($id)
    {
        $this->findModel($id)->forceDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Kamar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kamar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kamar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

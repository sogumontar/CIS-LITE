<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\IzinKolaboratif;
use backend\modules\askm\models\search\IzinKolaboratifSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IzinKolaboratifController implements the CRUD actions for IzinKolaboratif model.
 * controller-id: izin-kolaboratif
 * controller-desc: Controller untuk me-manage data izin jam tambahan kolaboratif malam
 */
class IzinKolaboratifController extends Controller
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

    /*
    * action-id: izin-by-baak-index
    * action-desc: Menampilkan request izin kolaboratif by baak
    */
    public function actionIzinByBaakIndex()
    {
        $searchModel = new IzinKolaboratifSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //Set the search/filter parameter
        $status_request = Yii::$app->request->get('status_request_id');
        if($status_request == 1 || $status_request == 2 || $status_request == 3){
            $params['IzinKolaboratifSearch']['status_request_id'] = $status_request;
        }

        return $this->render('IzinByBaakIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_request_id' => $status_request,
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-index
    * action-desc: Menampilkan izin kolaboratif by mahasiswa
    */
    public function actionIzinByMahasiswaIndex()
    {
        $searchModel = new IzinKolaboratifSearch();
        $dataProvider = $searchModel->searchByMahasiswa(Yii::$app->request->queryParams);

        //Set the search/filter parameter
        $status_request = Yii::$app->request->get('status_request_id');
        if($status_request == 1 || $status_request == 2 || $status_request == 3 || $status_request == 4){
            $params['IzinKolaboratifSearch']['status_request_id'] = $status_request;
        }

        return $this->render('IzinByMahasiswaIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_request_id' => $status_request,
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-view
    * action-desc: Menampilkan izin kolaboratif by mahasiswa
    */
    public function actionIzinByMahasiswaView($id)
    {
        $model = IzinKolaboratif::find()->where('deleted!=1')->andWhere(['izin_kolaboratif_id'=>$id])->one();
        if ($model->status_request_id == 2) {
            $status = 'Disetujui oleh';
        }
        elseif ($model->status_request_id == 3) {
            $status = 'Ditolak oleh';
        } else{
            $status = 'Persetujuan';
        }
        return $this->render('IzinByMahasiswaView', [
            'status' => $status,
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: izin-by-baak-view
    * action-desc: Menampilkan izin kolaboratif by baak
    */
    public function actionIzinByBaakView($id)
    {
        $model = IzinKolaboratif::find()->where('deleted!=1')->andWhere(['izin_kolaboratif_id'=>$id])->one();
        if ($model->status_request_id == 2) {
            $status = 'Disetujui oleh';
        }
        elseif ($model->status_request_id == 3) {
            $status = 'Ditolak oleh';
        } else{
            $status = 'Persetujuan';
        }
        return $this->render('IzinByBaakView', [
            'status' => $status,
            'model' => $this->findModel($id),
        ]);
    }

     /*
    * action-id: izin-by-mahasiswa-add
    * action-desc: Menambahkan izin kolaboratif by mahasiswa
    */
    public function actionIzinByMahasiswaAdd()
    {
        $model = new IzinKolaboratif();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-mahasiswa-index']);
        } else {
            return $this->render('IzinByMahasiswaAdd', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-mahasiswa-edit
    * action-desc: Memperbaharui request izin kolaboratif by mahasiswa
    */
    public function actionIzinByMahasiswaEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-mahasiswa-view', 'id' => $model->izin_kolaboratif_id]);
        } else {
            return $this->render('IzinByMahasiswaEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-baak-edit
    * action-desc: Memperbaharui request izin kolaboratif by baak
    */
    public function actionIzinByBaakEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-baak-view', 'id' => $model->izin_kolaboratif_id]);
        } else {
            return $this->render('IzinByBaakEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-baak-approve
    * action-desc: Menyetujui request izin kolaboratif by baak
    */
    public function actionIzinByBaakApprove($id, $id_baak)
    {
        $model=$this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                $model->status_request_id = $id;
                $model->status_request_id = 2;
                $model->baak_id = $id_baak;

                if($model->save()){
                    \Yii::$app->messenger->addSuccessFlash("Izin Tambahan Jam Kolaboratif telah disetujui");
                    return $this->redirect(['izin-by-baak-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah ditolak");
                    return $this->render('IzinByBaakView', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: izin-by-baak-reject
    * action-desc: Menolak request izin kolaboratif by baak
    */
    public function actionIzinByBaakReject($id, $id_baak)
    {
        $model=$this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                $model->status_request_id = $id;
                $model->status_request_id = 3;
                $model->baak_id = $id_baak;

                if($model->save()){
                    \Yii::$app->messenger->addSuccessFlash("Izin tambahan jam kolaboratif ditolak");
                    return $this->redirect(['izin-by-baak-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah disetujui");
                    return $this->render('IzinByBaakView', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: cancel-by-mahasiswa
    * action-desc: Membatalkan request izin kolaboratif by mahasiswa
    */
    public function actionCancelByMahasiswa($id)
    {
        $model = $this->findModel($id);

        if ($model->status_request_id = 1) {
            $model->status_request_id = 4;
            $model->save();

            \Yii::$app->messenger->addSuccessFlash("Request dibatalkan");
            return $this->redirect(['izin-by-mahasiswa-index']);
        } else {
            \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah cancel");
            return $this->render('IzinByMahasiswaView', [
                'model'=>$model
            ]);
        }
    }

    /*
    * action-id: cancel-by-baak
    * action-desc: Membatalkan request izin kolaboratif by baak
    */
    public function actionCancelByBaak($id)
    {
        $model = $this->findModel($id);

        if ($model->status_request_id = 1) {
            $model->status_request_id = 4;
            $model->save();

            \Yii::$app->messenger->addSuccessFlash("Request dibatalkan");
            return $this->redirect(['izin-by-baak-index']);
        } else {
            \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah disetujui atau ditolak");
            return $this->render('IzinByBaakView', [
                'model'=>$model
            ]);
        }
    }

    /**
     * Deletes an existing IzinKolaboratif model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus request izin kolaboratif
    */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IzinKolaboratif model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IzinKolaboratif the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IzinKolaboratif::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

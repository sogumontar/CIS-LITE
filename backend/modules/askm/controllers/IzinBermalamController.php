<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Dim;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\IzinBermalam;
use backend\modules\askm\models\search\IzinBermalamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use mPDF;

/**
 * IzinBermalamController implements the CRUD actions for IzinBermalam model.
  * controller-id: izin-bermalam
 * controller-desc: Controller untuk me-manage data Izin Bermalam Mahasiswa
 */
class IzinBermalamController extends Controller
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
    * action-id: index-admin
     * action-desc: Menampilkan data seluruh izin bermalam by admin
     * Lists all IzinBermalam models.
     * @return mixed
     */
    public function actionIndexAdmin()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('IndexAdmin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: index-mahasiswa
     * action-desc: Menampilkan data seluruh izin bermalam by mahasiswa
    */
    public function actionIndexMahasiswa()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->searchByMahasiswa(Yii::$app->request->queryParams);

        $dataProvider->pagination = ['pageSize' => 5];

        return $this->render('IndexMahasiswa', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-index
    * action-desc: Menampilkan data seluruh izin bermalam by mahasiswa
    */
    public function actionIzinByMahasiswaIndex()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->searchByMahasiswa(Yii::$app->request->queryParams);

        //Set the search/filter parameter
        $status_request = Yii::$app->request->get('status_request_id');
        if($status_request == 1 || $status_request == 2 || $status_request == 3){
            $params['IzinBermalamSearch']['status_request_id'] = $status_request;
        }

        return $this->render('IzinByMahasiswaIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_request_id' => $status_request,
        ]);
    }

    /*
    * action-id: izin-by-admin-index
     * action-desc: Menampilkan data seluruh izin bermalam by admin
    */
    public function actionIzinByAdminIndex()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->searchIbIndex(Yii::$app->request->queryParams);

        //Set the search/filter parameter
        $status_request = Yii::$app->request->get('status_request_id');
        if($status_request == 1 || $status_request == 2 || $status_request == 3 || $status_request == 4){
            $params['IzinBermalamSearch']['status_request_id'] = $status_request;
        }

        $angkatan = Dim::find()->select('thn_masuk')->where('deleted!=1')->andWhere(['status_akhir' => 'Aktif'])->groupBy(['thn_masuk'])->orderBy(['thn_masuk' => SORT_ASC])->all();

        return $this->render('IzinByAdminIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_request_id' => $status_request,
            'angkatan' => $angkatan
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-view
    * action-desc: Menampilkan data seluruh izin bermalam by admin
    */
    public function actionIzinByMahasiswaView($id)
    {
        $model = IzinBermalam::find()->where('deleted!=1')->andWhere(['izin_bermalam_id'=>$id])->one();
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
    * action-id: izin-by-admin-view
     * action-desc: Menampilkan data izin bermalam by admin
    */
    public function actionIzinByAdminView($id)
    {
        $model = IzinBermalam::find()->where('deleted!=1')->andWhere(['izin_bermalam_id'=>$id])->one();
        if ($model->status_request_id == 2) {
            $status = 'Disetujui oleh';
        }
        elseif ($model->status_request_id == 3) {
            $status = 'Ditolak oleh';
        } else{
            $status = 'Persetujuan';
        }
        return $this->render('IzinByAdminView', [
            'status' => $status,
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-add
     * action-desc: Menambahkan data izin bermalam by mahasiswa
    */
    public function actionIzinByMahasiswaAdd()
    {
        $model = new IzinBermalam();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['izin-by-mahasiswa-view', 'id' => $model->izin_bermalam_id]);
        } else {
            return $this->render('IzinByMahasiswaAdd', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-admin-add
     * action-desc: Menambahkan data izin bermalam by admin
    */
    public function actionIzinByAdminAdd()
    {
        $model = new IzinBermalam();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['izin-by-admin-view', 'id' => $model->izin_bermalam_id]);
        } else {
            return $this->render('IzinByAdminAdd', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: list-mahasiswa
     * action-desc: Mengambil daftar mahasiswa
    */
    public function actionListMahasiswa($query){
        $data = [];
        $dims = Dim::find()
                    ->select('dim_id,nim,nama')
                    ->where('deleted!=1')
                    ->andWhere(['status_akhir' => 'Aktif'])
                    ->andWhere('nama LIKE :query OR nim LIKE :query')
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

    /*
    * action-id: izin-by-admin-edit
     * action-desc: Memperbaharui data izin bermalam by admin
    */
    public function actionIzinByAdminEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['izin-by-admin-view', 'id' => $model->izin_bermalam_id]);
        } else {
            return $this->render('IzinByAdminEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-mahasiswa-edit
     * action-desc: Memperbaharui data izin bermalam by mahasiswa
    */
    public function actionIzinByMahasiswaEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['izin-by-mahasiswa-index']);
        } else {
            return $this->render('IzinByMahasiswaEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: cancel-by-mahasiswa
     * action-desc: Membatalkan izin yg telak diajukan mahasiswa by mahasiswa
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
            return $this->render('IzinByMahasiswaIndex', [
                'model'=>$model
            ]);
        }
    }

    /*
    * action-id: cancel-by-admin
     * action-desc: Membatalkan izin yg telak diajukan mahasiswa by admin
    */
    public function actionCancelByAdmin($id)
    {
        $model = $this->findModel($id);

        if ($model->status_request_id = 1) {
            $model->status_request_id = 4;
            $model->save();

            \Yii::$app->messenger->addSuccessFlash("Request dibatalkan");
            return $this->redirect(['izin-by-admin-index']);
        } else {
            \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah cancel");
            return $this->render('IzinByAdminIndex', [
                'model'=>$model
            ]);
        }
    }

    /*
    * action-id: print-ib
     * action-desc: Mencetak izin bermalam ke bentuk pdf
    */
    public function actionPrintIb($id)
    {
        $pdf_content = $this->renderPartial('view-pdf', [
            'model' => $this->findModel($id),
        ]);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($pdf_content);
        $mpdf->Output();
        exit;
    }

    /*
    * action-id: excel
     * action-desc: Mencetak data izin bermalam seluruh mahasiswa ke excel
    */
    public function actionExcel()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('excel', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: approve-by-keasramaan-index
     * action-desc: Menyetujui request izin bermalam dari mahasiswa by keasramaan
    */
    public function actionApproveByKeasramaanIndex($id_ib, $id_keasramaan)
    {
        $model = $this->findModel($id_ib);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if ($model->status_request_id = 1) {
                    $model->status_request_id = 2;
                    $model->keasramaan_id = $id_keasramaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin bermalam telah disetujui");
                    return $this->redirect(['izin-by-admin-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected");
                    return $this->render('IzinByAdminIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);
    }

    /*
    * action-id: approve-all
     * action-desc: Menyetujui semua request izin bermalam dari mahasiswa
    */
    public function actionApproveAll($id_keasramaan)
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $status_request = Yii::$app->request->get('status_request_id');
        $model = IzinBermalam::find()->all();
        $keasramaan = $id_keasramaan;
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                foreach ($model as $m) {
                    if ($m->status_request_id == 1) {
                        $m->status_request_id = 2;
                        $m->keasramaan_id = $keasramaan;
                        $m->save();
                    }
                }

                if($status_request == 1 || $status_request == 2 || $status_request == 3){
                    $params['IzinBermalamSearch']['status_request_id'] = $status_request;
                }

                if ($m->save()) {
                    \Yii::$app->messenger->addSuccessFlash("Semua izin bermalam telah disetujui");
                    return $this->redirect(['izin-by-admin-index']);
                } else {
                    return $this->render('IzinByAdminIndex', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'status_request_id' => $status_request,
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);
    }

    /*
    * action-id: approve-selected
     * action-desc: Menyetujui request izin bermalam yang dipilih dari mahasiswa by keasramaan
    */
    public function actionApproveSelected($id_keasramaan)
    {
        $model = IzinBermalam::find()->andWhere('deleted' != 1)->andWhere(['in', 'izin_bermalam_id', Yii::$app->request->post()['keylist']])->all();
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if (Yii::$app->request->post()) {
                    foreach ($model as $m) {
                        $m->status_request_id = 2;
                        $m->keasramaan_id = $id_keasramaan;
                        $m->save();
                    }

                    if ($m->save()) {
                        \Yii::$app->messenger->addSuccessFlash("Izin bermalam telah disetujui");
                        return $this->redirect(['izin-by-admin-index']);
                    } else {
                        return $this->render('IzinByAdminIndex');
                    }
                } else {
                    return $this->render('IzinByAdminIndex');
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-by-keasramaan-index
     * action-desc: Menolak request izin bermalam dari mahasiswa by keasramaan
    */
    public function actionRejectByKeasramaanIndex($id_ib, $id_keasramaan)
    {
        $model = $this->findModel($id_ib);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if ($model->status_request_id = 1) {
                    $model->status_request_id = 3;
                    $model->keasramaan_id = $id_keasramaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin bermalam telah ditolak");
                    return $this->redirect(['izin-by-admin-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected");
                    return $this->render('IzinByAdminIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);
    }

    /*
    * action-id: reject-all
     * action-desc: Menolak semua request izin bermalam
    */
    public function actionRejectAll($id_keasramaan)
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $status_request = Yii::$app->request->get('status_request_id');
        $model = IzinBermalam::find()->all();
        $m = IzinBermalam::find()->andWhere(['status_request_id' => 1])->all();
        $keasramaan = $id_keasramaan;
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                foreach ($model as $m) {
                    while ($m->status_request_id == 1) {
                        $m->status_request_id = 3;
                        $m->keasramaan_id = $keasramaan;
                        $m->save();
                    }
                }

                if($status_request == 1 || $status_request == 2 || $status_request == 3){
                    $params['IzinBermalamSearch']['status_request_id'] = $status_request;
                }

                if ($m->save()) {
                    \Yii::$app->messenger->addSuccessFlash("Semua izin bermalam telah ditolak");
                    return $this->redirect(['izin-by-admin-index']);
                } else {
                    return $this->render('IzinByAdminIndex', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'status_request_id' => $status_request,
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-selected
     * action-desc: Menolak izin bermalam yang dipilih
    */
    public function actionRejectSelected($id_keasramaan)
    {
        $model = IzinBermalam::find()->andWhere('deleted' != 1)->andWhere(['in', 'izin_bermalam_id', Yii::$app->request->post()['keylist']])->all();
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if (Yii::$app->request->post()) {
                    foreach ($model as $m) {
                        $m->status_request_id = 3;
                        $m->keasramaan_id = $id_keasramaan;
                        $m->save();
                    }

                    if ($m->save()) {
                        \Yii::$app->messenger->addSuccessFlash("Izin bermalam telah ditolak");
                        return $this->redirect(['izin-by-admin-index']);
                    } else {
                        return $this->render('IzinByAdminIndex');
                    }
                } else {
                    return $this->render('IzinByAdminIndex');
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /**
    * action-id: del
     * action-desc: Menghapus data izin bermalam
     * Deletes an existing IzinBermalam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IzinBermalam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IzinBermalam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IzinBermalam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

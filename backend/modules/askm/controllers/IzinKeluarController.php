<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\IzinKeluar;
use backend\modules\askm\models\search\IzinKeluarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap\ActiveForm;

/**
 * IzinKeluarController implements the CRUD actions for IzinKeluar model.
   * controller-id: izin-keluar
 * controller-desc: Controller untuk me-manage data Izin Bermalam Mahasiswa
 */
class IzinKeluarController extends Controller
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
    * action-id: ika-by-mahasiswa-index
     * action-desc: Menampilkan request izin keluar kampus dari mahasiswa by mahasiswa
    */
    public function actionIkaByMahasiswaIndex()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->searchByMahasiswa(Yii::$app->request->queryParams);

        return $this->render('IkaByMahasiswaIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: ika-by-baak-index
     * action-desc: Menampilkan request izin keluar kampus dari mahasiswa by baak
    */
    public function actionIkaByBaakIndex()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->searchIkIndex(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = [
            'status_request_baak' => SORT_ASC,
            'status_request_keasramaan' => SORT_DESC,
            'status_request_dosen_wali' => SORT_DESC,
            'created_at' => SORT_ASC,
        ];

        return $this->render('IkaByBaakIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: ika-by-dosen-index
     * action-desc: Menampilkan request izin keluar kampus dari mahasiswa by dosen wali
    */
    public function actionIkaByDosenIndex()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->searchByDosenWali(Yii::$app->request->queryParams);

        return $this->render('IkaByDosenIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: ika-by-keasramaan-index
    * action-desc: Menampilkan request izin keluar kampus dari mahasiswa by keasramaan
    */
    public function actionIkaByKeasramaanIndex()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->searchIkIndex(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = [
            'status_request_keasramaan' => SORT_ASC,
            'status_request_baak' => SORT_ASC,
            'status_request_dosen_wali' => SORT_DESC,
            'created_at' => SORT_ASC,
        ];

        return $this->render('IkaByKeasramaanIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: ika-by-kemahasiswaan-index
    * action-desc: Menampilkan request izin keluar kampus dari mahasiswa by kemahasiswaan
    */
    public function actionIkaByKemahasiswaanIndex()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->searchIkIndex(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = [
            'status_request_dosen_wali' => SORT_ASC,
            'status_request_keasramaan' => SORT_ASC,
            'status_request_baak' => SORT_ASC,
            'created_at' => SORT_ASC,
        ];

        return $this->render('IkaByKemahasiswaanIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: ika-by-mahasiswa-view
    * action-desc: Menampilkan data izin keluar by mahasiswa
    */
    public function actionIkaByMahasiswaView($id)
    {
        return $this->render('IkaByMahasiswaView', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: ika-by-dosen-view
    * action-desc: Menampilkan data izin keluar by dosen
    */
    public function actionIkaByDosenView($id)
    {
        return $this->render('IkaByDosenView', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: ika-by-keasramaan-view
    * action-desc: Menampilkan data izin keluar by keasramaan
    */
    public function actionIkaByKeasramaanView($id)
    {
        return $this->render('IkaByKeasramaanView', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: ika-by-kemahasiswaan-view
    * action-desc: Menampilkan data izin keluar by kemahasiswaan
    */
    public function actionIkaByKemahasiswaanView($id)
    {
        return $this->render('IkaByKemahasiswaanView', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: ika-by-baak-view
    * action-desc: Menampilkan data izin keluar by baak
    */
    public function actionIkaByBaakView($id)
    {
        return $this->render('IkaByBaakView', [
            'model' => $this->findModel($id),
        ]);
    }

    /*
    * action-id: ika-by-mahasiswa-add
    * action-desc: Menambahkan data izin keluar by mahasiswa
    */
    public function actionIkaByMahasiswaAdd()
    {
        $model = new IzinKeluar();

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['ika-by-mahasiswa-view', 'id' => $model->izin_keluar_id]);
        } else {
            return $this->render('IkaByMahasiswaAdd', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: ika-by-mahasiswa-edit
    * action-desc: Memperbaharui data izin keluar by mahasiswa
    */
    public function actionIkaByMahasiswaEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }
            if($model->save())
                return $this->redirect(['ika-by-mahasiswa-view', 'id' => $model->izin_keluar_id]);
        } else {
            return $this->render('IkaByMahasiswaEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: ika-by-mahasiswa-cancel
    * action-desc: Membatalkan data izin keluar by mahasiswa
    */
    public function actionIkaByMahasiswaCancel($id)
    {
        $model = $this->findModel($id);

        if ($model->status_request_keasramaan = 1) {
            $model->status_request_keasramaan = 4;
            $model->status_request_dosen_wali = 4;
            $model->status_request_baak = 4;
            $model->save();

            \Yii::$app->messenger->addSuccessFlash("Izin keluar telah dibatalkan");
            return $this->redirect(['ika-by-mahasiswa-index']);
        } else {
            \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
            return $this->render('IzinByMahasiswaIndex', [
                'model'=>$model
            ]);
        }
    }

    /*
    * action-id: approve-by-maha-asra
    * action-desc: Menyetujui request izin keluar by keasramaan oleh kemahasiswaan
    */
    public function actionApproveByMahaAsra($id, $id_kemahasiswaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_kemahasiswaan) {
                if ($model->status_request_keasramaan = 1) {
                    $model->status_request_keasramaan = 2;
                    $model->keasramaan_id = $id_kemahasiswaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah disetujui");
                    return $this->redirect(['ika-by-kemahasiswaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKemahasiswaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: approve-by-kemahasiswaan-dosen
    * action-desc: Menyetujui request izin keluar by dosen wali oleh kemahasiswaan
    */
    public function actionApproveByKemahasiswaanDosen($id, $id_kemahasiswaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_kemahasiswaan) {
                if ($model->status_request_dosen_wali = 1) {
                    $model->status_request_dosen_wali = 2;
                    $model->dosen_wali_id = $id_kemahasiswaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah disetujui");
                    return $this->redirect(['ika-by-kemahasiswaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKemahasiswaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: approve-by-keasramaan
    * action-desc: Menyetujui request izin keluar by keasramaan
    */
    public function actionApproveByKeasramaan($id, $id_keasramaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if ($model->status_request_keasramaan = 1) {
                    $model->status_request_keasramaan = 2;
                    $model->keasramaan_id = $id_keasramaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah disetujui");
                    return $this->redirect(['ika-by-keasramaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKeasramaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: approve-by-dosen
    * action-desc: Menyetujui request izin keluar by dosen
    */
    public function actionApproveByDosen($id, $id_dosen, $redback=null)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_dosen) {
                if ($model->status_request_dosen_wali = 1) {
                    $model->status_request_dosen_wali = 2;
                    $model->dosen_wali_id = $id_dosen;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah disetujui");
                    if(is_null($redback))
                        return $this->redirect(['ika-by-dosen-index']);
                    else
                        return $this->redirect(Yii::$app->request->referrer);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByDosenIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: approve-by-baak
    * action-desc: Menyetujui request izin keluar by baak
    */
    public function actionApproveByBaak($id, $id_baak)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                if ($model->status_request_baak = 1) {
                    $model->status_request_baak = 2;
                    $model->baak_id = $id_baak;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah disetujui");
                    return $this->redirect(['ika-by-baak-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByBaakIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-by-keasramaan
    * action-desc: Menolak request izin keluar by keasramaan
    */
    public function actionRejectByKeasramaan($id, $id_keasramaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_keasramaan) {
                if ($model->status_request_keasramaan = 1) {
                    $model->status_request_keasramaan = 3;
                    $model->status_request_baak = 3;
                    $model->keasramaan_id = $id_keasramaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah ditolak");
                    return $this->redirect(['ika-by-keasramaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKeasramaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);
    }

    /*
    * action-id: reject-by-dosen
    * action-desc: Menolak request izin keluar by dosen wali
    */
    public function actionRejectByDosen($id, $id_dosen, $redback=null)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_dosen) {
                if ($model->status_request_dosen_wali = 1) {
                    $model->status_request_dosen_wali = 3;
                    $model->status_request_keasramaan = 3;
                    $model->status_request_baak = 3;
                    $model->dosen_wali_id = $id_dosen;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah ditolak");
                    if(is_null($redback))
                        return $this->redirect(['ika-by-dosen-index']);
                    else
                        return $this->redirect(Yii::$app->request->referrer);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByDosenIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-by-baak
    * action-desc: Menolak request izin keluar by baak
    */
    public function actionRejectByBaak($id, $id_baak)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                if ($model->status_request_baak = 1) {
                    $model->status_request_baak = 3;
                    $model->baak_id = $id_baak;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah ditolak");
                    return $this->redirect(['ika-by-baak-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByBaakIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-by-kemahasiswaan-dosen
    * action-desc: Menolak request izin keluar by dosen wali oleh kemahasiswaan
    */
    public function actionRejectByKemahasiswaanDosen($id, $id_kemahasiswaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_kemahasiswaan) {
                if ($model->status_request_baak = 1) {
                    $model->status_request_dosen_wali = 3;
                    $model->status_request_baak = 3;
                    $model->status_request_keasramaan = 3;
                    $model->dosen_wali_id = $id_kemahasiswaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah ditolak");
                    return $this->redirect(['ika-by-kemahasiswaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKemahasiswaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /*
    * action-id: reject-by-maha-asra
    * action-desc: Menolak request izin keluar by keasramaan oleh kemahasiswaan
    */
    public function actionRejectByMahaAsra($id, $id_kemahasiswaan)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_kemahasiswaan) {
                if ($model->status_request_keasramaan = 1) {
                    $model->status_request_keasramaan = 3;
                    $model->status_request_baak = 3;
                    $model->keasramaan_id = $id_kemahasiswaan;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin keluar telah ditolak");
                    return $this->redirect(['ika-by-kemahasiswaan-index']);
                } else {
                    \Yii::$app->messenger->addErrorFlash("Request tidak bisa diubah bila status sudah Rejected atau Canceled");
                    return $this->render('IzinByKemahasiswaanIndex', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /**
     * Deletes an existing IzinKeluar model.
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
     * Finds the IzinKeluar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IzinKeluar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IzinKeluar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

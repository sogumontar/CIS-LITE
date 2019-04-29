<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\IzinRuangan;
use backend\modules\askm\models\search\IzinRuanganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IzinRuanganController implements the CRUD actions for IzinRuangan model.
 * controller-id: izin-ruangan
 * controller-desc: Controller untuk me-manage data izin pemakaian ruangan
 */
class IzinRuanganController extends Controller
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
     * Lists all IzinRuangan models.
     * @return mixed
     */
    /*
    * action-id: index-mahasiswa
    * action-desc: Menampilkan semua request izin ruangan by mahasiswa
    */
    public function actionIndexMahasiswa()
    {
        $events = IzinRuangan::find()->all();

        $task = [];
        foreach ($events as $eve) {
            if ($eve->status_request_id == 2) {
                $event = new \yii2fullcalendar\models\Event();
                $event->id = $eve->izin_ruangan_id;
                $event->title = $eve->lokasi['name'].' - '.$eve->desc;
                $event->url = '/cis-lite/backend/web/index.php/askm/izin-ruangan/izin-by-mahasiswa-view?id='.$eve->izin_ruangan_id.'';
                $event->start = $eve->rencana_mulai;
                $event->end = $eve->rencana_berakhir;
                $task[] = $event;
            }
        }

        return $this->render('IndexMahasiswa', [
            'events' => $task,
        ]);
    }

    /*
    * action-id: index-baak
    * action-desc: Menampilkan semua request izin ruangan by baak
    */
    public function actionIndexBaak()
    {
        $events = IzinRuangan::find()->all();

        $task = [];
        foreach ($events as $eve) {
            if ($eve->status_request_id == 2) {
                $event = new \yii2fullcalendar\models\Event();
                $event->id = $eve->izin_ruangan_id;
                $event->title = $eve->lokasi['name'].' - '.$eve->desc;
                $event->url = '/cis-lite/backend/web/index.php/askm/izin-ruangan/izin-by-baak-view?id='.$eve->izin_ruangan_id.'';
                $event->start = $eve->rencana_mulai;
                $event->end = $eve->rencana_berakhir;
                $task[] = $event;
            }
        }

        return $this->render('IndexBaak', [
            'events' => $task,
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-index
    * action-desc: Menampilkan semua request izin ruangan by mahasiswa
    */
    public function actionIzinByMahasiswaIndex()
    {
        $searchModel = new IzinRuanganSearch();
        $dataProvider = $searchModel->searchByMahasiswa(Yii::$app->request->queryParams);

        return $this->render('IzinByMahasiswaIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: izin-by-baak-index
    * action-desc: Menampilkan semua request izin ruangan by baak
    */
    public function actionIzinByBaakIndex()
    {
        $searchModel = new IzinRuanganSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $status_request = Yii::$app->request->get('status_request_id');
        if($status_request == 1 || $status_request == 2 || $status_request == 3 || $status_request == 4){
            $params['IzinRuanganSearch']['status_request_id'] = $status_request;
        }

        return $this->render('IzinByBaakIndex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_request_id' => $status_request,
        ]);
    }

    /*
    * action-id: izin-by-mahasiswa-view
    * action-desc: Menampilkan request izin ruangan by mahasiswa
    */
    public function actionIzinByMahasiswaView($id)
    {
        $model = IzinRuangan::find()->where('deleted!=1')->andWhere(['izin_ruangan_id'=>$id])->one();
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
    * action-desc: Menampilkan request izin ruangan by baak
    */
    public function actionIzinByBaakView($id)
    {
        $model = IzinRuangan::find()->where('deleted!=1')->andWhere(['izin_ruangan_id'=>$id])->one();
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
    * action-desc: Menambahkan request izin ruangan by mahasiswa
    */
    public function actionIzinByMahasiswaAdd()
    {
        $model = new IzinRuangan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-mahasiswa-view', 'id' => $model->izin_ruangan_id]);
        } else {
            return $this->render('IzinByMahasiswaAdd', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: izin-by-mahasiswa-edit
    * action-desc: Memperbaharui request izin ruangan by mahasiswa
    */
    public function actionIzinByMahasiswaEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-mahasiswa-view', 'id' => $model->izin_ruangan_id]);
        } else {
            return $this->render('IzinByMahasiswaEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: cancel-by-mahasiswa
    * action-desc: Membatalkan request izin ruangan by mahasiswa
    */
    public function actionCancelByMahasiswa($id)
    {
        $model = $this->findModel($id);

        if ($model->status_request_id = 1) {
            $model->status_request_id = 4;
            $model->save();

            \Yii::$app->messenger->addSuccessFlash("Izin telah dibatalkan");
            return $this->redirect(['index-mahasiswa']);
        } else {
            return $this->render('IndexMahasiswa', [
                'model'=>$model
            ]);
        }
    }

    /*
    * action-id: izin-by-baak-edit
    * action-desc: Memperbaharui request izin ruangan by baak
    */
    public function actionIzinByBaakEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['izin-by-baak-view', 'id' => $model->izin_ruangan_id]);
        } else {
            return $this->render('IzinByBaakEdit', [
                'model' => $model,
            ]);
        }
    }

    /*
    * action-id: approve-by-baak
    * action-desc: Menyetujui request izin ruangan
    */
    public function actionApproveByBaak($id, $id_baak)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                if ($model->status_request_id = 1) {
                    $model->status_request_id = 2;
                    $model->baak_id = $id_baak;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin telah disetujui");
                    return $this->redirect(['index-baak']);
                } else {
                    return $this->render('IndexMahasiswa', [
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
    * action-desc: Menolak request izin ruangan
    */
    public function actionRejectByBaak($id, $id_baak)
    {
        $model = $this->findModel($id);
        $pegawai = Pegawai::find()->where('deleted!=1')->all();

        foreach ($pegawai as $p) {
            if ($p->pegawai_id == $id_baak) {
                if ($model->status_request_id = 1) {
                    $model->status_request_id = 3;
                    $model->baak_id = $id_baak;
                    $model->save();

                    \Yii::$app->messenger->addSuccessFlash("Izin telah ditolak");
                    return $this->redirect(['index-baak']);
                } else {
                    return $this->render('IndexBaak', [
                        'model'=>$model
                    ]);
                }
            }
        }

        \Yii::$app->messenger->addWarningFlash("Anda belum terdaftar di data kepegawaian IT Del, hubungi HRD untuk memasukkan data Pegawai anda");
        return $this->redirect(['izin-by-admin-index']);

    }

    /**
     * Deletes an existing IzinRuangan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus request izin ruangan
    */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IzinRuangan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IzinRuangan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IzinRuangan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

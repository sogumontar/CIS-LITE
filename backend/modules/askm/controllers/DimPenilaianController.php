<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Registrasi;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Ta;
use backend\modules\askm\models\SemTa;
use backend\modules\askm\models\Dim;
use backend\modules\askm\models\DimPenilaian;
use backend\modules\askm\models\DimPelanggaran;
use backend\modules\askm\models\search\DimPenilaianSearch;
use backend\modules\askm\models\search\DimPelanggaranSearch;
use backend\modules\askm\models\search\PoinKebaikanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;

/**
 * DimPenilaianController implements the CRUD actions for DimPenilaianController model.
 * controller-id: dim-penilaian
 * controller-desc: Controller untuk me-manage data perilaku mahasiswa
 */
class DimPenilaianController extends Controller
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
     * Lists all DimPenilaian models.
     * action-id: index
     * action-desc: Display all data
     * Lists all DimPenilaian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DimPenilaianSearch();
        $searchModel->ta = \Yii::$app->appConfig->get('tahun_ajaran', true);
        $searchModel->sem_ta = \Yii::$app->appConfig->get('semester_tahun_ajaran', true);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['dimx_dim.thn_masuk' => SORT_DESC]);

        $prodi = Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
                    return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
                }])->all();
        $dosen_wali = Registrasi::find()->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->groupBy(['dosen_wali_id'])->joinWith(['dosenWali' => function($query){
                $query->orderBy(['hrdx_pegawai.nama' => SORT_ASC]);
        }])->all();
        $asrama = Asrama::find()->where('deleted!=1')->orderBy('name')->all();
        $angkatan = Dim::find()->select('thn_masuk')->where('deleted!=1')->andWhere(['status_akhir' => 'Aktif'])->groupBy(['thn_masuk'])->orderBy(['thn_masuk' => SORT_ASC])->all();
        $ta = Ta::find()->where('deleted!=1')->orderBy(['nama' => SORT_DESC])->all();
        $sem_ta = SemTa::find()->where('deleted!=1')->orderBy('sem_ta')->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'prodi' => $prodi,
            'dosen_wali' => $dosen_wali,
            'asrama' => $asrama,
            'ta' => $ta,
            'sem_ta' => $sem_ta,
            'angkatan' => $angkatan,
        ]);
    }

    /**
     * Lists single DimPenilaian models.
     * @return mixed
     */
    /*
    * action-id: view
    * action-desc: Display Single Data
    */
    public function actionView($id)
    {
        $searchModel = new DimPelanggaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['askm_dim_pelanggaran.penilaian_id' => $id]);

        $searchModel2 = new PoinKebaikanSearch();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
        $dataProvider2->query->andWhere(['askm_poin_kebaikan.penilaian_id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
        ]);
    }

    /**
     * Add new DimPenilaian models.
     * @return mixed
     */
    /*
    * action-id: add
    * action-desc: Add Single Data
    */
    public function actionAdd()
    {
        $model = new DimPenilaian();

        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['view', 'id' => $model->penilaian_id]);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Generate single existing DimPenilaian models.
     * @return mixed
     */
    /*
    * action-id: generate
    * action-desc: Generate single existing data
    */
    public function actionGenerate()
    {
        $all_dim = Dim::find()->where('deleted!=1')->andWhere(['status_akhir' => 'Aktif'])->all();

        ini_set('max_execution_time', 1500);
        foreach ($all_dim as $dim) {
            $model1 = DimPenilaian::find()->where('deleted!=1')->andWhere(['dim_id' => $dim->dim_id, 'ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->one();
            if(empty($model1)){
                $model = new DimPenilaian();
                $model->dim_id = $dim->dim_id;
                $model->ta = \Yii::$app->appConfig->get('tahun_ajaran', true);
                $model->sem_ta = \Yii::$app->appConfig->get('semester_tahun_ajaran', true);
                $model->save();
            }
        }
        ini_set('max_execution_time', 30);

        return $this->redirect(['index']);
    }

    /**
     * Print out existing DimPenilaian models.
     * @return mixed
     */
    /*
    * action-id: print-nilai
    * action-desc: Print single existing data
    */
    public function actionPrintNilai($id){
        $pelanggaran = DimPelanggaran::find()->where('deleted!=1')->andWhere(['penilaian_id' => $id])->andWhere('status_pelanggaran!=1')->all();

        $pdf_content = $this->renderPartial('nilai-pdf', [
            'model' => $this->findModel($id),
            'pelanggaran' => $pelanggaran,
        ]);

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->showImageErrors = true;
        $mpdf->WriteHTML($pdf_content);
        $mpdf->Output();
        exit;
    }

    /**
     * Edit existing DimPenilaian models.
     * @return mixed
     */
    /*
    * action-id: edit
    * action-desc: Edit single existing data
    */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->penilaian_id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DimPenilaian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus penilaian
    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DimPenilaian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DimPenilaian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DimPenilaian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

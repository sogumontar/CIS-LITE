<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\LogMahasiswa;
use backend\modules\askm\models\search\LogMahasiswaSearch;
use backend\modules\askm\models\search\IzinBermalamSearch;
use backend\modules\askm\models\search\IzinKeluarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use backend\modules\krkm\models\Prodi;
use backend\modules\adak\models\Registrasi;
use backend\modules\askm\models\Asrama;
use yii\data\Sort;

/**
 * LogMahasiswaController implements the CRUD actions for LogMahasiswa model.
  * controller-id: log-mahasiswa
 * controller-desc: Controller untuk me-manage data log mahasiswa
 */
class LogMahasiswaController extends Controller
{
    public function behaviors()
    {
        return [
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
     * Lists all LogMahasiswa models.
     * @return mixed
     */
    /*
    * action-id: index-luar
    * action-desc: Display All Data
    */
    public function actionIndexLuar()
    {
        $searchModel = new LogMahasiswaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('askm_log_mahasiswa.tanggal_masuk is NULL');

        return $this->render('indexLuar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: index
    * action-desc: Menampilkan mahasiswa yang berada di luar kampus
    */
    public function actionIndex()
    {
        $searchModelLog = new LogMahasiswaSearch();
        $dataProviderLog = $searchModelLog->searchCombinedLog(Yii::$app->request->queryParams);
        $dataProviderLog->sort->defaultOrder = ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC];
        $dataProviderLog->pagination = false;

        $searchModelIb = new IzinBermalamSearch();
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_nama'])){
            $searchModelIb->dim_nama = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_nama'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_prodi'])){
            $searchModelIb->dim_prodi = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_prodi'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_dosen'])){
            $searchModelIb->dim_dosen = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_dosen'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_asrama'])){
            $searchModelIb->dim_asrama = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_asrama'];
        }
        $dataProviderIb = $searchModelIb->searchCombinedLog(Yii::$app->request->queryParams);
        $dataProviderIb->sort->defaultOrder = ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC];
        $dataProviderIb->pagination = false;

        $searchModelIk = new IzinKeluarSearch();
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_nama'])){
            $searchModelIk->dim_nama = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_nama'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_prodi'])){
            $searchModelIk->dim_prodi = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_prodi'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_dosen'])){
            $searchModelIk->dim_dosen = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_dosen'];
        }
        if(isset(Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_asrama'])){
            $searchModelIk->dim_asrama = Yii::$app->request->queryParams['LogMahasiswaSearch']['dim_asrama'];
        }
        $dataProviderIk = $searchModelIk->searchCombinedLog(Yii::$app->request->queryParams);
        $dataProviderIk->sort->defaultOrder = ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC];
        $dataProviderIk->pagination = false;

        $data = array_merge($dataProviderLog->getModels(), $dataProviderIb->getModels(), $dataProviderIk->getModels());

        $data2 = array();
        foreach($data as $d){
            $ket = '-';
            if(!isset($d->izin_bermalam_id) && !isset($d->izin_keluar_id)){
                $tanggal = $d->tanggal_masuk;
                $day = date('w', strtotime($tanggal));//Yii::$app->formatter->asDate($tanggal,'EEEE');
                $dayOut = date('w', strtotime($d->tanggal_keluar));//Yii::$app->formatter->asDate($d->tanggal_keluar,'EEEE');
                $time = date('H:i', strtotime($tanggal));//Yii::$app->formatter->asTime($tanggal,'HH:mm');
                $time2 = date('H:i', strtotime($d->tanggal_keluar));
                if(!is_null($tanggal)){
                    if($day==0||$day==1||$day==2||$day==3||$day==4){
                        if($day==$dayOut){
                            if($time > '22:00'){
                                $ket = "Besok hari biasa/weekdays masuk lebih dari jam 22.00";
                            }
                        }
                        // else{
                        //     $ket = "Beda hari waktu keluar dan masuk";
                        // }
                    }
                    else if($day==5||$day==6){
                        if($day==$dayOut){
                            if($time > '23:00'){
                                $ket = "Besok akhir pekan/weekend masuk lebih dari jam 23.00";
                            }
                        }
                        // else{
                        //     $ket = "Beda hari waktu keluar dan masuk";
                        // }
                    }
                }else{
                    if($day==1||$day==2||$day==3||$day==4||$day==5){
                        if($time2 < '17:00'){
                            $ket = "Hari biasa/weekdays keluar kurang dari jam 17.00";
                        }
                    }
                }
            }else{
                $waktu_keluar = date('Y-m-d H:i:s', strtotime($d->realisasi_berangkat));//Yii::$app->formatter->asDate($d->realisasi_berangkat,'EEEE');
                $waktu_kembali = date('Y-m-d H:i:s', strtotime($d->realisasi_kembali));//Yii::$app->formatter->asDate($d->realisasi_kembali,'EEEE');
                $rencana_keluar = date('Y-m-d H:i:s', strtotime($d->rencana_berangkat));//Yii::$app->formatter->asDate($d->rencana_berangkat,'EEEE');
                $rencana_kembali = date('Y-m-d H:i:s', strtotime($d->rencana_kembali));//Yii::$app->formatter->asDate($d->rencana_kembali,'EEEE');
                if($waktu_kembali != null && $waktu_kembali > $rencana_kembali){
                    $ket = "Waktu kembali lebih lama dari rencana kembali";
                }else if($waktu_keluar < $rencana_keluar){
                    $ket = "Waktu keluar lebih awal dari rencana keluar";
                }
            }

            $data2[] = (object)[
                'dim_id' => $d->dim->dim_id,
                'nama' => $d->dim->nama,
                'waktu_keluar' => $d->realisasi_berangkat,
                'waktu_kembali' => $d->realisasi_kembali,
                'jenis_log' => isset($d->izin_bermalam_id)?'Izin Bermalam (IB)':(isset($d->izin_keluar_id)?'Izin Keluar (IK)':'Jam Keluar-Masuk'),
                'rencana_keluar' => (isset($d->izin_bermalam_id) || isset($d->izin_keluar_id))?$d->rencana_berangkat:null,
                'rencana_kembali' => (isset($d->izin_bermalam_id) || isset($d->izin_keluar_id))?$d->rencana_kembali:null,
                'updated_at' => $d->updated_at,
                'ket' => $ket
            ];
        }

        usort($data2, function($a, $b) {
            $retval = -($a->updated_at <=> $b->updated_at);
            //$retval .= -($a['waktu_keluar'] <=> $b['waktu_keluar']);
            return $retval;
        });

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data2,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // foreach($dataProvider->getModels() as $d){
        //     echo $d['nama'].' - '.$d['waktu_keluar'].' - '.$d['waktu_kembali'].' - '.$d['jenis_log'].'<br />';
        // }
        // die;
        $prodi = Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
                    return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
                }])->all();
        $dosen_wali = Registrasi::find()->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->joinWith(['dosenWali' => function($query){
                $query->orderBy(['hrdx_pegawai.nama' => SORT_ASC]);
        }])->groupBy(['dosen_wali_id'])->all();
        $asrama = Asrama::find()->where('deleted!=1')->orderBy('name')->all();
        return $this->render('index', [
            'searchModel' => $searchModelLog,
            'dataProvider' => $dataProvider,
            'prodi' => $prodi,
            'dosen_wali' => $dosen_wali,
            'asrama' => $asrama
        ]);
    }

    /*
    * action-id: index-dalam
    * action-desc: Menampilkan mahasiswa yang berada di dalam kampus
    */
    public function actionIndexDalam()
    {
        $searchModel = new LogMahasiswaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('askm_log_mahasiswa.tanggal_masuk is not NULL')->groupBy('dim_id');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: index-ib
    * action-desc: Menampilkan mahasiswa yang berada di dalam kampus
    */
    public function actionIndexIb()
    {
        $searchModel = new IzinBermalamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('askm_izin_bermalam.realisasi_kembali is NULL')->andWhere('askm_izin_bermalam.realisasi_berangkat is NOT NULL')->groupBy('dim_id');

        return $this->render('indexIb', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    * action-id: index-ik
    * action-desc: Menampilkan mahasiswa yang berada di dalam kampus
    */
    public function actionIndexIk()
    {
        $searchModel = new IzinKeluarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('askm_izin_keluar.realisasi_kembali is NULL')->andWhere('askm_izin_keluar.realisasi_berangkat is NOT NULL')->groupBy('dim_id');

        // echo '<pre>';
        // print_r($dataProvider->models);
        // die;

        return $this->render('indexIk', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LogMahasiswa model.
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
     * Creates a new LogMahasiswa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new LogMahasiswa();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->log_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LogMahasiswa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->log_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LogMahasiswa model.
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
     * Finds the LogMahasiswa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LogMahasiswa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LogMahasiswa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

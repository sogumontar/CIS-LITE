<?php

namespace backend\modules\cist\controllers;

use Yii;
use mPDF;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\search\SuratTugasSearch;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\Pejabat;
use backend\modules\cist\models\SuratTugasFile;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\cist\models\LaporanSuratTugas;
use backend\modules\cist\models\AtasanSuratTugas;
use backend\modules\baak\models\InstApiModel;
use backend\modules\cist\models\StrukturJabatan;
use backend\modules\cist\models\JenisSurat;
use backend\modules\cist\models\Status;
use backend\modules\cist\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;


/**
 * SuratTugasController implements the CRUD actions for SuratTugas model.
 * controller-id: surat-tugas
 * controller-desc: Controller untuk me-manage data Surat Tugas untuk Pegawai
 */
class SuratTugasController extends Controller
{
    public $to = array("dosenstaff@del.ac.id");
    
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

    public $pegawai;
    public function beforeAction($action)
    {
        $this->pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
        if(empty($this->pegawai) || !isset($this->pegawai) || is_null($this->pegawai)){
            \Yii::$app->messenger->addErrorFlash('Data pegawai anda belum terdaftar, harap menghubungi HRD !');
            $this->redirect(\Yii::$app->request->referrer);
            return false;
        }
        return true;
    }

    /**
     * action-id: dashboard-surat-tugas
     * action-desc: Show surat tugas module dashboard
     */
    public function actionDashboardSuratTugas(){
        $searchModel = new SuratTugasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('Dashboard', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * action-id: index-pegawai
     * action-desc: Display all surat tugas of specific pegawai
     */
    public function actionIndexPegawai()
    {
        $arraySuratTugasId = SuratTugas::getSuratTugas(Yii::$app->user->identity->id);

        $searchModel = new SuratTugasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['in', 'surat_tugas_id', $arraySuratTugasId]);  
        $status = Status::find()->where(['not in', 'status_id', [7, 8, 9, 10]])->andWhere('deleted!=1')->all();

        return $this->render('IndexPegawai', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }

    /**
     * action-id: index-hrd
     * action-desc: Display all surat tugas that have been confirmed or published
     */
    public function actionIndexHrd(){
        $searchModel = new SuratTugasSearch();
        $dataProvider = $searchModel->searchHrd(Yii::$app->request->queryParams);
        // $dataProvider->query->where("name = 6 or name = 3")->orderBy(['created_at' => SORT_DESC]);        
        // $dataProvider->sort->defaultOrder = ['status_id' => SORT_ASC, 'created_at' => SORT_ASC];
        $jenisSurat = JenisSurat::find()->where('deleted != 1')->all();
        $status = Status::find()->where(['in', 'status_id', [6, 3]])->andWhere('deleted!=1')->all();

        return $this->render('IndexHrd', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenisSurat' => $jenisSurat,
            'status' => $status,
        ]);
    }

    /**
     * action-id: index-wr
     * action-desc: Display all surat tugas that have been confirmed or published
     */
    public function actionIndexWr(){
        $searchModel = new SuratTugasSearch();
        $dataProvider = $searchModel->searchWr(Yii::$app->request->queryParams);
        $jenisSurat = JenisSurat::find()->where('deleted!=1')->all();
        $status = Status::find()->where(['in', 'status_id', [6, 3]])->all();

        return $this->render('IndexWr', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'jenisSurat' => $jenisSurat,
            'status' => $status,
        ]);
    }

    /**
     * action-id: index-surat-bawahan
     * action-desc: Display all subordinate surat tugas to superior
     */
    public function actionIndexSuratBawahan(){
        $searchModel = new SuratTugasSearch();
        $dataProvider = $searchModel->searchBawahan(Yii::$app->request->queryParams);
        // $dataProvider->sort->defaultOrder = ['status_id' => SORT_ASC, 'created_at' => SORT_ASC];
        $status = Status::find()->where(['not in', 'status_id', [7, 8, 9, 10]])->andWhere('deleted!=1')->all();

        return $this->render('IndexSuratBawahan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }

    /**
     * action-id: view-profil-pegawai
     * action-desc: View specific pegawai profile
     */
    public function actionViewProfilPegawai($id, $suratId)
    {
        $model = Pegawai::find()->where(['pegawai_id' => $id])->one();
        $arraySuratTugasId = SuratTugas::getSuratTugas($model->user_id);
        $modelSuratTugas = SuratTugas::find();
        $modelSuratTugas->where(['YEAR(created_at)' => date('Y')])->andWhere(['in', 'surat_tugas_id', $arraySuratTugasId])->andWhere('deleted!=1')->all();
        $dataProvider = new ActiveDataProvider([
            'query' => $modelSuratTugas,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);

        return $this->render('ViewProfilPegawai', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'suratTugasId' => $suratId,
        ]);
    }

    /**
     * action-id: view-pegawai
     * action-desc: View specific surat tugas of specific pegawai
     */
    public function actionViewPegawai($id)
    {
        $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAtasan = AtasanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        return $this->render('ViewPegawai', [
            'model' => $this->findModel($id),
            'modelAssignee' => $modelAssignee,
            'modelFile' => $modelFile,
            'modelLaporan' => $modelLaporan,
            'modelAtasan' => $modelAtasan,
        ]);
    }

    /**
     * action-id: view-hrd
     * action-desc: View specific surat tugas so HRD can either publish, change report submission date, give note, edit description
     */
    public function actionViewHrd($id)
    {
        $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAtasan = AtasanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        return $this->render('ViewHrd', [
            'model' => $this->findModel($id),
            'modelAssignee' => $modelAssignee,
            'modelFile' => $modelFile,
            'modelLaporan' => $modelLaporan,
            'modelAtasan' => $modelAtasan,
        ]);
    }

    /**
     * action-id: view-wr
     * action-desc: View specific surat tugas so WR can either accept or reject surat tugas report
     */
    public function actionViewWr($id)
    {
        $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAtasan = AtasanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        return $this->render('ViewWr', [
            'model' => $this->findModel($id),
            'modelAssignee' => $modelAssignee,
            'modelFile' => $modelFile,
            'modelLaporan' => $modelLaporan,
            'modelAtasan' => $modelAtasan,
        ]);
    }

    /**
     * action-id: view-surat-bawahan
     * action-desc: View specific surat tugas of subordinate so superior can either accept, reject, give review of surat tugas
     */
    public function actionViewSuratBawahan($id){
        $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelAtasan = AtasanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        return $this->render('ViewSuratBawahan', [
            'model' => $this->findModel($id),
            'modelAssignee' => $modelAssignee,
            'modelFile' => $modelFile,
            'modelAtasan' => $modelAtasan,
        ]);
    }

    /**
     * action-id: add-luar-kampus
     * action-desc: If there are new post request, call the save function else render a _formLuarKampus
     */
    public function actionAddLuarKampus()
    {   
        $model = new SuratTugas();
        $modelPegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
        $modelAtasan = InstApiModel::getAtasanByPegawaiId($modelPegawai->pegawai_id);
        $occurence = array();
        $count = 0;
        $surat_id = 0;
        $modelAssignee = SuratTugasAssignee::find()->where(['id_pegawai' => $modelPegawai->pegawai_id])->all();
        foreach ($modelAssignee as $dataAssignee) {
            $modelSurat = SuratTugas::find()->where(['surat_tugas_id' => $dataAssignee->surat_tugas_id])->andWhere('deleted!=1')->all();  
            foreach ($modelSurat as $dataSurat) {
                  $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $dataSurat->surat_tugas_id])->andWhere('deleted!=1')->all();
                  foreach ($modelLaporan as $dataLaporan) {
                      //SuratTugas::getLaporan(['surat_tugas_id' => $dataSurat->surat_tugas_id]);
                      // echo "<pre>";print_r($modelLaporan);die();
                      if($dataLaporan->status_id == 8 || $dataLaporan->status_id == 10){
                        $count = 1;
                        $surat_id = $dataLaporan->surat_tugas_id;
                        break;
                      }
                      else{
                        $count = 0;
                      }
                  }
                      
            }  
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                return ActiveForm::validate($model);
            }

            if ($model->atasan == null) {
               \Yii::$app->messenger->addErrorFlash("Harap memilih atasan anda !");
               return $this->redirect(\Yii::$app->request->referrer);
            }
            
            //Set Default Information
            $model->perequest = $modelPegawai->pegawai_id;
            $model->jenis_surat_id = 1;
            $model->status_id = 1;

            //Get the date difference
            $today = time();
            $berangkat = strtotime($model->tanggal_berangkat);
            $kembali = strtotime($model->tanggal_kembali);
            $mulai = strtotime($model->tanggal_mulai);
            $selesai = strtotime($model->tanggal_selesai);
            $kembali_kerja = strtotime($model->kembali_bekerja);

            if($model->validate()){
                $model->save();

                //Atasan Handler
                if($model->atasan != null){
                    foreach($model->atasan as $data){
                        $modelAtasanSuratTugas = new AtasanSuratTugas();
                        $modelAtasanSuratTugas->id_pegawai = $data;
                        $modelAtasanSuratTugas->surat_tugas_id = $model->surat_tugas_id;
                        $modelAtasanSuratTugas->perequest = $model->perequest;
                        $atasan = Pegawai::find()->where(['pegawai_id' => $modelAtasanSuratTugas->id_pegawai])->andWhere('deleted!=1')->one();
                        if($modelAtasanSuratTugas->validate()){
                            $modelAtasanSuratTugas->save();
                            \Yii::$app->messenger->sendNotificationToUser((int) $atasan->user_id, "Ada request surat tugas dari bawahan");
                        }else{
                            $errors = $modelAtasanSuratTugas->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Participants Handler
                foreach(Yii::$app->request->post()['Peserta'] as $data){
                    if($data['id_pegawai'] == "empty" || array_search($data['id_pegawai'], $occurence) !== false){
                        continue;
                    }else{
                        $modelAssignee = new SuratTugasAssignee();
                        $modelAssignee->id_pegawai = $data['id_pegawai'];
                        $modelAssignee->surat_tugas_id = $model->surat_tugas_id;
                        $modelAssignee->deleted = 0;

                        $pegawai = Pegawai::find()->where(['pegawai_id' => $modelAssignee->id_pegawai])->andWhere('deleted!=1')->one();
                        $from = $pegawai->email;
                        $subject = "";
                        $body = "";

                        if($modelAssignee->validate()){
                            array_push($occurence, $modelAssignee->id_pegawai);
                            $modelAssignee->save();
                            // \Yii::$app->messenger->sendEmail($from, $this->to, $subject, $body);
                        }else{
                            $errors = $modelAssignee->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelFile = new SuratTugasFile();
                        $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        $modelFile->nama_file = $status->fileinfo[$i]->name;
                        //$modelFile->lokasi_file = $fileDir;
                        $modelFile->kode_file = $status->fileinfo[$i]->id;
                        if($modelFile->validate()){
                            //Save file to directory $fileDir
                            //$file->saveAs($fileDir);

                            $modelFile->save();
                        }else{
                            $errors = $modelFile->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }
                
                // $model->files = UploadedFile::getInstances($model, 'files');
                // if($model->files != null){
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = $file->baseName;
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);

                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }
                
                return $this->redirect(['view-pegawai', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
            // }else{
            //     \Yii::$app->messenger->addWarningFlash("Permohonan minimal 2 hari sebelum keberangkatan");
            //     return $this->render('AddLuarKampus', [
            //         'model' => $model,
            //     ]);
            // }
        } else {
            // if($count == 1){
            //     \Yii::$app->messenger->addWarningFlash("Harap submit terlebih dahulu Laporan Surat Tugas ini, sebelum mengajukan Surat Tugas baru");
            //     return $this->redirect(['view-pegawai', 'id' => $surat_id]);
            // }
            return $this->render('AddLuarKampus', [
                'model' => $model,
                'modelAtasan' => $modelAtasan,
                'pegawai' => $modelPegawai,
            ]);
        }
    }

    /**
     * action-id: add-dalam-kampus
     * action-desc: If there are new post, call save function else render _formDalamKampus
     */
    public function actionAddDalamKampus()
    {
        $model = new SuratTugas();
        $modelPegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
        $modelAtasan = InstApiModel::getAtasanByPegawaiId($modelPegawai->pegawai_id);

        if ($model->load(Yii::$app->request->post())) {
            //Set Default Information
            $model->perequest = $modelPegawai->pegawai_id;
            $model->status_id = 1;
            $model->jenis_surat_id = 2;

            //Get the date difference
            // $today = time();
            // $plan = strtotime($model->tanggal_mulai);
            // $datediff = $plan - $today;

            // if(strtotime($model->tanggal_selesai) < $plan){
            //     \Yii::$app->messenger->addWarningFlash("Tanggal selesai tidak bisa sebelum tanggal mulai");
            //     return $this->render('AddDalamKampus', [
            //         'model' => $model,
            //     ]);
            // }else if(round($datediff / (60 * 60 * 24)) >= 2){
            if($model->validate()){
                $model->save();

                //Atasan Handler
                if($model->atasan != null){
                    foreach($model->atasan as $data){
                        $modelAtasanSuratTugas = new AtasanSuratTugas();
                        $modelAtasanSuratTugas->id_pegawai = $data;
                        $modelAtasanSuratTugas->surat_tugas_id = $model->surat_tugas_id;
                        if($modelAtasanSuratTugas->validate()){
                            $modelAtasanSuratTugas->save();
                        }else{
                            $errors = $modelAtasanSuratTugas->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Participants Handler
                foreach(Yii::$app->request->post()['Peserta'] as $data){
                    if($data['id_pegawai'] == "empty"){
                        continue;
                    }else{
                        $modelAssignee = new SuratTugasAssignee();
                        $modelAssignee->id_pegawai = $data['id_pegawai'];
                        $modelAssignee->surat_tugas_id = $model->surat_tugas_id;
                        $modelAssignee->deleted = 0;
                        if($modelAssignee->validate()){
                            $modelAssignee->save();
                        }else{
                            $errors = $modelAssignee->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelFile = new SuratTugasFile();
                        $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        $modelFile->nama_file = $status->fileinfo[$i]->name;
                        //$modelFile->lokasi_file = $fileDir;
                        $newFiles->kode_file = $status->fileinfo[$i]->id;
                        if($modelFile->validate()){
                            //Save file to directory $fileDir
                            //$file->saveAs($fileDir);

                            $modelFile->save();
                        }else{
                            $errors = $modelFile->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }
                // $model->files = UploadedFile::getInstances($model, 'files');
                // if($model->files != null){
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = $file->baseName;
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);

                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }
                
                return $this->redirect(['view-pegawai', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
            // }else{
            //     \Yii::$app->messenger->addWarningFlash("Permohonan minimal 2 hari sebelum keberangkatan");
            //     return $this->render('AddDalamKampus', [
            //         'model' => $model,
            //     ]);
            // }
        } else {
            return $this->render('AddDalamKampus', [
                'model' => $model,
                'modelAtasan' => $modelAtasan,
            ]);
        }
    }

    /**
     * action-id: edit-dalam-kampus
     * action-desc: If there are post request, call the save function else render _formUpdateDalamKampus
     */
    public function actionEditDalamKampus($id)
    {
        $model = $this->findModel($id);
        $modelPegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
        $modelAtasan = InstApiModel::getAtasanByPegawaiId($modelPegawai->pegawai_id);
        $modelAssigned = AtasanSuratTugas::find()->select(['id_pegawai'])->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelSisaAtasan = $this->getSisaAtasan($modelPegawai->pegawai_id, $id);
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelLampiran = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();

        if ($model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $model->save();

                //Atasan Handler
                if($model->atasan != null){
                    foreach($model->atasan as $data){
                        $modelAtasanSuratTugas = new AtasanSuratTugas();
                        $modelAtasanSuratTugas->id_pegawai = $data;
                        $modelAtasanSuratTugas->surat_tugas_id = $model->surat_tugas_id;
                        if($modelAtasanSuratTugas->validate()){
                            $modelAtasanSuratTugas->save();
                        }else{
                            $errors = $modelAtasanSuratTugas->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Participants Handler
                foreach(Yii::$app->request->post()['Peserta'] as $data){
                    if($data['id_pegawai'] == "empty"){
                        continue;
                    }else{
                        $modelAssignee = new SuratTugasAssignee();
                        $modelAssignee->id_pegawai = $data['id_pegawai'];
                        $modelAssignee->surat_tugas_id = $model->surat_tugas_id;
                        $modelAssignee->deleted = 0;
                        if($modelAssignee->validate()){
                            $modelAssignee->save();
                        }else{
                            $errors = $modelAssignee->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelFile = new SuratTugasFile();
                        $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        $modelFile->nama_file = $status->fileinfo[$i]->name;
                        //$modelFile->lokasi_file = $fileDir;
                        $newFiles->kode_file = $status->fileinfo[$i]->id;
                        if($modelFile->validate()){
                            //Save file to directory $fileDir
                            //$file->saveAs($fileDir);

                            $modelFile->save();
                        }else{
                            $errors = $modelFile->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }
                // if($model->files != null){
                //     $model->files = UploadedFile::getInstances($model, 'files');
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = $file->baseName;
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);

                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }

                return $this->redirect(['view-pegawai', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        } else {
            return $this->render('EditDalamKampus', [
                'model' => $model,
                'modelAtasan' => $modelAtasan,
                'modelAssigned' => $modelAssigned,
                'modelSisaAtasan' => $modelSisaAtasan,
                'modelAssignee' => $modelAssignee,
                'modelLampiran' => $modelLampiran,
            ]);
        }
    }

    /**
     * action-id: edit-luar-kampus
     * action-desc: If there are post request, call the save function else render _formUpdateLuarKampus
     */
    public function actionEditLuarKampus($id)
    {
        $model = $this->findModel($id);
        $modelPegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
        $modelAtasan = InstApiModel::getAtasanByPegawaiId($modelPegawai->pegawai_id);
        $modelAssigned = AtasanSuratTugas::find()->select(['id_pegawai'])->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelSisaAtasan = $this->getSisaAtasan($modelPegawai->pegawai_id, $id);
        $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $modelLampiran = SuratTugasFile::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $occurence = array();

        if ($model->load(Yii::$app->request->post())) {
            // echo $coba;
            if($model->validate()){
                $model->save();

                //Atasan Handler
                if($model->atasan != null){
                    foreach($model->atasan as $data){
                        $modelAtasanSuratTugas = new AtasanSuratTugas();
                        $modelAtasanSuratTugas->id_pegawai = $data;
                        $modelAtasanSuratTugas->surat_tugas_id = $model->surat_tugas_id;
                        if($modelAtasanSuratTugas->validate()){
                            $modelAtasanSuratTugas->save();
                        }else{
                            $errors = $modelAtasanSuratTugas->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Participants Handler
                foreach(Yii::$app->request->post()['Peserta'] as $data){
                    if($data['id_pegawai'] == "empty" || array_search($data['id_pegawai'], $occurence) !== false){
                        continue;
                    }else{
                        $modelAssignee = new SuratTugasAssignee();
                        $modelAssignee->id_pegawai = $data['id_pegawai'];
                        $modelAssignee->surat_tugas_id = $model->surat_tugas_id;
                        $modelAssignee->deleted = 0;

                        if($modelAssignee->validate()){
                            array_push($occurence, $modelAssignee->id_pegawai);
                            $modelAssignee->save();
                        }else{
                            $errors = $modelAssignee->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelFile = new SuratTugasFile();
                        $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        $modelFile->nama_file = $status->fileinfo[$i]->name;
                        //$modelFile->lokasi_file = $fileDir;
                        $newFiles->kode_file = $status->fileinfo[$i]->id;
                        if($modelFile->validate()){
                            //Save file to directory $fileDir
                            //$file->saveAs($fileDir);

                            $modelFile->save();
                        }else{
                            $errors = $modelFile->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }
                
                // $model->files = UploadedFile::getInstances($model, 'files');
                // if($model->files != null){
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = $file->baseName;
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);

                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }
           
                return $this->redirect(['view-pegawai', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        } else {
            return $this->render('EditLuarKampus', [
                'model' => $model,
                'modelAtasan' => $modelAtasan,
                'modelAssigned' => $modelAssigned,
                'modelSisaAtasan' => $modelSisaAtasan,
                'modelAssignee' => $modelAssignee,
                'modelLampiran' => $modelLampiran,
            ]);
        }
    }

    /**
     * action-id: -
     * action-desc: Find specific data from SuratTugas model
     */
    protected function findModel($id)
    {
        if (($model = SuratTugas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * action-id: pegawais
     * action-desc: return JSON format data of pegawai
     */
    public function actionPegawais()
    {
        if(null !== Yii::$app->request->post()){
            $pegawais = Pegawai::find()->where('deleted!=1')->andWhere(['in', 'status_aktif_pegawai_id', [1, 2]])->orderBy(['nama' => SORT_ASC])->asArray()->all();
            
            return json_encode($pegawais);
        }else{
            return "Ajax failed";
        }
    }

    /**
     * action-id: pegawai-self
     * action-desc: return JSON format data of pegawai self
     */
    public function actionPegawaiSelf()
    {
        if(null !== Yii::$app->request->post()){
            $pegawais = Pegawai::find()->where('deleted!=1')->andWhere(['in', 'status_aktif_pegawai_id', [1, 2]])->andWhere(['pegawai_id' => $this->pegawai->pegawai_id])->asArray()->one();

            return json_encode($pegawais);
        }else{
            return "Ajax failed";
        }
    }

    /**
     * action-id: pegawais-bawahan
     * action-desc: Return JSON format data of subordinate
     */
    // public function actionPegawaisBawahan($id){
    //     if(null !== Yii::$app->request->post()){
    //         $pegawais = InstApiModel::getBawahanByPegawaiId($id);
    //         $data = ArrayHelper::toArray($pegawais);
           
    //         return json_encode($data);
    //     }else{
    //         return "Ajax failed";
    //     }
    // }

    public function actionPegawaisBawahan(){
        if(null !== Yii::$app->request->post()['id']){
            $pegawais = new InstApiModel();
            $pegawais = $pegawais->getBawahanByPegawaiId(Yii::$app->request->post()['id']);
            $data = ArrayHelper::toArray($pegawais);
           
            return json_encode($data);
        }else{
            return "Ajax failed";
        }
    }

    // public function actionDownloadAttachments($id){
    //     $model = SuratTugasFile::find()->where(['surat_tugas_file_id' => $id])->one();
    //     $path = Yii::getAlias('@webroot').'/';
    //     $file = $path.$model->lokasi_file;
    //     if(file_exists($file)){
    //         Yii::$app->response->sendFile($file);
    //     }else{
    //         echo "File's missing";
    //     }
    // }

    // public function actionDownloadReports($id){
    //     $model = LaporanSuratTugas::find()->where(['laporan_surat_tugas_id' => $id])->one();
    //     $path = Yii::getAlias('@webroot').'/';
    //     $file = $path.$model->lokasi_file;
    //     if(file_exists($file)){
    //         Yii::$app->response->sendFile($file);
    //     }else{
    //         echo "File's missing";
    //     }
    // }

    /**
     * action-id: batalkan
     * action-desc: batal mengajukan surat tugas
     */
    public function actionBatalkan($id){
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            $model->status_id = 5;
            if($model->validate()){
                $user_id = Yii::$app->user->identity->id;
                $user_pegawai = Pegawai::find()->where(['user_id'=> $user_id])->one();
                $pegawai = $user_pegawai->pegawai_id;
                $model->penyetuju = $pegawai;
                $model->save();
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }
        else{
            $model->status_id = 5;
            $model->save();
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * action-id: tolak
     * action-desc: reject and give review to specific surat tugas
     */
    public function actionTolak($id){
        $model = $this->findModel($id);
        if($model->load(Yii::$app->request->post())){
            $model->status_id = 4;
            if($model->validate()){
                $user_id = Yii::$app->user->identity->id;
                $user_pegawai = Pegawai::find()->where(['user_id'=> $user_id])->one();
                $pegawai = $user_pegawai->pegawai_id;
                $model->penyetuju = $pegawai;
                $model->save();
                return $this->redirect(['view-surat-bawahan', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }
    }

    /**
     * action-id: terbitkan
     * action-desc: Publish specific surat tugas
     */
    public function actionTerbitkan($id){
        $model = $this->findModel($id);
        $modelLaporan = new LaporanSuratTugas();

        if($model->load(Yii::$app->request->post())){
            $model->status_id = 3;
            $dateToday = date('Y-m-d');
            $model->tanggal_surat = $dateToday;
            $modelLaporan->surat_tugas_id = $id;
            $modelLaporan->status_id = 8;
            $modelLaporan->batas_submit = date('Y-m-d H:i:s', strtotime('+2 day', strtotime($model->tanggal_kembali)));
            if($modelLaporan->validate()){
                $modelLaporan->save();
                if($model->validate()){
                    $model->save();
                    return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
                }else{
                    $errors = $model->errors;
                    print_r(array_values($errors));
                }
            }else{
                $errors = $modelLaporan->errors;
                print_r(array_values($errors));
            }
        }
    }

    /**
     * action-id: terima
     * action-desc: Accept specific surat tugas
     */
    public function actionTerima($id){
        $model = $this->findModel($id);
        if($model->status_id == 1 || $model->status_id == 2 || $model->status_id == 4 || $model->status_id == 6){
            $model->status_id = 6;
            if($model->validate()){
                $user_id = Yii::$app->user->identity->id;
                $user_pegawai = Pegawai::find()->where(['user_id'=> $user_id])->one();
                $pegawai = $user_pegawai->pegawai_id;
                $model->penyetuju = $pegawai;
                $model->save();
                return $this->redirect(['view-surat-bawahan', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }else{
            \Yii::$app->messenger->addErrorFlash("Surat tugas telah diterbitkan");
            $this->redirect('index-surat-bawahan');
        }
    }

    /**
     * action-id: tolak
     * action-desc: Reject specific surat tugas
     */
    // public function actionTolak($id){
    //     $model = $this->findModel($id);
    //     if($model->status_id == 1 || $model->status_id == 2 || $model->status_id == 4 || $model->status_id == 6){
    //         $model->status_id = 4;
    //         if($model->validate()){
    //             $user_id = Yii::$app->user->identity->id;
    //             $user_pegawai = Pegawai::find()->where(['user_id'=> $user_id])->one();
    //             $pegawai = $user_pegawai->pegawai_id;
    //             $model->penyetuju = $pegawai;
    //             $model->save();
    //             return $this->redirect(['view-surat-bawahan', 'id' => $model->surat_tugas_id]);
    //         }else{
    //             $errors = $model->errors;
    //             print_r(array_values($errors));
    //         }
    //     }else{
    //         \Yii::$app->messenger->addErrorFlash("Surat tugas telah diterbitkan");
    //         $this->redirect('index-surat-bawahan');
    //     }
    // }

    /**
     * action-id: buka-submission?id=
     * action-desc: Open report submission time for specific surat tugas
     */
    // public function actionBukaSubmission($id){
    //     $model = $this->findModel($id);
    //     $modelLaporan = new LaporanSuratTugas();
    //     if($modelLaporan->load(Yii::$app->request->post())){
    //         //Set default value
    //         $modelLaporan->surat_tugas_id = $model->surat_tugas_id;
    //         $modelLaporan->deleted = 0;

    //         if($model->jenis_surat_id == 1){
    //             //Get the date difference
    //             $datediff = strtotime($modelLaporan->batas_submit) - strtotime($model->tanggal_kembali);

    //             if(round($datediff / (60 * 60 * 24)) >= 2){
    //                 if($modelLaporan->validate()){
    //                     $modelLaporan->save();
    //                     return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
    //                 }else{
    //                     $errors = $model->errors;
    //                     print_r(array_values($errors));
    //                 }
    //             }else{
    //                 \Yii::$app->messenger->addWarningFlash("Minimal pembukaan submission adalah 2 hari setelah kembali");
    //                 $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
    //                 $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
    //                 return $this->render('ViewHrd', [
    //                     'model' => $model,
    //                     'modelAssignee' => $modelAssignee,
    //                     'modelFile' => $modelFile,
    //                 ]);
    //             }
    //         }else{
    //             //Get the date difference
    //             $datediff = strtotime($modelLaporan->batas_submit) - strtotime($model->tanggal_selesai);

    //             if(round($datediff / (60 * 60 * 24)) >= 2){
    //                 if($modelLaporan->validate()){
    //                     $modelLaporan->save();
    //                     return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
    //                 }else{
    //                     $errors = $model->errors;
    //                     print_r(array_values($errors));
    //                 }
    //             }else{
    //                 \Yii::$app->messenger->addWarningFlash("Minimal pembukaan submission adalah 2 hari setelah tugas selesai");
    //                 $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
    //                 $modelAssignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
    //                 return $this->render('ViewHrd', [
    //                     'model' => $model,
    //                     'modelAssignee' => $modelAssignee,
    //                     'modelFile' => $modelFile,
    //                 ]);
    //             }
    //         }
    //     }
    // }

    /**
     * action-id: edit-submission
     * action-desc: Edit report submission time of specific surat tugas
     */
    public function actionEditSubmission($id){
        $model = $this->findModel($id);
        $modelLaporan = SuratTugas::getLaporan($id);
        if($modelLaporan->load(Yii::$app->request->post())){
            if($modelLaporan->validate()){
                $modelLaporan->save();
                return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }
    }

    /**
     * action-id: delete-peserta
     * action-desc: Remove assignee from specific surat tugas dalam kampus
     */
    public function actionDeletePeserta($id, $surattugas){
        $model = SuratTugasAssignee::find()->where(['id_pegawai' => $id])->andWhere(['surat_tugas_id' => $surattugas])->andWhere('deleted!=1')->one();
        $model->forceDelete();

        return $this->redirect(['edit-dalam-kampus', 'id' => $surattugas]);
    }

    /**
     * action-id: delete-peserta-luar
     * action-desc: Remove assignee from specific surat tugas luar kampus
     */
    public function actionDeletePesertaLuar($id, $surattugas){
        $model = SuratTugasAssignee::find()->where(['id_pegawai' => $id])->andWhere(['surat_tugas_id' => $surattugas])->andWhere('deleted!=1')->one();
        $model->forceDelete();

        return $this->redirect(['edit-luar-kampus', 'id' => $surattugas]);
    }

    /**
     * action-id: delete-file?id= &surattugas=
     * action-desc: Remove specific attachment from specific surat tugas
     */
    public function actionDeleteFile($id, $surattugas){
        $modelSurat = $this->findModel($surattugas);
        $model = SuratTugasFile::find()->where(['surat_tugas_file_id' => $id])->andWhere(['surat_tugas_id' => $surattugas])->andWhere('deleted!=1')->one();
        //$path = Yii::getAlias('@webroot').'/';
        //$file = $path.$model->lokasi_file;
        // if(file_exists($file)){
        //     unlink($file);
        $model->forceDelete();
        //}
            if($modelSurat->jenis_surat_id == 1){
                return $this->redirect(['edit-luar-kampus', 'id' => $surattugas]);
            }else if($modelSurat->jenis_surat_id == 2){
                return $this->redirect(['edit-dalam-kampus', 'id' => $surattugas]);
            }
    }

    /**
     * action-id: -
     * action-desc: Get remaining superior
     */
    public function getSisaAtasan($pegawai_id, $surattugas, $instansi_id=null){
        $modelAssigned = SuratTugas::getAssignedAtasan($surattugas);

        $current_date = date('Y-m-d');

        $pejabat = Pejabat::find()->select('struktur_jabatan_id')->where(['pegawai_id' => $pegawai_id])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $struktur = StrukturJabatan::find()->select('parent as struktur_jabatan_id')->where(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->all();
        if($instansi_id!=null)
            $struktur = StrukturJabatan::find()->select('parent as struktur_jabatan_id')->where(['instansi_id' => $instansi_id])->andWhere(['in', 'struktur_jabatan_id', $pejabat])->andWhere('deleted != 1')->all();

        $parent = Pejabat::find()->select('pegawai_id')->where(['in', 'struktur_jabatan_id', $struktur])->andWhere(['<=', 'awal_masa_kerja', $current_date])->andWhere(['>=', 'akhir_masa_kerja', $current_date])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->all();

        $pegawai = Pegawai::find()->where(['not in', 'pegawai_id', $modelAssigned])->andWhere('deleted != 1')->andWhere(['in', 'pegawai_id', $parent])->all();

        return $pegawai;
    }

    /**
     * action-id: delete-atasan
     * action-desc: Remove superior from specific surat tugas
     */
    public function actionDeleteAtasan($id, $surattugas){
        $modelSurat = $this->findModel($surattugas);
        $modelAtasan = AtasanSuratTugas::find()->where(['id_pegawai' => $id])->andWhere(['surat_tugas_id' => $surattugas])->andWhere('deleted!=1')->one();
        $modelAtasan->forceDelete();
        if($modelSurat->jenis_surat_id == 1){
            return $this->redirect(['edit-luar-kampus', 'id' => $surattugas]);
        }else if($modelSurat->jenis_surat_id == 2){
            return $this->redirect(['edit-dalam-kampus', 'id' => $surattugas]);
        }
    }

    /**
     * action-id: submit-laporan
     * action-desc: Submit report of specific surat tugas
     */
    public function actionSubmitLaporan($id){
        $model = $this->findModel($id);
        $modelLaporan = SuratTugas::getLaporan($id);
        $today = time();
        $batas = strtotime($modelLaporan->batas_submit);
        // $datediff2 = $today - strtotime($model->tanggal_kembali);
        $selisih = (round($batas / (60 * 60 * 24))) - (round($today / (60 * 60 * 24)));
            // print_r($selisih); die();
        if($selisih > -1){
            if ($model->load(Yii::$app->request->post())) { 
                $berangkat = strtotime($model->realisasi_berangkat);
                $kembali = strtotime($model->realisasi_kembali);

                if($berangkat > $kembali){
                    \Yii::$app->messenger->addWarningFlash("Tanggal realisasi kembali tidak bisa sebelum tanggal realisasi berangkat");
                    return $this->redirect(\Yii::$app->request->referrer);
                }                
                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelLaporan->surat_tugas_id = $model->surat_tugas_id;
                        $modelLaporan->nama_file = $status->fileinfo[$i]->name;
                        $modelLaporan->kode_laporan = $status->fileinfo[$i]->id;
                        if($modelLaporan->validate()){
                            $modelLaporan->status_id = 7;
                            $modelLaporan->save();
                        }else{
                            $errors = $modelLaporan->errors;
                            print_r(array_values($errors));
                            die();
                        }
                        // $modelFile = new SuratTugasFile();
                        // $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        // $modelFile->nama_file = $status->fileinfo[$i]->name;
                        // $modelFile->kode_file = $status->fileinfo[$i]->id;
                        // if($modelFile->validate()){
                        //     $modelFile->save();
                        // }else{
                        //     $errors = $modelFile->errors;
                        //     print_r(array_values($errors));
                        //     die();
                        // }
                    }
                }
                
                // $model->files = UploadedFile::getInstances($model, 'files');
                // if($model->files != null){
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = '$file->baseName';
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);

                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }

                
                // $modelLaporan->save();
                $model->save();
            }

            return $this->redirect(['view-pegawai', 'id' => $id]);
        }else{
            \Yii::$app->messenger->addWarningFlash("Waktu submit laporan telah ditutup");
            return $this->redirect(['view-pegawai', 'id' => $id]);
        }
    }

    /**
     * action-id: create-pdf
     * action-desc: Print specific surat tugas
     */
    public function actionCreatePdf($id){
        $modelLaporan = SuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->one();
        $modelPegawaiId = SuratTugasAssignee::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $arrayPegawaiId = array();
        foreach($modelPegawaiId as $pegawai){
            array_push($arrayPegawaiId, $pegawai['id_pegawai']);
        }
        $daftar_jabatan = StrukturJabatan::find()->where(['struktur_jabatan_id' => $modelLaporan->penandatangan])->one();
        $nama_jabatan = $daftar_jabatan->jabatan;

        $struktur_jabatan = new InstApiModel();
        $array_jabatan = $struktur_jabatan->getPejabatByJabatan($modelLaporan->penandatangan, 4);
        $hasil_jabatan = $array_jabatan[0]->pegawai->nama;

        $modelPeserta = Pegawai::find()->where(['in', 'pegawai_id', $arrayPegawaiId])->all();
        $mPDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 10.5,
            'default_font ' => 'serif'
        ]);
        // $mPDF = new mPDF('utf-8', 'A4', 10.5, 'serif');
        $mPDF->WriteHTML($this->renderPartial('mpdf', array('model' => $modelLaporan, 'pesertas' => $modelPeserta, 'nama_jabatan' => $nama_jabatan, 'hasil_jabatan' => $hasil_jabatan,)));
        $mPDF->debug = true;
        $mPDF->Output($modelLaporan->no_surat . ".pdf", "I");
        exit;
    }

    /**
     * action-id: terima-laporan
     * action-desc: Accept report of specific surat tugas
     */
    public function actionTerimaLaporan($id){
        $model = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->one();
        // if($model->load(Yii::$app->request->post(), "")){
        if($model != null && $model->nama_file != null){
            $model->status_id = 9;
            if($model->validate()){
                $model->save();
                return $this->redirect(['view-wr', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }else{
            \Yii::$app->messenger->addErrorFlash("Surat tugas belum diterbitkan / laporan belum dimasukkan");
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * action-id: review-laporan
     * action-desc: review report of specific surat tugas
     */
    public function actionReviewLaporan($id){
        $model = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->one();
        $model_surat = $this->findModel($id);
        $today = time();
        $tgl_submit = strtotime($model->batas_submit);
        if ($model_surat->load(Yii::$app->request->post())) { 
            // echo "<pre>";print_r($today);die();
            if($today > $tgl_submit || $today == $tgl_submit){
                $model->batas_submit = date('Y-m-d H:i:s', strtotime('+2 day', $today));
            }
            $model->status_id = 10;
            $model->review_laporan = $model_surat->review_laporan;
            if($model->validate()){
                $model->save();
                return $this->redirect(['view-wr', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }else{
            \Yii::$app->messenger->addErrorFlash("Surat tugas belum diterbitkan / laporan belum dimasukkan");
            $this->redirect('index-wr');
        }
    }

    /**
     * action-id: add-penugasan-luar-kampus
     * action-desc: If there are post request, call save function else render _formPenugasanLuarKampus
     */
    public function actionAddPenugasanLuarKampus(){
        $model = new SuratTugas();
        $modelPegawai = Pegawai::find()->where(['user_id' => \Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
        $occurence = array();

        if($model->load(Yii::$app->request->post())){
            $model->perequest = $modelPegawai->pegawai_id;
            $model->penyetuju = $modelPegawai->pegawai_id;
            $model->jenis_surat_id = 3;
            $model->status_id = 6;

            //Get the date difference
            $today = time();
            $berangkat = strtotime($model->tanggal_berangkat);
            $kembali = strtotime($model->tanggal_kembali);
            $mulai = strtotime($model->tanggal_mulai);
            $selesai = strtotime($model->tanggal_selesai);
            $kembali_kerja = strtotime($model->kembali_bekerja);

            if($berangkat < $today || $kembali < $today || $mulai < $today || $selesai < $today || $kembali_kerja < $today){
                \Yii::$app->messenger->addWarningFlash("Tanggal sudah lewat");
                return $this->render('AddLuarKampus', [
                    'model' => $model,
                    'modelAtasan' => $modelAtasan,
                    'pegawai' => $modelPegawai,
                ]);
            }

            else if($mulai < $berangkat || $selesai > $kembali){
                \Yii::$app->messenger->addWarningFlash("Tanggal mulai dan selesai harus diantara tanggal berangkat dan kembali");
                return $this->render('AddLuarKampus', [
                    'model' => $model,
                    'modelAtasan' => $modelAtasan,
                    'pegawai' => $modelPegawai,
                ]);
            }

            else if($kembali < $berangkat){
                \Yii::$app->messenger->addWarningFlash("Tanggal kembali tidak bisa sebelum tanggal berangkat");
                return $this->render('AddLuarKampus', [
                    'model' => $model,
                    'modelAtasan' => $modelAtasan,
                    'pegawai' => $modelPegawai,
                ]);
            }
            else if($selesai < $mulai){
                \Yii::$app->messenger->addWarningFlash("Tanggal selesai tidak bisa sebelum tanggal mulai");
                return $this->render('AddLuarKampus', [
                    'model' => $model,
                    'modelAtasan' => $modelAtasan,
                    'pegawai' => $modelPegawai,
                ]);
            }
            else if($kembali_kerja < $kembali){
                \Yii::$app->messenger->addWarningFlash("Tanggal kembali bekerja tidak bisa sebelum tanggal kembali");
                return $this->render('AddLuarKampus', [
                    'model' => $model,
                    'modelAtasan' => $modelAtasan,
                    'pegawai' => $modelPegawai,
                ]);
            }

            if($model->validate()){
                $model->save();

                //Atasan Handler
                // if($model->atasan != null){
                //     foreach($model->atasan as $data){
                $modelAtasanSuratTugas = new AtasanSuratTugas();
                $modelAtasanSuratTugas->id_pegawai = $modelPegawai->pegawai_id;
                $modelAtasanSuratTugas->surat_tugas_id = $model->surat_tugas_id;
                $modelAtasanSuratTugas->perequest = $model->perequest;
                if($modelAtasanSuratTugas->validate()){
                    $modelAtasanSuratTugas->save();
                }else{
                    $errors = $modelAtasanSuratTugas->errors;
                    print_r(array_values($errors));
                    die();
                }
                //     }
                // }
                
                //Participants Handler
                foreach(Yii::$app->request->post()['Peserta'] as $data){
                    if($data['id_pegawai'] == "empty" || array_search($data['id_pegawai'], $occurence) !== false){
                        continue;
                    }else{
                        $modelAssignee = new SuratTugasAssignee();
                        $modelAssignee->id_pegawai = $data['id_pegawai'];
                        $modelAssignee->surat_tugas_id = $model->surat_tugas_id;
                        $modelAssignee->deleted = 0;
                        $pegawai = Pegawai::find()->where(['pegawai_id' => $modelAssignee->id_pegawai])->andWhere('deleted!=1')->one();

                        if($modelAssignee->validate()){
                            array_push($occurence, $modelAssignee->id_pegawai);
                            $modelAssignee->save();
                            \Yii::$app->messenger->sendNotificationToUser((int) $pegawai->user_id, "Ada surat tugas dari atasan");
                        }else{
                            $errors = $modelAssignee->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }

                //Files Handler
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status != null && $status->status == 'success'){
                    $total = count($status->fileinfo);
                    for ($i=0;$i<$total;$i++)
                    {
                        $modelFile = new SuratTugasFile();
                        $modelFile->surat_tugas_id = $model->surat_tugas_id;
                        $modelFile->nama_file = $status->fileinfo[$i]->name;
                        //$modelFile->lokasi_file = $fileDir;
                        $modelFile->kode_file = $status->fileinfo[$i]->id;
                        if($modelFile->validate()){
                            //Save file to directory $fileDir
                            //$file->saveAs($fileDir);

                            $modelFile->save();
                        }else{
                            $errors = $modelFile->errors;
                            print_r(array_values($errors));
                            die();
                        }
                    }
                }
                
                // $model->files = UploadedFile::getInstances($model, 'files');
                // if($model->files != null){
                //     foreach($model->files as $file){
                //         $modelFile = new SuratTugasFile();
                //         $fileDir = 'uploads/attachments/' . $file->baseName . '.' . $file->extension;
                //         $modelFile->surat_tugas_id = $model->surat_tugas_id;
                //         $modelFile->nama_file = $file->baseName;
                //         $modelFile->lokasi_file = $fileDir;
                //         $modelFile->deleted = 0;
                //         if($modelFile->validate()){
                //             //Save file to directory $fileDir
                //             $file->saveAs($fileDir);
                            
                //             $modelFile->save();
                //         }else{
                //             $errors = $modelFile->errors;
                //             print_r(array_values($errors));
                //             die();
                //         }
                //     }
                // }

                return $this->redirect(['view-surat-bawahan', 'id' => $model->surat_tugas_id]);
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }

        }else{
            // $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->one();

            return $this->render('AddPenugasanLuar', [
                'model' => $model,
                'pegawai' => $modelPegawai,
            ]);
        }
    }
    
    /**
     * action-id: add-keterangan
     * action-desc: Add description of specific surat tugas
     */
    public function actionAddKeterangan($id){
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->save();
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }
        
        return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
    }

    /**
     * action-id: add-catatan
     * action-desc: Give note to specific surat tugas
     */
    public function actionAddCatatan($id){
        $model = $this->findModel($id);
    
        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                $model->save();
            }else{
                $errors = $model->errors;
                print_r(array_values($errors));
            }
        }
        
        return $this->redirect(['view-hrd', 'id' => $model->surat_tugas_id]);
    }

    /**
     * action-id: tolak-surat-tugas
     * action-desc: Confirmation before rejecting surat tugas
     */
    public function actionTolakSuratTugas($id){
        return $this->render('TolakSuratTugas', [
            'id' => $id,
        ]);
    }

    /**
     * action-id: tolak-laporan-tugas
     * action-desc: Confirmation before rejecting report of surat tugas
     */
    public function actionTolakLaporanTugas($id){
        return $this->render('TolakLaporanTugas', [
            'id' => $id,
        ]);
    }

}

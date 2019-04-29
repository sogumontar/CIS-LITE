<?php

namespace backend\modules\hrdx\controllers;

use Yii;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\PegawaiAbsensi;
use backend\modules\hrdx\models\KuotaCutiTahunan;
use backend\modules\hrdx\models\search\PegawaiAbsensiSearch;
use backend\modules\hrdx\models\search\JenisAbsenSearch;
use backend\modules\hrdx\models\search\PegawaiSearch;
use backend\modules\admin\models\UserHasRole;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\filters\VerbFilter;

use backend\modules\mref\models\statusAktifPegawai;
use backend\modules\hrdx\models\JenisAbsen;
use backend\modules\hrdx\models\Dosen;

/**
 * PegawaiAbsensiController implements the CRUD actions for PegawaiAbsensi model.
 */
class PegawaiAbsensiController extends Controller
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
     * Lists all PegawaiAbsensi models.
     * @return mixed
     */
    public function actionBrowse()
    {
        $pegawai = Pegawai::find()->where(['user_id'=> Yii::$app->user->id])->one();
        $user_role = UserHasRole::find()->where(['user_id'=> Yii::$app->user->id])->all();
        $jenisAbsenAll=null;
        if (!is_null($pegawai)) {
            if ($pegawai->jenis_kelamin_id==1) {
                $jenisAbsenAll = JenisAbsen::find()->where("deleted=0 AND nama NOT LIKE 'Cuti Bersama' AND nama NOT LIKE 'Cuti Melahirkan'")->all();
            }else{
                $jenisAbsenAll = JenisAbsen::find()->where("deleted=0 AND nama NOT LIKE 'Cuti Bersama'")->all();
            } 
        }else{
            \Yii::$app->messenger->addErrorFlash("Anda tidak terdaftar sebagai pegawai");
            return $this->render('blank');
        }

        //get kuota cuti bersama
        $_kuotaCutiBersama = JenisAbsen::find()
                                        ->where(['nama'=>'Cuti Bersama', 'deleted'=>0])
                                        ->one();

        $totalKuotaCutiTahunan = $this->_totalCutiTahunan();
        $tahun = date("Y");
        
        
        $sisaKuota = [];

        if($pegawai == NULL){
            \Yii::$app->messenger->addErrorFlash("Anda tidak terdaftar sebagai pegawai");
            return $this->render('blank');
        }
        else
        {
            foreach ($jenisAbsenAll as $absen) {
                if($absen->nama == 'Cuti Tahunan'){   
                    $sisaKuota['tahunan'] = $this->_calculateSisaCutiTahunan($pegawai->pegawai_id, $tahun);
                }else if($absen->nama == 'Cuti Menikah'){  
                    $jumlah_absen=PegawaiAbsensiController::JumlahAbsenByJenis($pegawai->pegawai_id,$absen->jenis_absen_id,$tahun);
                    $temp=0;
                    if (!is_null($jumlah_absen)) {
                        $temp=$jumlah_absen->jumlah_absen;
                    }
                    $sisaKuota['menikah'] = $absen->kuota-$temp;
                }else if($absen->nama == 'Cuti Kelahiran'){  
                    $jumlah_absen=PegawaiAbsensiController::JumlahAbsenByJenis($pegawai->pegawai_id,$absen->jenis_absen_id,$tahun);
                    $temp=0;
                    if (!is_null($jumlah_absen)) {
                        $temp=$jumlah_absen->jumlah_absen;
                    }
                    $sisaKuota['kelahiran'] = $absen->kuota-$temp;
                }else if($absen->nama == 'Cuti Melahirkan'){  
                    $jumlah_absen=PegawaiAbsensiController::JumlahAbsenByJenis($pegawai->pegawai_id,$absen->jenis_absen_id,$tahun);
                    $temp=0;
                    if (!is_null($jumlah_absen)) {
                        $temp=$jumlah_absen->jumlah_absen;
                    }
                    $sisaKuota['melahirkan'] = $absen->kuota-$temp;
                }
                else if($absen->nama == 'Cuti Kedukaan'){  
                    $jumlah_absen=PegawaiAbsensiController::JumlahAbsenByJenis($pegawai->pegawai_id,$absen->jenis_absen_id,$tahun);
                    $temp=0;
                    if (!is_null($jumlah_absen)) {
                        $temp=$jumlah_absen->jumlah_absen;
                    }
                    $sisaKuota['kedukaan'] = $absen->kuota-$temp;
                }
                else{
                    $sisaKuota[] = -99;
                }
            }
        }

        return $this->render('index', [
            'pegawai' => $pegawai,
            'user_role' => $user_role,
            'jenisAbsenAll' => $jenisAbsenAll,
            'totalKuotaCutiTahunan' => $totalKuotaCutiTahunan,
            'sisaKuota' => $sisaKuota,
            '_kuotaCutiBersama'=>$_kuotaCutiBersama,
        ]);   
    }

    /**
     * Histori cuti masing-masing pegawai.
     * @return mixed
     */
    public function actionHistoriCuti($id){
        $searchModel = new PegawaiAbsensiSearch();
        $dataProviderCuti = $searchModel->searchByJenisAbsen('cuti', $id,Yii::$app->request->queryParams);

        return $this->render('histori_cuti', [
            'searchModel' => $searchModel,
            'dataProviderCuti' => $dataProviderCuti,
        ]);   
    }

    /**
     * Histori izin masing-masing pegawai.
     * @return mixed
     */
    public function actionHistoriIzin($id){
        $searchModel = new PegawaiAbsensiSearch();
        $dataProviderIzin = $searchModel->searchByJenisAbsen('izin', $id,Yii::$app->request->queryParams);

        return $this->render('histori_izin', [
            'searchModel' => $searchModel,
            'dataProviderIzin' => $dataProviderIzin,
        ]);   
    }


    /**
     * Proses permintaan izin maupun cuti.
     * @return mixed
     */
    public function actionRequest($pegawai_id,$jenis_absen_id){
        $model = new PegawaiAbsensi();

        $jenis_absensi=JenisAbsen::findOne($jenis_absen_id);

        $penerima_tugas= Pegawai::find()
        ->where("deleted=0")
        ->all();

        if ($model->load(Yii::$app->request->post())) {
            $pengalihan_tugas='';
            if(isset($_POST['pengalihan_tugas']))
                $pengalihan_tugas=implode(';',$_POST['pengalihan_tugas']);


            // Yii::$app->debugger->print_array($pengalihan_tugas);
            
            $model->pegawai_id = $pegawai_id;
            $model->pengalihan_tugas = $pengalihan_tugas;
            $model->jenis_absen_id = $jenis_absen_id;
            $model->approval_1=150;
            $model->approval_2=96;
            if(!$model->save()){
                foreach ($model->getErrors() as $key => $value) {
                    Yii::$app->messenger->addErrorFlash($value[0]);
                }
            }else{
                return $this->redirect(['browse']);
            }
        }
        
        return $this->render('request', [
            'model' => $model,
            'jenis_absensi' => $jenis_absensi,
            'penerima_tugas' => $penerima_tugas,
        ]);
    }


    /**
     * Edit izin dan cuti masing-masing pegawai.
     * @return mixed
     */
    public function actionEdit($id){
        $model = PegawaiAbsensi::findOne($id);

        $penerima_tugas= Pegawai::find()
        ->where("deleted=0")
        ->all();

        if ($model->load(Yii::$app->request->post())) {
            $pengalihan_tugas='';
            if(isset($_POST['pengalihan_tugas']))
                $pengalihan_tugas=implode(';',$_POST['pengalihan_tugas']);


            // Yii::$app->debugger->print_array($pengalihan_tugas);
            
            $model->pegawai_id = $model->pegawai_id;
            $model->pengalihan_tugas = $pengalihan_tugas;
            $model->approval_1=150;
            $model->approval_2=96;
            if(!$model->save()){
                foreach ($model->getErrors() as $key => $value) {
                    Yii::$app->messenger->addErrorFlash($value[0]);
                }
            }else{
                return $this->redirect(['browse']);
            }
        }
        
        return $this->render('edit', [
            'model' => $model,
            'penerima_tugas' => $penerima_tugas,
        ]);
    }

    /**
     * Tampilkan detail absensi.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $absensi=PegawaiAbsensi::findOne($id);

        $user_role = UserHasRole::find()->where(['user_id'=> Yii::$app->user->id])->all();

        $is_hrd=0;

        foreach ($user_role as $key_user_role => $value_user_role) {
            if ($value_user_role['role']['name']=='hrd') {
                $is_hrd=1;
            }
        }

        return $this->render('view', [
            'absensi' => $absensi,
            'is_hrd' => $is_hrd,
        ]);
    }

    public static function getPegawaiById($id){
        $pegawai=Pegawai::findOne($id);
        return is_null($pegawai)?"":$pegawai->nama;
    }

    /**
     * Tampilkan antrian absensi untuk HRD.
     * @return mixed
     */
    public function actionAntrianAbsensiAsHrd()
    {
        $searchModel = new PegawaiAbsensiSearch();
        $dataProviderAbsensi = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('antrian_absensi_as_hrd', [
            'searchModel' => $searchModel,
            'dataProviderAbsensi'=>$dataProviderAbsensi,
        ]);
    }

    /**
     * Tampilkan antrian absensi untuk Atasan.
     * @return mixed
     */
    public function actionAntrianAbsensiAsAtasan()
    {
        $searchModel = new PegawaiAbsensiSearch();
        $params=Yii::$app->request->queryParams;

        $pegawai = Pegawai::find()->where(['user_id'=> Yii::$app->user->id])->one();
        $params['PegawaiAbsensiSearch']['approval_1']=$pegawai->pegawai_id;
        $dataProviderAbsensi = $searchModel->search($params);

        //Yii::$app->debugger->print_array($params);

        return $this->render('antrian_absensi_as_hrd', [
            'searchModel' => $searchModel,
            'dataProviderAbsensi'=>$dataProviderAbsensi,
        ]);
    }


    /**
     * Penerimaan absensi.
     * @param integer $id
     * @return mixed
     */
    public function actionPenerimaanAbsensi($id,$approval,$status)
    {
        $absensi=PegawaiAbsensi::findOne($id);

        if($approval==1){
            $absensi->status_approval_1=$status;
        }else{
            if($absensi->status_approval_1==0){
                Yii::$app->messenger->addWarningFlash("Absensi belum diterima oleh atasan.");
                return $this->redirect(['view','id'=>$id]);
            }
            $absensi->status_approval_2=$status;
            if ($status==1) {
                Yii::$app->messenger->addSuccessFlash("Absensi sudah diterima oleh HRD.");
            }
        }

        if(!$absensi->save()){
            foreach ($model->getErrors() as $key => $value) {
                Yii::$app->messenger->addErrorFlash($value[0]);
            }
        }else{
            return $this->redirect(['browse']);
        }
    }









    public static function JumlahAbsenByJenis($pegawai_id,$jenis_absen_id,$tahun){
        $pegawai_absensi=PegawaiAbsensi::find()
        ->select(['jumlah_absen'=>"count('jumlah_hari')"])
        ->where('pegawai_id ='.$pegawai_id.' AND jenis_absen_id = '.$jenis_absen_id.' AND YEAR(dari_tanggal) = '.$tahun)
        ->one();
        return $pegawai_absensi;
    }

    

    public function actionManageCuti(){
        $searchModel = new PegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('manageCuti', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDetailCutiPegawai(){

    }
    

    // /**
    //  * Updates an existing PegawaiAbsensi model.
    //  * If update is successful, the browser will be redirected to the 'view' page.
    //  * @param integer $id
    //  * @return mixed
    //  */
    // public function actionEdit($id)
    // {

    //     $model = $this->findModel($id);

    //     $statusAktifPegawaiConf = $ta = Yii::$app->appConfig->get('status_pegawai_aktif');

    //     if($statusAktifPegawaiConf == NULL){
    //         throw new NotFoundHttpException('Config status pegawai aktif belum di set. Hubungi admin aplikasi!!!.');
    //     }
    //     else
    //     {
    //         $statusAktifPegawai = statusAktifPegawai::find()->where(['desc'=>$statusAktifPegawaiConf])->one();
    //     }

    //     if($statusAktifPegawai != NULL){
    //         $pegawai = Pegawai::find()
    //                     ->where(['status_aktif_pegawai_id' => $statusAktifPegawai->status_aktif_pegawai_id])
    //                     ->andWhere(['deleted' => 0])
    //                     ->orderBy('nama')
    //                     ->all();
    //     }
    //     else
    //     {
    //         throw new NotFoundHttpException('Tidak ditemukan data yang sesuai dengan config. Hubungi admin aplikasi!!!.');
    //     }   

    //     if(($model->status_approval_1 == 1) || ($model->status_approval_2 == 1) || ($model->status_approval_1 == 2) || ($model->status_approval_2 == 2)){
    //         \Yii::$app->messenger->addErrorFlash("Request sudah di approve/reject, tidak dapat di edit lagi");
    //         return $this->redirect(['index']);
    //     }
    //     else{
    //         $pegawai_login = Pegawai::find()
    //                         ->where(['status_aktif_pegawai_id' => $statusAktifPegawai->status_aktif_pegawai_id])
    //                         ->andWhere(['deleted' => 0, 'user_id'=>Yii::$app->user->id])
    //                         ->one();
    //         $tahun = date("Y");
    //         $sisaKuota = $this->_calculateSisaCuti($pegawai_login->pegawai_id,$model['jenis_absen_id'], $tahun) + $model->jumlah_hari;
            
            
    //     }
        

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->pegawai_absensi_id]);
    //     } else {
    //         return $this->render('edit', [
    //             'model' => $model,
    //             'pegawai' => $pegawai,
    //             'sisaKuota'=> $sisaKuota,
    //         ]);
    //     }
    // }


    public function actionApprove($idAbsen=null)
    {
        $myPegawaiId = Pegawai::find()->where(['user_id'=> Yii::$app->user->id])->one();
        $searchModel = new PegawaiAbsensiSearch();
        $statusSudahApprove = 1;
        $statusBelumApprove = 0;

        if($myPegawaiId == NULL){
            \Yii::$app->messenger->addErrorFlash("Anda bukan pegawai!");
            return $this->render('blank');

        }else{

            if($idAbsen != null){
                $modelPegawaiAbsensi = $this->findModel($idAbsen);
                if($modelPegawaiAbsensi->approval_1 == $myPegawaiId->pegawai_id){
                    $modelPegawaiAbsensi->status_approval_1 = 1;
                    $modelPegawaiAbsensi->save();
                    Yii::$app->messenger->sendNotificationToUser($modelPegawaiAbsensi->pegawai_id, "Request cuti sudah di approve oleh ". $myPegawaiId->nama);
                    Yii::$app->messenger->addSuccessFlash("Request sudah di approve");

                }
                elseif($modelPegawaiAbsensi->approval_2 == $myPegawaiId->pegawai_id){
                    $modelPegawaiAbsensi->status_approval_2 = 1;
                    $modelPegawaiAbsensi->save();
                    Yii::$app->messenger->sendNotificationToUser($modelPegawaiAbsensi->pegawai_id, "Request cuti sudah di approve oleh ". $myPegawaiId->nama);
                    \Yii::$app->messenger->addSuccessFlash("Request sudah di approve");
                    
                }
            }
            $dataProviderPegawaiAbsensiBelumApp = $searchModel->searchByStatusApproval($myPegawaiId->pegawai_id, $statusBelumApprove, Yii::$app->request->queryParams);
            $dataProviderPegawaiAbsensiSudahApp = $searchModel->searchByStatusApproval($myPegawaiId->pegawai_id, $statusSudahApprove, Yii::$app->request->queryParams);
        
        }

        return $this->render('approve', [
            'dataProviderPegawaiAbsensiBelumApp'=> $dataProviderPegawaiAbsensiBelumApp,
            'dataProviderPegawaiAbsensiSudahApp'=> $dataProviderPegawaiAbsensiSudahApp,
            'searchModel' => $searchModel,
        ]);

    }
    
    public function actionReject($idAbsen){
        $myPegawaiId = Pegawai::find()->where(['user_id'=> Yii::$app->user->id])->one();
        $searchModel = new PegawaiAbsensiSearch();
        $statusSudahApprove = 1;
        $statusBelumApprove = 0;
        $statusReject = 2;

        if($myPegawaiId == NULL){
            \Yii::$app->messenger->addErrorFlash("Anda bukan pegawai!");
            return $this->render('blank');

        }else{
            if($idAbsen != null){
                $modelPegawaiAbsensi = $this->findModel($idAbsen);
                if($modelPegawaiAbsensi->approval_1 == $myPegawaiId->pegawai_id){
                    $modelPegawaiAbsensi->status_approval_1 = 2;
                    $modelPegawaiAbsensi->save();
                    Yii::$app->messenger->sendNotificationToUser($modelPegawaiAbsensi->pegawai_id, "Request cuti sudah di reject oleh ". $myPegawaiId->nama);
                    \Yii::$app->messenger->addWarningFlash("Request cuti/izin di reject");

                }
                elseif($modelPegawaiAbsensi->approval_2 == $myPegawaiId->pegawai_id){
                    $modelPegawaiAbsensi->status_approval_2 = 2;
                    $modelPegawaiAbsensi->save();
                    Yii::$app->messenger->sendNotificationToUser($modelPegawaiAbsensi->pegawai_id, "Request cuti sudah di reject oleh ". $myPegawaiId->nama);
                    \Yii::$app->messenger->addWarningFlash("Request cuti/izin di reject");
                    
                }
            }
            $dataProviderPegawaiAbsensiBelumApp = $searchModel->searchByStatusApproval($myPegawaiId->pegawai_id, $statusBelumApprove, Yii::$app->request->queryParams);
            $dataProviderPegawaiAbsensiSudahApp = $searchModel->searchByStatusApproval($myPegawaiId->pegawai_id, $statusSudahApprove, Yii::$app->request->queryParams);
            $dataProviderPegawaiAbsensiReject = $searchModel->searchByStatusApproval($myPegawaiId->pegawai_id, $statusReject, Yii::$app->request->queryParams);
        }
        return $this->render('approve', [
            'dataProviderPegawaiAbsensiBelumApp'=> $dataProviderPegawaiAbsensiBelumApp,
            'dataProviderPegawaiAbsensiSudahApp'=> $dataProviderPegawaiAbsensiSudahApp,
            'dataProviderPegawaiAbsensiReject'=> $dataProviderPegawaiAbsensiReject,
            'searchModel' => $searchModel,
        ]);
    }


    /*public fuction actionPrint($id){

        $model = $this->findModel($id);

        $content= $this->renderAjax('printForm', [
                    'model'=>$model,
                ]);

        $mpdf = new mPDF('utf-8', 'A4');
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit;
    }*/

    /**
     * Deletes an existing PegawaiAbsensi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PegawaiAbsensi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PegawaiAbsensi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PegawaiAbsensi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function _calculateSisaCuti($pegawai_id, $jenis_absen, $tahun){

        $cutiTahunanConf = Yii::$app->appConfig->get('Cuti Tahunan');
       
        if($jenis_absen != null){
           
            $_quotaSpend = PegawaiAbsensi::find()
                        ->where(['deleted' => 0])
                        ->andWhere(['jenis_absen_id' => $jenis_absen])
                        ->andWhere(['like','dari_tanggal',$tahun])
                        ->andWhere(['pegawai_id'=> $pegawai_id])
                        ->andWhere(['status_approval_1'=>1])
                        ->andWhere(['status_approval_2'=>1])
                        ->all();

            $quotaSpend = 0; 
            foreach ($_quotaSpend as $key => $value) {
                $quotaSpend = $quotaSpend + $value->jumlah_hari;
            }
        
        }
        
        $jenisAbsen = JenisAbsen::find()->where(['jenis_absen_id'=>$jenis_absen])
                        ->andWhere(['deleted' => 0])
                        ->one();


        $sisaQuota = $jenisAbsen->kuota - $quotaSpend;
    
        return $sisaQuota;
    }

    private function _lamaBekerja(){
        $user_login_id =Yii::$app->user->id;
        $myPegawaiId = Pegawai::find()->where(['user_id'=> Yii::$app->user->id])->one();
        
        $now = date_create( date("Y-m-d"));

        if($myPegawaiId != NULL){
            $_temp = date_create($myPegawaiId->tanggal_masuk);
            $diff=date_diff($now, $_temp);
        }else{
            \Yii::$app->messenger->addErrorFlash("Tanggal masuk kepegawaian belum di set!");
            return $this->render('blank');
        }

        $lama_bekerja = round($diff->days / 365);

        return $lama_bekerja;
    }

    private function _totalCutiTahunan(){

        $lama_bekerja = $this->_lamaBekerja();
        $kuotaCutiTahunan = KuotaCutiTahunan::find()
                                ->where(['<=', 'lama_bekerja_min', $lama_bekerja])
                                ->andWhere(['>=','lama_bekerja_max', $lama_bekerja])
                                ->one();

        if($kuotaCutiTahunan != null){
            return  $kuotaCutiTahunan->kuota;
        }
        else
        {
            \Yii::$app->messenger->addErrorFlash("Total Cuti Tahunan anda tidak terdefenisi");
            return 0;
        }
    }

    private function _calculateSisaCutiTahunan($pegawai_id, $tahun){
        //$cutiTahunanConf = Yii::$app->appConfig->get('Cuti Tahunan');
        $cutiTahunan = JenisAbsen::find()->where(['nama'=> 'Cuti Tahunan', 'deleted'=>0])->one(); 

        //$cutiBersamaConf = Yii::$app->appConfig->get('cuti_bersama');
        $modelCutiBersama = JenisAbsen::find()->where(['nama'=>'Cuti Bersama','deleted'=>0])->one();
        
        $totalKuotaCutiTahunan = $this->_totalCutiTahunan();

        $_quotaSpend = PegawaiAbsensi::find()
                    ->asArray()
                    ->where(['deleted' => 0])
                    ->andWhere(['jenis_absen_id' => $cutiTahunan->jenis_absen_id])
                    ->andWhere(['like','dari_tanggal',$tahun])
                    ->andWhere(['pegawai_id'=> $pegawai_id])
                    ->all();
            
        $quotaSpend =0; 
        if($_quotaSpend != null){
            foreach ($_quotaSpend as $temp) {
                $quotaSpend = $quotaSpend + $temp["jumlah_hari"];
            }
        }
    
        $sisaKuota = $totalKuotaCutiTahunan - $quotaSpend;
        $sisaKuota = $sisaKuota - $modelCutiBersama->kuota;
        return $sisaKuota;
    }  
}

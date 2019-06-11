<?php

namespace backend\modules\rppx\controllers;

use backend\modules\cist\models\Pegawai;
use Yii;
use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\PenugasanPengajaranAsdos;
use backend\modules\rppx\models\AdakPengajaran;
use backend\modules\rppx\models\Kuliah; 
use backend\modules\rppx\models\Kelas; 
use backend\modules\rppx\models\Prodi; 
use backend\modules\rppx\models\search\PenugasanPengajaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\rppx\models\Staf;
use backend\modules\rppx\models\HrdxPegawai;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * PenugasanPengajaranController implements the CRUD actions for PenugasanPengajaran model.
 */
class PenugasanPengajaranController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'allow'=>true,
                    'roles'=>['@'],
                ],
            ],
        ];
    }

    /**
     * Lists all PenugasanPengajaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PenugasanPengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PenugasanPengajaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new PenugasanPengajaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionMenu(){
        return $this->render('menu');
    }
    public function actionBaak(){
        return $this->render('baak');
    }
    public function actionKelas(){
        return $this->render('kelas');
    }
    public function actionMenuasdos(){
        return $this->render('menuAsdos');
    }
    public function actionIndexasdos(){
        $searchModel = new PenugasanPengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('indesAsdos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreateasdos($semester){
        $model = new PenugasanPengajaranAsdos();
        $modelPengajaran = Kuliah::find()->where(['sem' => $semester])->all();
        $jlhDosen = 1;
        $jlhAsdos = 1;
        $baris=0;
        $colom=0;
        
        if ($model->load(Yii::$app->request->post())) {

            // var_dump($model->load);
            // var_dump($model->load);
            // die();

            if($model->asdos2==""){
                $model->asdos2=0;
            }
            if($model->asdos3==""){
                $model->asdos3=0;
            }
             Yii::$app->db->createCommand('update krkm_kuliah set stat_created=1 where kuliah_id='.$model->pengajaran_id)->execute();
             
            $model->save(false);

            return $this->render('createAsdos', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $semester,
            ]);
        } else {
              return $this->render('createAsdos', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $semester,
            ]);

        }

    }

    public function actionCreate($semester)
    {
        $model = new PenugasanPengajaran();
        $modelPengajaran = Kuliah::find()->where(['sem' => $semester])->all();
        $jlhDosen = 1;
        $jlhAsdos = 1;
        $baris=0;
        $colom=0;
        $kuliah=$_GET['kuliah'];
        $namekul=Kuliah::find()->where('kuliah_id='.$kuliah)->all();
        $namakuliah;
        $skstot;
        foreach ($namekul as $key ) {
            $namakuliah=$key['nama_kul_ind'];
            $skstot=$key['sks'];
        }
        if ($model->load(Yii::$app->request->post())) {
            $kk=kelas::find()->where(['nama'=>$_GET['kelas']])->all();
            foreach ($kk as $key) {
                $model->kelas=$key['kelas_id'];
            }
            if($model->role_pengajar_id==""){
                $model->role_pengajar_id=0;
            }
            if($model->role_pengajar_id3==""){
                $model->role_pengajar_id3=0;
            }
            
            $model->load=(($skstot+$model->kelas_tatap_muka + $skstot*$model->jumlah_kelas_riil)/$skstot)*($model->load/100);
            
            $model->pengajaran_id=$_GET['kuliah'];
             Yii::$app->db->createCommand('update krkm_kuliah set stat_created=1 where kuliah_id='.$model->pengajaran_id)->execute();
            $model->save(false);
             return $this->render('create', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'kuliah'=>$kuliah,
                'namakuliah'=>$namakuliah,
                'skstot'=>$skstot,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $semester,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'kuliah'=>$kuliah,
                'namakuliah'=>$namakuliah,
                'skstot'=>$skstot,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $semester,
            ]);
        }
    }

    /**
     * Updates an existing PenugasanPengajaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new PenugasanPengajaran();
        $kul_id=0;
        $sems=0;
        $test=PenugasanPengajaran::find()->where('penugasan_pengajaran_id='.$id)->all();
        $kul=0;
        foreach ($test as $key) {
         
         $kul_id= $key['pengajaran_id'];

        }
        $testting=Kuliah::find()->where('kuliah_id='.$kul_id)->all();
        foreach ($testting as $key ) {
            $kul=$key['sem'];
        }

        $modelPengajaran = Kuliah::find()->where(['sem' => $kul])->all();
        $jlhDosen = 1;
        $jlhAsdos = 1;
        $baris=0;
        $colom=0;
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->penugasan_pengajaran_id]);
        } else {
            return $this->render('update', [
              'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
                'colom'=>$colom,
                'modelPengajaran' => $modelPengajaran,
                'semester'=> $kul,
            ]);
        }
    }

    public function actionDekan(){
        return $this->render('dekan');
    }
    public function actionApprove(){
        return $this->render('Approval');
    }
    public function actionApprovegbk(){
        return $this->render('ApprovalByGBK');
    }
    public function actionXx(){
        return $this->render('dekan');
    }
    /**
     * Deletes an existing PenugasanPengajaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        echo $id;   
        die();
        // $this->findModel($id)->delete();
        Yii::$app->db->createCommand()->delete('rppx_penugasan_pengajaran','penugasan_pengajaran_id='.$id)->execute();

        // PenugasanPengajaran::findModel($id)->delete();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the PenugasanPengajaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PenugasanPengajaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PenugasanPengajaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionPegawais()
    {
        if(null !== Yii::$app->request->post()){
            $pegawais = Pegawai::find()->orderBy(['nama' => SORT_ASC])->asArray()->all();
            return json_encode($pegawais);
        }else{
            return "Ajax failed";
        }
    }
    public function actionPegawai(){
        if(null !== Yii::$app->request->post()){
            $staf=Staf::find()->select('pegawai_id')->asArray()->all();
            
                $pegawai = HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all();
            
            return json_encode($pegawai);
        }else{
            return "Ajax failed";
        }
    }
    public function actionRequest(){
        $searchModel = new PenugasanPengajaranSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('request', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionInsret($id){
         Yii::$app->db->createCommand()->delete('rppx_penugasan_pengajaran','penugasan_pengajaran_id='.$id)->execute();

        // PenugasanPengajaran::findModel($id)->delete();
        
        return $this->redirect(['index']);
        echo $id;
       
    }
     public function actionTtg(){
      return $this->render('data_convert');
    }
    public function actionConvert(){
        $datas=Kuliah::find()->groupBy('sem')->all();
        $data=PenugasanPengajaran::find()->all();


        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: filename=Data Pegawai.xls");
        echo "<h1></h1>";
        echo '<table border="1" align="center" style="font-family: Times New Roman;">
        <tr><td colspan="22"><center><h1>Penugasan Pengajaran</h1></center></td></tr>
        <tr >
            <th style="background-color: #8db3e2;">Fakultas</th>
            <th style="background-color: #8db3e2;">Prodi</th>
            <th style="background-color: #8db3e2;">Semester</th>
            <th style="background-color: #8db3e2;">Kode MK</th>
            <th style="background-color: #8db3e2;">Nama Mata Kuliah</th>
            <th style="background-color: #8db3e2;">Short Name</th>
            <th style="background-color: #8db3e2;">Jumlah Kelas Riil</th>
            <th style="background-color: #8db3e2;">SKS</th>
            <th style="background-color: #8db3e2;">SKS-Teori</th>
            <th style="background-color: #8db3e2;">SKS-Praktikum</th>
            <th style="background-color: #8db3e2;">Kelas Tatap Muka</th>
            <th style="background-color: #8db3e2;">Kelas Praktikum</th>
            <th style="background-color: #8db3e2;">Jlh Mhs</th>
            <th style="background-color: #8db3e2;">Dosen 1</th>
            <th style="background-color: #8db3e2;">Dosen 2</th>
            <th style="background-color: #8db3e2;">Dosen 3</th>
            <th style="background-color: #8db3e2;">%Dosen 1</th>
            <th style="background-color: #8db3e2;">%Dosen 2</th>
            <th style="background-color: #8db3e2;">%Dosen 3</th>
            <th style="background-color: #8db3e2;">Load Dosen 1</th>
            <th style="background-color: #8db3e2;">Load Dosen 2</th>
            <th style="background-color: #8db3e2;">Load Dosen 3</th>
        </tr>';
        $cek='';
        foreach($datas as $ss){
            if($cek!=''){
                if($cek!=$ss['sem']){
                    $cek=$ss['sem'];
                    echo '<tr><td colspan="22" style="background-color: #bfbfbf;"></td></tr>';
                }
            }else{
                $cek=$ss['sem'];
            }
        foreach($data as $dd){

                $semester;
                $sks;
                $pId=HrdxPegawai::find()->where('pegawai_id = '.$dd['pegawai_id'])->all();
                $d2=HrdxPegawai::find()->where('pegawai_id = '.$dd['role_pengajar_id'])->all();
                $d3=HrdxPegawai::find()->where('pegawai_id = '.$dd['role_pengajar_id3'])->all();
                $pengajaran=AdakPengajaran::find()->where('pengajaran_id = '.$dd['pengajaran_id'])->all();
                foreach ($pengajaran as $peng) {
                    $matakuliah = Kuliah::find()->where('kuliah_id = '.$peng['kuliah_id'])->all();
                    foreach ($matakuliah as $cs) {
                        $semester = $cs['sem'];
                        $sks = $cs['sks'];
                    }
                }

                $kelas = Kelas::find()->where('kelas_id = '.$dd['kelas'])->all();
        if($semester == $ss['sem']){
            echo '<tr> <td align="center">';

        echo '</td><td align="center">';
            foreach($kelas as $kk){
                    $prodi = Prodi::find()->where('ref_kbk_id = '.$kk['prodi_id'])->all();
                    foreach($prodi as $pr){
                        echo $pr['singkatan_prodi'];
                    }
                }
             echo '</td><td align="center">'; 
             if($ss['sem']==1){
                echo 'I';
            }else if($ss['sem']==2){
                echo 'II';
            }else if($ss['sem']==3){
                echo 'III';
            }else if($ss['sem']==4){
                echo 'IV';
            }else if($ss['sem']==5){
                echo 'V';
            }else if($ss['sem']==6){
                echo 'VI';
            }else if($ss['sem']==7){
                echo 'VII';
            }else if($ss['sem']==8){
                echo 'VIII';
            }
             echo '</td><td>'; 
                foreach($pengajaran as $pp){
                    $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                    foreach($kuliah as $kul){
                        echo $kul['kode_mk'];
                    }
                }
            echo '</td><td>'; 
                foreach($pengajaran as $pp){
                    $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                    foreach($kuliah as $kul){
                        echo $kul['nama_kul_ind'];
                    }
                }
            
            echo '</td><td align="center">';
            foreach($pengajaran as $pp){
                $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                foreach($kuliah as $ku){
                    echo $ku['short_name'];
                }

            }
            echo'</td><td align="center">';
            echo $dd['jumlah_kelas_riil']; 
                    
            echo '</td><td align="center">';
                echo $sks;
            echo '</td><td align="center">';

            echo '</td><td align="center">';

            echo '</td><td align="center">';
                echo $dd['kelas_tatap_muka'];
            echo '</td><td align="center">';

            echo '</td><td align="center">';

            echo '</td><td align="center">';
                foreach($pId as $p){
                        echo $p['nama'];
                }
            echo '</td><td align="center">';
                foreach($d2 as $p){
                        echo $p['nama'];
                }
            echo '</td><td align="center">';
                foreach($d3 as $p){
                        echo $p['nama'];
                }
            echo '</td><td align="center">';
/* %dosen1*/echo ($dd['load']/(($sks+$dd['kelas_tatap_muka']*$sks+$sks*$dd['jumlah_kelas_riil'])/3)*100);
            echo '</td><td align="center">';
/* %dosen2*/echo ($dd['load2']/(($sks+$dd['kelas_tatap_muka']*$sks+$sks*$dd['jumlah_kelas_riil'])/3)*100);
            echo '</td><td align="center">';
/* %dosen3*/echo ($dd['load3']/(($sks+$dd['kelas_tatap_muka']*$sks+$sks*$dd['jumlah_kelas_riil'])/3)*100);
            echo '</td><td align="center">';
            if($dd['load']!=null){
/* loadosen1*/echo $dd['load'];
            }else{echo '0';}
            echo '</td><td align="center">';
            if($dd['load2']!=null){
/* loadosen2*/echo $dd['load2'];
            }else{echo '0';}
            echo '</td><td align="center">';
            if($dd['load3']!=null){
/* loadosen3*/echo $dd['load3'];
            }else{echo '0';}
            echo '</td></tr>';    
        }
        

        }
    }  
    echo '</table>'; 
    }
    //Approve request Penugasan Pengajaran Oleh Dekan
    //$idAkun=id request yanng telah di parsing
    public function actionApprover($idAkun){
     Yii::$app->db->createCommand('update rppx_penugasan_pengajaran set approved=1 where penugasan_pengajaran_id='.$idAkun)->execute();
        return $this->render('Approval');

    }

    //Reject request Penugasan Pengajaran Oleh Dekan
    //$idAkun=id request yanng telah di parsing
     public function actionRejecter($idAkun){
     Yii::$app->db->createCommand('update rppx_penugasan_pengajaran set approved=2 where penugasan_pengajaran_id='.$idAkun)->execute();
        return $this->render('Approval');

    }

    //Approve request Penugasan Pengajaran Oleh Kepala GBK
    //$idAkun=id request yanng telah di parsing
    public function actionGbkapprove($idAkun){
     Yii::$app->db->createCommand('update rppx_penugasan_pengajaran set gbk_approved=1 where penugasan_pengajaran_id='.$idAkun)->execute();
        return $this->render('ApprovalByGBK');

    }

    //Reject request Penugasan Pengajaran Oleh Kepala GBK
    //$idAkun=id request yanng telah di parsing
    public function actionGbkreject($idAkun){

     Yii::$app->db->createCommand('update rppx_penugasan_pengajaran set gbk_approved=2 where penugasan_pengajaran_id='.$idAkun)->execute();

// Instantiation and passing `true` enables exceptions
// $mail = new PHPMailer(true);

// try {
//     //Server settings
//     $mail->SMTPDebug = 2;                                       // Enable verbose debug output
//     $mail->isSMTP();                                            // Set mailer to use SMTP
//     $mail->Host       = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
//     $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
//     $mail->Username   = 'hendrasimz92@gmail.com';                     // SMTP username
//     $mail->Password   = 'simangunsong77';                               // SMTP password
//     $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
//     $mail->Port       = 587;                                    // TCP port to connect to

//     //Recipients
//     $mail->setFrom('hendrasimz92@gmail.com', 'Mailer');
//     $mail->addAddress('hendrasimz92@gmail.net', 'Joe User');     // Add a recipient
//     $mail->addAddress('hendrasimz92@gmail.com');               // Name is optional
//     $mail->addReplyTo('hendrasimz92@gmail.com', 'Information');
//     $mail->addCC('hendrasimz92@gmail.com');
//     $mail->addBCC('hendrasimz92@gmail.com');

//     // Attachments    // Optional name

//     // Content
//     $mail->isHTML(true);                                  // Set email format to HTML
//     $mail->Subject = 'Rencana Penugasan Pengajaran';
//     $mail->Body    = '<b>in bold!</b>';
//     $mail->AltBody = 'Your request is rejected';

//     $mail->send();
//     echo 'Message has been sent';
// } catch (Exception $e) {
//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
// }

        return $this->render('ApprovalByGBK');



    }
    public function actionSems(){
        return $this->render('SemesterPendek');
    }
}

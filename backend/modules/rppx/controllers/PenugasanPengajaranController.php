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
        if ($model->load(Yii::$app->request->post())) {
            if($model->role_pengajar_id==""){
                $model->role_pengajar_id=0;
            }
            if($model->role_pengajar_id3==""){
                $model->role_pengajar_id3=0;
            }
             Yii::$app->db->createCommand('update krkm_kuliah set stat_created=1 where kuliah_id='.$model->pengajaran_id)->execute();
            $model->save(false);
             return $this->render('create', [
                'model' => $model,
                'jlhDosen'=>$jlhDosen,
                'jlhAsdos'=>$jlhAsdos,
                'baris'=>$baris,
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
        $data=PenugasanPengajaran::find()->all();


        // header("Content-type: application/vnd-ms-excel");
        // header("Content-Disposition: fdf_get_attachment(fdf_document, fieldname, savepath)nt; filename=Data Pegawai.xls");
        echo "<h1></h1>";
        echo '<table border="1">
        <tr ><td colspan="9"><center><h1>Penugasan Pengajaran</h1></center></td></tr>
        <tr>
            <th>Prodi</th>
            <th>Semester</th>
            <th>Kode MK</th>
            <th>Nama Mata Kuliah</th>
            <th>Short Name</th>
            <th>Jumlah Kelas Riil</th>
            <th>SKS</th>
            <th>Kelas Tatap Muka</th>
            <th>Dosen</th>
        </tr>';
        foreach($data as $dd){
                $pId=HrdxPegawai::find()->where('pegawai_id = '.$dd['pegawai_id'])->all();
                $pengajaran=AdakPengajaran::find()->where('pengajaran_id = '.$dd['pengajaran_id'])->all();
                $kelas = Kelas::find()->where('kelas_id = '.$dd['kelas'])->all();
        echo '<tr> <td>';
            foreach($kelas as $kk){
                    $prodi = Prodi::find()->where('ref_kbk_id = '.$kk['prodi_id'])->all();
                    foreach($prodi as $pr){
                        echo $pr['singkatan_prodi'];
                    }
                }
                echo '</td> <td>';
                foreach($pengajaran as $pp){
                $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                foreach($kuliah as $ku){
                    echo $ku['sem'];
                }
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
            
            echo '</td><td>';
            foreach($pengajaran as $pp){
                $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                foreach($kuliah as $ku){
                    echo $ku['short_name'];
                }

            }
            echo'</td><td>';
            echo $dd['jumlah_kelas_riil']; 
            echo '</td><td>';
            foreach($pengajaran as $pp){
                $kuliah = Kuliah::find()->where('kuliah_id = '.$pp['kuliah_id'])->all();
                foreach($kuliah as $ku){
                    echo $ku['sks'];
                }
            }
                    
            echo '</td><td>';
            echo $dd['kelas_tatap_muka']; 
            echo '</td><td>';
                foreach($pId as $p){
                        echo $p['nama'];
                }
            
            echo '</td></tr>';
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
        return $this->render('ApprovalByGBK');
    }
}

<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\PicBarang;
use backend\modules\invt\models\search\PicBarangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\invt\models\UnitCharged;
use backend\modules\invt\models\PengeluaranBarang;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\search\PengeluaranBarangSearch;
use backend\modules\invt\models\Lokasi;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\invt\models\PicBarangFile;

//file
use yii\web\UploadedFile;
/**
 * PicBarangController implements the CRUD actions for PicBarang model.
 */
class PicBarangController extends Controller
{
    public $menuGroup = "m-distribusi";
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PicBarang models.
     * @return mixed
     */
    public function actionPicBarangBrowse()
    {
        $user_id = Yii::$app->user->id;
        $_getUnits = UnitCharged::getUnitbyUser($user_id);

        if($_getUnits==null||empty($_getUnits)){
            Yii::$app->messenger->addWarningFlash("Anda bukan salah satu dari admin unit");
            return $this->redirect(Url::to(['barang/barang-browse']));
        }


        $searchModel = new PicBarangSearch();
        $dataProviders[][] = null;
        $i=0;
        foreach ($_getUnits as $key => $_unit) {
            $_query = PicBarang::find()
                                ->where(['unit_id'=>$_unit->unit_id])
                                ->orderBy(['tgl_assign'=>SORT_DESC]);
            $dataProviders[$i]['dataProvider'] = $searchModel->search(Yii::$app->request->queryParams,$_query);
            $dataProviders[$i]['unit'] = $_unit->unit->nama;
            $dataProviders[$i]['unit_id'] = $_unit->unit_id;
            $i++;
        }
        return $this->render('PicBarangBrowse', [
            'searchModel' => $searchModel,
            'dataProviders' => $dataProviders,
        ]);
    }

    /**
     * Displays a single PicBarang model.
     * @param integer $id
     * @return mixed
     */
    //menampilkan daftar distribusi barang dari sebuah unit : assign pic barang
    public function actionListDistribusi($unit_id){
        //get status distribusi/pindah
        $_statusDistribusi = Yii::$app->appConfig->get('invt_status_distribusi');
        $_statusPindah = Yii::$app->appConfig->get('invt_status_pindah');
        $_namaUnit = Unit::getNamaUnit($unit_id);
        $searchModel = new PengeluaranBarangSearch();
        $_query = PengeluaranBarang::find()
                                    ->where(['unit_id'=>$unit_id])
                                    ->andWhere(['status_akhir'=>$_statusDistribusi])
                                    ->orWhere(['status_akhir'=>$_statusPindah])
                                    ->andWhere(['is_has_pic'=>0])
                                    ->andWhere(['deleted'=>0])
                                    ->orderBy(['tgl_keluar'=>SORT_DESC]);
        $_cek = $_query->all();
        // echo "<pre>";
        // print_r($_cek);die;
        //cek distribusi barang sudah ada
        if($_cek!=null){
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$_query);
            $_lokasi = Lokasi::find()
                                ->where(['deleted'=>0])
                                ->all();
            return $this->render('ListDistribusi',[
                'searchModel'=>$searchModel,
                'dataProvider'=>$dataProvider,
                '_namaUnit'=>$_namaUnit,
                '_lokasi'=>$_lokasi,
            ]);
        }else{
            Yii::$app->messenger->addWarningFlash("Distribusi barang tidak ditemukan pada unit : <strong>".$_namaUnit."</strong>. Silahkan melakukan distribusi terlebih dahulu.");
            return $this->redirect(['pengeluaran-barang/barang-keluar-browse']);
        }
    }


    /**
     * Creates a new PicBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPicBarangAdd($pengeluaran_barang_id,$unit_id)
    {
        $modelPengeluaran = PengeluaranBarang::find()
                                            ->where(['pengeluaran_barang_id'=>$pengeluaran_barang_id])
                                            ->andWhere(['deleted'=>0])
                                            ->one();
        $modelPegawai = Pegawai::find()
                                ->where(['status_aktif_pegawai_id'=>1, 'deleted'=>0, 'tanggal_keluar'=>0])
                                ->orderBy(['nama'=>SORT_ASC])
                                ->all();
        $model = new PicBarang();

        if ($model->load(Yii::$app->request->post())) {
            //update PengeluaranBarang : is_has_pic
            $modelPengeluaran->is_has_pic = 1;
            $modelPengeluaran->save();


            $model->pengeluaran_barang_id = $pengeluaran_barang_id;
            $model->unit_id = $unit_id;
            $model->save();

            //save file
            $_files = $_FILES['files']['name'];
            if($_files[0]!=""){
                $objFiles = Yii::$app->fileManager->saveUploadedFiles();
                if($objFiles->status == 'success'){
                    foreach ($objFiles->fileinfo as $key => $value) {
                       $_arsipFile = new PicBarangFile();
                       $_arsipFile->pic_barang_id = $model->pic_barang_id;
                       $_arsipFile->nama_file = $value->name;
                       $_arsipFile->kode_file = $value->id;
                       $_arsipFile->save();
                    }
                }
            }
            return $this->redirect(['pic-barang-view', 'id' => $model->pic_barang_id]);
        } else {
            return $this->render('PicBarangAdd', [
                'model' => $model,
                'modelPengeluaran'=>$modelPengeluaran,
                'modelPegawai'=>$modelPegawai,
            ]);
        }
    }


    public function actionPicBarangView($id)
    {
        $model = $this->findModel($id);
        $modelPicBarangFile = $model->picBarangFile;
        return $this->render('PicBarangView', [
            'model' => $model,
            'modelPicBarangFile'=>$modelPicBarangFile,
        ]);
    }
    /**
     * Updates an existing PicBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPicBarangEdit($id)
    {
        $model = $this->findModel($id);
        $modelPengeluaran = PengeluaranBarang::find()
                                            ->where(['pengeluaran_barang_id'=>$model->pengeluaran_barang_id])
                                            ->andWhere(['deleted'=>0])
                                            ->one();
        $modelPegawai = Pegawai::find()
                                ->where(['status_aktif_pegawai_id'=>1, 'deleted'=>0, 'tanggal_keluar'=>0])
                                ->orderBy(['nama'=>SORT_ASC])
                                ->all();
        $fileList = $model->picBarangFile;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //save file
            $_files = $_FILES['files']['name'];
            if($_files[0]!=""){
                $objFiles = Yii::$app->fileManager->saveUploadedFiles();
                if($objFiles->status == 'success'){
                    foreach ($objFiles->fileinfo as $key => $value) {
                       $_arsipFile = new PicBarangFile();
                       $_arsipFile->pic_barang_id = $model->pic_barang_id;
                       $_arsipFile->nama_file = $value->name;
                       $_arsipFile->kode_file = $value->id;
                       $_arsipFile->save();
                    }
                }
            }
            return $this->redirect(['pic-barang-view', 'id' => $model->pic_barang_id]);
        } else {
            return $this->render('PicBarangEdit', [
                'model' => $model,
                'modelPengeluaran'=>$modelPengeluaran,
                'modelPegawai'=>$modelPegawai,
                'fileList'=>$fileList,
            ]);
        }
    }

    /**
     * Deletes an existing PicBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPicBarangDel($id)
    {
        $model = $this->findModel($id);
        $modelPicBarangFile = $model->picBarangFile;
        $modelPengeluaran = $model->pengeluaranBarang;

        if($model->softDelete()){
            //update pengeluaran barang : is_has_pic = 0
            $modelPengeluaran->is_has_pic = 0;
            $modelPengeluaran->save();

            foreach ($modelPicBarangFile as $key => $value) {
                //dari puro
               \Yii::$app->fileManager->delete($value->kode_file);
               //hapus dari database
               $value->delete();
            }
            Yii::$app->messenger->addInfoFlash("PIC Barang <strong>".$model->pengeluaranBarang->kode_inventori."</strong> berhasip dihapus.");
        }
        return $this->redirect(['pic-barang-browse']);
    }

    public function actionPicFileDel($pic_barang_file_id, $pic_barang_id){
        $_picFile = PicBarangFile::findOne(['pic_barang_file_id'=>$pic_barang_file_id]);
        //hapus dari puro dan dari database
        \Yii::$app->fileManager->delete($_picFile->kode_file);
        $_picFile->delete();
        return $this->redirect(['pic-barang-edit','id'=>$pic_barang_id]);
    }
    /**
     * Finds the PicBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PicBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PicBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\PengeluaranBarang;
use backend\modules\invt\models\search\KeteranganPengeluaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use backend\modules\invt\models\Barang;
use backend\modules\invt\models\search\LokasiSearch;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\UnitCharged;
use backend\modules\invt\models\PemindahanBarang;
use backend\modules\invt\models\search\BarangSearch;
use backend\modules\invt\models\SummaryJumlah;
use backend\modules\invt\models\KeteranganPengeluaran;
use backend\modules\invt\models\search\PengeluaranBarangSearch;
/**
 * PengeluaranBarangController implements the CRUD actions for PengeluaranBarang model.
 */
class PengeluaranBarangController extends Controller
{
    public $menuGroup = "m-distribusi";
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
     * Lists all PengeluaranBarang models.
     * @return mixed
     */
    public function actionBarangKeluarBrowse()
    {
        $user_id = Yii::$app->user->id;
        $_getUnits = UnitCharged::getUnitbyUser($user_id);

        if($_getUnits==null||empty($_getUnits)){
            Yii::$app->messenger->addWarningFlash("Anda bukan salah satu dari admin unit");
            return $this->redirect(Url::to(['barang/barang-browse']));
        }
        $searchModel = new KeteranganPengeluaranSearch();
        $dataProviders[][]=null;
        $i=0;
        $_statusDistribusi = Yii::$app->appConfig->get('invt_status_distribusi');
        foreach ($_getUnits as $key => $_unit) {
            $_query = KeteranganPengeluaran::find()
                                        ->where(['unit_id'=>$_unit->unit_id])
                                        ->andWhere(['deleted'=>0])
                                        ->orderBy(['tgl_keluar'=>SORT_DESC]);
            $dataProviders[$i]['dataProvider']=$searchModel->search(Yii::$app->request->queryParams,$_query);
            $dataProviders[$i]['unit']=$_unit->unit->nama;
            $dataProviders[$i]['unit_id'] = $_unit->unit_id;
            $i++;
        }
        $_lokasi = Lokasi::find()
                            ->where(['deleted'=>0])
                            ->orderBy(['lokasi_id'=>SORT_ASC])
                            ->all();
        return $this->render('BarangKeluarBrowse', [
            'searchModel' => $searchModel,
            'dataProviders' => $dataProviders,
            '_lokasi'=>$_lokasi,
        ]);
    }

    //menampilkan list lokasi yang akan dibuatkan distribusi barang
    public function actionListLokasi()
    {
        if($this->isExistBarang()==false){
            Yii::$app->messenger->addWarningFlash('Barang tidak ditemukan. Silahkan tambahkan barang terlebih dahulu.');
            return $this->redirect(['barang-keluar-browse']);
        }elseif($this->isOutOfStock()==true){
            Yii::$app->messenger->addWarningFlash('Barang <i>out of stock</i>. Silahkan tambahkan barang.');
            return $this->redirect(['barang-keluar-browse']);
        }

        $lokasis = Lokasi::find()->where(['deleted'=>0]);
        $searchModel = new LokasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$lokasis);
        return $this->render('ListLokasi',[
                'searchModel'=>$searchModel,
                'dataProvider'=>$dataProvider,
        ]);
    }


    //cek barang apakah barang ada di gudang
    private function isExistBarang(){
        $_isExistBarang = Barang::find()
                            ->where(['deleted'=>0])
                            ->all();
        if($_isExistBarang !=null){
            return true;
        }else{
            return false;
        }
    }

    //cek apakah barang out of stock
    private function isOutOfStock(){
        $_isOutofStock = Barang::find()
                                ->joinWith('summaryJumlah')
                                ->where(SummaryJumlah::tableName().'.jumlah_gudang>:ju',[':ju'=>0])
                                ->andWhere(SummaryJumlah::tableName().'.jumlah_distribusi<'.SummaryJumlah::tableName().'.total_jumlah')
                                ->all();
        if($_isOutofStock ==null){
            return true;
        }else{
            return false;
        }
    }

    //by lokasi and unit
    public function actionDistribusiBylokasi($lokasi_id,$unit_id)
    {
        $searchModel = new BarangSearch();
        $query = Barang::find()
                            ->joinWith('summaryJumlah')
                            //cek dari tabel summary untuk jumlah tersedia
                            ->andWhere(SummaryJumlah::tableName().'.jumlah_gudang>:ju',[':ju'=>0])
                            ->orWhere(SummaryJumlah::tableName().'.jumlah_gudang IS NULL')
                            ->andWhere(Barang::tableName().'.unit_id=:ui',[':ui'=>$unit_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$query, 20);
        $modelBarangKeluarForm = new KeteranganPengeluaran();

        $post = Yii::$app->request->post();
        if(isset($post['kode_inventori']) && isset($post['jumlah'])){
            if(array_filter($post['kode_inventori']) && array_filter($post['jumlah'])){
                $_oleh = Yii::$app->user->id;
                $_kodeInventori = $post['kode_inventori'];
                $_tanggalKeluar = $post['KeteranganPengeluaran']['tgl_keluar'];
                $_keterangan = $post['KeteranganPengeluaran']['keterangan'];
                $_totalBarangKeluar = array_sum($post['jumlah']);

                //cek is keterangan pengeluaran exist
                $_cekPengeluaran = KeteranganPengeluaran::find()
                                                        ->where(['unit_id'=>$unit_id])
                                                        ->andWhere(['tgl_keluar'=>$_tanggalKeluar])
                                                        ->andWhere(['lokasi_distribusi'=>$lokasi_id])
                                                        ->andWhere(['deleted'=>0])
                                                        ->one();
                if(isset($_cekPengeluaran) && $_cekPengeluaran!=null ){
                    Yii::$app->messenger->addWarningFlash('Distribusi pada lokasi <strong>'.Lokasi::getNamaLokasi($lokasi_id).'</strong>'.
                                                ' tanggal: <strong>'.$_tanggalKeluar.'</strong> sudah ada. Silahkan tambahkan barang pada distribusi tersebut.');
                    return $this->redirect('barang-keluar-browse');
                }
                //save data keterangan pengeluaran
                $modelBarangKeluarForm->tgl_keluar = $_tanggalKeluar;
                $modelBarangKeluarForm->keterangan = $_keterangan;
                $modelBarangKeluarForm->lokasi_distribusi = $lokasi_id;
                $modelBarangKeluarForm->unit_id = $unit_id;
                $modelBarangKeluarForm->oleh = $_oleh;
                $modelBarangKeluarForm->total_barang_keluar = $_totalBarangKeluar;
                $modelBarangKeluarForm->save();

                //save data pengeluaran barang (kode_inventori)
                foreach ($_kodeInventori as $key => $valueKode) {
                    $jumlah = (int)$post['jumlah'][$key];
                    if(($valueKode!='' && isset($valueKode)) && ($jumlah!=0 && $jumlah!="" && isset($jumlah))){
                        //cek barang dan lokasi
                        $_cekBarang = PengeluaranBarang::find()
                                                       ->where(['lokasi_id'=>$lokasi_id])
                                                       ->andWhere(['barang_id'=>$key])
                                                       ->andWhere(['deleted'=>0])
                                                       ->orderBy(['SUBSTRING(kode_inventori,-1)'=>SORT_DESC])
                                                       ->one();
                        //get jumlah => kode inventori : unik
                       $_initiateNumber = 1;
                       if(!empty($_cekBarang)||$_cekBarang!=null){
                        $_initiateNumber = (int)substr($_cekBarang->kode_inventori, -1)+1;
                       }
                        for($i=1;$i<=$jumlah;$i++){
                            $modelPengeluaran = new PengeluaranBarang();
                            $modelPengeluaran->barang_id = $key;
                            $modelPengeluaran->keterangan_pengeluaran_id = $modelBarangKeluarForm->keterangan_pengeluaran_id;
                            $modelPengeluaran->lokasi_id = $lokasi_id;
                            $modelPengeluaran->kode_inventori = $valueKode."/".$_initiateNumber;
                            $modelPengeluaran->jumlah = 1;
                            $modelPengeluaran->tgl_keluar = $_tanggalKeluar;
                            $modelPengeluaran->unit_id = $unit_id;
                            $modelPengeluaran->save();
                            $_initiateNumber +=1;
                        }

                        /**update jumlah gudang //diganti jadi update jumlah di tabel summary
                        //cek summary**/
                        $_barangOne = Barang::find()
                                                ->where(['barang_id'=>$key])
                                                ->one();
                        $_minusJumlah = $_barangOne->jumlah - $jumlah;
                        $_cekSUmmaryJumlah = SummaryJumlah::find()
                                                            ->where(['barang_id'=>$key])
                                                            ->andWhere(['deleted'=>0])
                                                            ->one();
                        if(!isset($_cekSUmmaryJumlah)){
                            $modelSummary = new SummaryJumlah();
                            $modelSummary->barang_id = $key;
                            $modelSummary->kategori_id = $_barangOne->kategori->kategori_id;
                            $modelSummary->total_jumlah = $_barangOne->jumlah;
                            $modelSummary->jumlah_distribusi = $modelSummary->jumlah_distribusi + $jumlah;
                            $modelSummary->jumlah_gudang = $modelSummary->jumlah_gudang + $_minusJumlah;
                            $modelSummary->save();
                        }else{
                            $_cekSUmmaryJumlah->jumlah_distribusi = $_cekSUmmaryJumlah->jumlah_distribusi + $jumlah;
                            $_cekSUmmaryJumlah->jumlah_gudang = $_cekSUmmaryJumlah->jumlah_gudang - $jumlah;
                            $_cekSUmmaryJumlah->save();
                        }
                       Yii::$app->messenger->addSuccessFlash("<strong>".$modelPengeluaran->barang->nama_barang."</strong>".
                                                            " sebanyak ".$jumlah.
                                                            " ".
                                                            $modelPengeluaran->barang->satuan->nama.
                                                            ", berhasil didistribusikan ke ".
                                                            $modelPengeluaran->lokasi->nama_lokasi);
                    }
                }
                return $this->redirect('barang-keluar-browse');
            }else{
                return $this->render ('DistribusiBylokasi',[
                    'searchModel'=>$searchModel,
                    'dataProvider'=>$dataProvider,
                    'modelBarangKeluarForm'=>$modelBarangKeluarForm,
                ]);
            }
        }else{
                return $this->render ('DistribusiBylokasi',[
                    'searchModel'=>$searchModel,
                    'dataProvider'=>$dataProvider,
                    'modelBarangKeluarForm'=>$modelBarangKeluarForm,
                ]);
            }
    }

    //by keterangan pengeluaran
    public function actionTambahBarangDistribusi($keterangan_pengeluaran_id)
    {
        $searchModel = new BarangSearch();
        $modelKeterangan = KeteranganPengeluaran::find()
                                                ->where(['keterangan_pengeluaran_id'=>$keterangan_pengeluaran_id])
                                                ->andWhere(['deleted'=>0])
                                                ->one();
        $query = Barang::find()
                            ->joinWith('summaryJumlah')
                            //cek dari tabel summary untuk jumlah tersedia
                            ->andWhere(SummaryJumlah::tableName().'.jumlah_gudang>:ju',[':ju'=>0])
                            ->orWhere(SummaryJumlah::tableName().'.jumlah_gudang IS NULL')
                            ->andWhere(Barang::tableName().'.unit_id=:ui',[':ui'=>$modelKeterangan->unit_id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$query, 25);
        $post = Yii::$app->request->post();
        if(isset($post['kode_inventori']) && isset($post['jumlah'])){
            if(array_filter($post['kode_inventori']) && array_filter($post['jumlah'])){
                $_kodeInventori = $post['kode_inventori'];
                //update jumlah barang keluar
                $modelKeterangan->total_barang_keluar = $modelKeterangan->total_barang_keluar + array_sum($post['jumlah']);
                $modelKeterangan->save();
                //save data pengeluaran barang (kode_inventori)
                foreach ($_kodeInventori as $key => $valueKode) {
                    $jumlah = (int)$post['jumlah'][$key];
                    if(($valueKode!='' && isset($valueKode)) && ($jumlah!=0 && $jumlah!="" && isset($jumlah))){
                        //cek barang dan lokasi
                        $_cekBarang = PengeluaranBarang::find()
                                                       ->where(['lokasi_id'=>$modelKeterangan->lokasi_distribusi])
                                                       ->andWhere(['barang_id'=>$key])
                                                       ->andWhere(['deleted'=>0])
                                                       ->orderBy(['SUBSTRING(kode_inventori,-1)'=>SORT_DESC])
                                                       ->one();
                        //get jumlah => kode inventori : unik
                       $_initiateNumber = 1;
                       if(!empty($_cekBarang)||$_cekBarang!=null){
                        $_initiateNumber = (int)substr($_cekBarang->kode_inventori, -1)+1;
                       }
                        for($i=1;$i<=$jumlah;$i++){
                            $modelPengeluaran = new PengeluaranBarang();
                            $modelPengeluaran->barang_id = $key;
                            $modelPengeluaran->keterangan_pengeluaran_id = $keterangan_pengeluaran_id;
                            $modelPengeluaran->lokasi_id = $modelKeterangan->lokasi_distribusi;
                            $modelPengeluaran->kode_inventori = $valueKode."/".$_initiateNumber;
                            $modelPengeluaran->jumlah = 1;
                            $modelPengeluaran->tgl_keluar = $modelKeterangan->tgl_keluar;
                            $modelPengeluaran->unit_id = $modelKeterangan->unit_id;
                            $modelPengeluaran->save();
                            $_initiateNumber +=1;
                        }

                        /**update jumlah gudang //diganti jadi update jumlah di tabel summary
                        //cek summary**/
                        $_barangOne = Barang::find()
                                                ->where(['barang_id'=>$key])
                                                ->one();
                        $_minusJumlah = $_barangOne->jumlah - $jumlah;
                        $_cekSUmmaryJumlah = SummaryJumlah::find()
                                                            ->where(['barang_id'=>$key])
                                                            ->andWhere(['deleted'=>0])
                                                            ->one();
                        if(!isset($_cekSUmmaryJumlah)){
                            $modelSummary = new SummaryJumlah();
                            $modelSummary->barang_id = $key;
                            $modelSummary->kategori_id = $_barangOne->kategori->kategori_id;
                            $modelSummary->total_jumlah = $_barangOne->jumlah;
                            $modelSummary->jumlah_distribusi = $modelSummary->jumlah_distribusi + $jumlah;
                            $modelSummary->jumlah_gudang = $modelSummary->jumlah_gudang + $_minusJumlah;
                            $modelSummary->save();
                        }else{
                            $_cekSUmmaryJumlah->jumlah_distribusi = $_cekSUmmaryJumlah->jumlah_distribusi + $jumlah;
                            $_cekSUmmaryJumlah->jumlah_gudang = $_cekSUmmaryJumlah->jumlah_gudang - $jumlah;
                            $_cekSUmmaryJumlah->save();
                        }
                       Yii::$app->messenger->addSuccessFlash("<strong>".$modelPengeluaran->barang->nama_barang."</strong>".
                                                            " sebanyak ".$jumlah.
                                                            " ".
                                                            $modelPengeluaran->barang->satuan->nama.
                                                            ", berhasil didistribusikan ke ".
                                                            $modelPengeluaran->lokasi->nama_lokasi);
                    }
                }
                return $this->redirect('barang-keluar-browse');
            }else{
                return $this->render ('TambahBarangDistribusi',[
                    'searchModel'=>$searchModel,
                    'dataProvider'=>$dataProvider,
                    'modelKeterangan'=>$modelKeterangan,
                ]);
            }
        }else{
                return $this->render ('TambahBarangDistribusi',[
                    'searchModel'=>$searchModel,
                    'dataProvider'=>$dataProvider,
                    'modelKeterangan'=>$modelKeterangan,
                ]);
            }
    }

    public function actionDetailBarangKeluar($keterangan_pengeluaran_id){
        $modelKeterangan = KeteranganPengeluaran::find()
                                                    ->where(['deleted'=>0])
                                                    ->andWhere(['keterangan_pengeluaran_id'=>$keterangan_pengeluaran_id])
                                                    ->one();
        $model = PengeluaranBarang::find()
                                    ->where(['keterangan_pengeluaran_id'=>$keterangan_pengeluaran_id])
                                    ->andWhere(['deleted'=>0])
                                    ->orderBy(['tgl_keluar'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
                'query'=>$model,
                'pagination'=>['pageSize'=>6],
        ]);
        $detailBarang = PengeluaranBarang::find()
                                    ->select(['*','jumlahBarang'=>'SUM(jumlah)'])
                                    ->where(['keterangan_pengeluaran_id'=>$keterangan_pengeluaran_id])
                                    ->andWhere(['deleted'=>0])
                                    ->groupBy(['barang_id'])
                                    ->all();
        $detailLokasi = PengeluaranBarang::find()
                                    ->select(['*','jum_barang_lokasi'=>'SUM(jumlah)'])
                                    ->where(['keterangan_pengeluaran_id'=>$keterangan_pengeluaran_id])
                                    ->andWhere(['deleted'=>0])
                                    ->groupBy(['lokasi_id'])
                                    ->all();
        return $this->render('DetailBarangKeluar',[
            'modelKeterangan'=>$modelKeterangan,
            'dataProvider'=>$dataProvider,
            'detailBarang'=>$detailBarang,
            'detailLokasi'=>$detailLokasi,
        ]);
    }

/**
 *Detail Lokasi Barang
 */
    public function actionLokasiBarang()
    {
        $lokasis = Lokasi::find()->where(['deleted'=>0])
                                ->orderBy(['lokasi_id'=>SORT_ASC]);
        $searchModel = new LokasiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$lokasis);
        return $this->render('LokasiBarang',[
                'searchModel'=>$searchModel,
                'dataProvider'=>$dataProvider,
            ]);
    }

    //menampilkan daftar barang dalam bentuk tree
    public function actionBarangBylokasi($lokasi_id)
    {
        $model = Lokasi::find()
                        ->where(['lokasi_id'=>$lokasi_id])
                        ->one();
        return $this->render('BarangBylokasi',[
            'model'=>$model,
        ]);
    }

    //menampilkan detail daftar barang dalam suatu lokasi
    public function actionDetailBarangBylokasi($lokasi_id)
    {
        $nama_lokasi = Lokasi::getNamaLokasi($lokasi_id);
        $searchModel = new PengeluaranBarangSearch();
        $query = PengeluaranBarang::find()
                            ->where(['lokasi_id'=>$lokasi_id])
                            ->andWhere(['deleted'=>0])
                            ->orderBy(['tgl_keluar'=>SORT_DESC]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $query);
        return $this->render('DetailBarangByLokasi',[
                'searchModel'=>$searchModel,
                'dataProvider'=>$dataProvider,
                'nama_lokasi'=>$nama_lokasi,
            ]);
    }
/**
 *End of Detail Lokasi Barang
 */
    /**
     * Updates an existing PengeluaranBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
/*
    Lokasi barang berdasarkan unit
*/
    //lokasi barang berdasarkan unit
    public function actionLokasiBarangByunit(){
        //user_id
        $user_id = Yii::$app->user->id;
        $_getUnits = UnitCharged::getUnitbyUser($user_id);
        if($_getUnits==null || empty($_getUnits)){
            Yii::$app->messenger->addWarningFlash("Anda bukan admin salah satu unit");
        }
        $searchModel = new PengeluaranBarangSearch();
        $dataProviders[][]=null;
        $i=0;
        foreach ($_getUnits as $key => $_unit) {
            $query = PengeluaranBarang::find()
                                        ->select(['jumlahBarang'=>'SUM(jumlah)','lokasi_id'])
                                        ->Where(['unit_id'=>$_unit->unit_id])
                                        ->groupBy(['lokasi_id'])
                                        ->orderBy(['lokasi_id'=>SORT_ASC]);
            $dataProviders[$i]['dataProvider'] = $searchModel->search(Yii::$app->request->queryParams,$query);
            $dataProviders[$i]['unit'] = $_unit->unit->nama;
            $dataProviders[$i]['unit_id'] = $_unit->unit_id;
            $i++;
        }
        return $this->render('LokasiBarangByunit',[
            'dataProviders'=>$dataProviders,
            'searchModel'=>$searchModel,
        ]);
    }
    //detail lokasi barang berdasarkan lokasi dan unit
    public function actionDetailByunitlokasi($unit_id,$lokasi_id){
        $modelPengeluaran = PengeluaranBarang::find()
                                            ->select(['jumlahBarang'=>'SUM(jumlah)', 'barang_id'])
                                            ->where(['deleted'=>0])
                                            ->andWhere(['unit_id'=>$unit_id])
                                            ->andWhere(['lokasi_id'=>$lokasi_id])
                                            ->groupBy(['barang_id']);
        $unit = Unit::getNamaUnit($unit_id);
        $lokasi = Lokasi::getNamaLokasi($lokasi_id);
        $dataProvider = new ActiveDataProvider([
                'query'=>$modelPengeluaran,
                'pagination'=>['pageSize'=>20],
            ]);
        return $this->render('DetailByunitlokasi',[
            'dataProvider'=>$dataProvider,
            'unit'=>$unit,
            'lokasi'=>$lokasi,
            'unit_id'=>$unit_id,
            'lokasi_id'=>$lokasi_id,
        ]);
    }
/*
    End lokasi barang berdasarkan unit
*/
    public function actionBarangKeluarEdit($id)
    {
        $model = $this->findModel($id);
        $_lokasi = Lokasi::find()
                            ->where(['deleted'=>0])
                            ->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //ambil kode inventori terakhir dengan lokasi yang dipilih
            $_initiateNumber = 1;
            $_lokasi = $_POST['PengeluaranBarang']['lokasi_id'];
            $_cekModel = PengeluaranBarang::find()
                                            ->where(['barang_id'=>$model->barang_id])
                                            ->andWhere(['lokasi_id'=>$_lokasi])
                                            ->andWhere(['deleted'=>0])
                                            ->andWhere(['<>','pengeluaran_barang_id', $model->pengeluaran_barang_id])
                                            ->orderBy(['SUBSTRING(kode_inventori,-1)'=>SORT_DESC])
                                            ->one();
            if(!empty($_cekModel)|| $_cekModel!=null){
                $_initiateNumber = (int)substr($_cekModel->kode_inventori, -1)+1;
            }
            $model->kode_inventori = $_POST['PengeluaranBarang']['kode_inventori']."/".$_initiateNumber;
            $model->save();
            return $this->redirect(['barang-keluar-browse']);
        } else {
            return $this->render('BarangKeluarEdit', [
                'model' => $model,
                '_lokasi'=>$_lokasi,
            ]);
        }
    }

    /**
     * Deletes an existing PengeluaranBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBarangKeluarDel($id)
    {
        //update pengeluaran barang
        $model = $this->findModel($id);

        //update jumlah gudang barang dan hapus pemindahan barang
        if($model->softDelete()){
            $_barang = Barang::find()
                           ->where(['barang_id'=>$model->barang_id])
                           ->one();
            $_barang->jumlah_gudang = $_barang->jumlah_gudang + $model->jumlah;
            $_barang->save();   

            $_barangPindah = PemindahanBarang::find()
                                                ->where(['deleted'=>0])
                                                ->andWhere(['pengeluaran_barang_id'=>$id])
                                                ->all();
            if($_barangPindah!=null){
                foreach ($_barangPindah as $key => $value) {
                    $value->softDelete();
                }
            }
        }
        Yii::$app->messenger->addSuccessFlash("Distribusi Barang dengan kode inventori: ". $model->kode_inventori. " telah dihapus.");
        return $this->redirect(['barang-keluar-browse']);
    }

    /**
     * Finds the PengeluaranBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PengeluaranBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PengeluaranBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

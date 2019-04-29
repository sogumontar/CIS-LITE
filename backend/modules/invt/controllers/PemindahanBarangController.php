<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\PemindahanBarang;
use backend\modules\invt\models\search\PemindahanBarangSearch;
use backend\modules\invt\models\PindahBarangForm;
use backend\modules\invt\models\PengeluaranBarang;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\UnitCharged;
use backend\modules\invt\models\Unit;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * PemindahanBarangController implements the CRUD actions for PemindahanBarang model.
 */
class PemindahanBarangController extends Controller
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
     * Lists all PemindahanBarang models.
     * @return mixed
     */
    public function actionPindahBarangBrowse()
    {
        $user_id = Yii::$app->user->id;
        $_getunits = UnitCharged::getUnitbyUser($user_id);

        if($_getunits ==null || empty($_getunits)){
            Yii::$app->messenger->addWarningFlash("Anda bukan admin salah satu dari unit");
            return $this->redirect(url::to('barang/barang-browse'));
        }
        $searchModel = new PemindahanBarangSearch();
        $dataProviders[][]=null;
        $i=0;
        foreach ($_getunits as $key => $_unit) {
            $query = PemindahanBarang::find()
                                ->join("left join", PemindahanBarang::tableName()." p2", PemindahanBarang::tableName().". pengeluaran_barang_id = p2.pengeluaran_barang_id and ".PemindahanBarang::tableName().".pemindahan_barang_id < p2.pemindahan_barang_id")
                                ->join("join", PengeluaranBarang::tableName(), PemindahanBarang::tableName().".pengeluaran_barang_id = ".PengeluaranBarang::tableName().".pengeluaran_barang_id")
                                ->where(['p2.pemindahan_barang_id'=>null])
                                ->andWhere([PengeluaranBarang::tableName().".unit_id"=>$_unit->unit_id])
                                ->orderBy([PemindahanBarang::tableName().".tanggal_pindah"=>SORT_DESC]);
            $dataProviders[$i]['dataProvider'] = $searchModel->search(Yii::$app->request->queryParams,$query);
            $dataProviders[$i]['unit'] = $_unit->unit->nama;
            $dataProviders[$i]['unit_id'] = $_unit->unit_id;
            $i++;
        }
        $lokasi = Lokasi::find()
                            ->where(['deleted'=>0])
                            ->all();
        return $this->render('PindahBarangBrowse', [
            'searchModel' => $searchModel,
            'dataProviders' => $dataProviders,
            'lokasi'=>$lokasi,
        ]);
    }

    /**
     * Displays a single PemindahanBarang model.
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
     * Creates a new PemindahanBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPindahBarang($unit_id)
    {
        if($unit_id==null){
            Yii::$app->messenger->addErrorFlash("Tidak ditemukan ID unit");
            return $this->redirect(['pindah-barang-browse']);
        }
        $modelForm = new PindahBarangForm();
        $_statusDistribusi = Yii::$app->appConfig->get('invt_status_distribusi');
        $_statusPindah = Yii::$app->appConfig->get('invt_status_pindah');
        $modelPengeluaranBarang = PengeluaranBarang::find()
                                        ->where(['deleted'=>0])
                                        ->andWhere(['unit_id'=>$unit_id])
                                        ->andWhere(['status_akhir'=>$_statusDistribusi])
                                        ->orWhere(['status_akhir'=>$_statusPindah])
                                        ->groupBy(['lokasi_id'])
                                        ->all();
        $modelLokasi = Lokasi::find()
                                ->where(['deleted'=>0])
                                ->all();
        $lokasiAwalList=[];
        $lokasiTujuanList = [];
        //lokasi awal list
        foreach ($modelPengeluaranBarang as $key => $value) {
            $lokasiAwalList[$value->lokasi_id] = $value->lokasi->nama_lokasi;
        }
        //lokasi tujuan list
        foreach ($modelLokasi as $key => $value) {
           $lokasiTujuanList[$value->lokasi_id] = $value->nama_lokasi;
        }

        //simpan data pemindahan
        $post = Yii::$app->request->post();
        if (isset($post['lokasi']) && isset($post['prefiks_kode'])) {
            if(array_filter($post['lokasi']) && array_filter($post['prefiks_kode'])){
                $_tanggalPindah = $post['tanggal_pindah'];
                $_kodeInventori = $post['prefiks_kode'];
                foreach ($_kodeInventori as $key => $value) {
                    list($_barangId,$_barangKeluarId) = explode("|",$key);
                    $_lokasiTujuan = $post['lokasi'][$key];
                    if(($value!=""&&isset($value)) && ($_lokasiTujuan!=""&&isset($_lokasiTujuan))){
                        //cek last kode inventori
                        $_cekKode = PengeluaranBarang::find()
                                                        ->where(['lokasi_id'=>$_lokasiTujuan])
                                                        ->andWhere(['barang_id'=>$_barangId])
                                                        ->andWhere(['deleted'=>0])
                                                        ->andWhere(["<>",'pengeluaran_barang_id',$_barangKeluarId])
                                                        ->orderBy(['SUBSTRING(kode_inventori,-1)'=>SORT_DESC])
                                                        ->one();
                        $_sequenceNumber = 1;
                        if(!empty($_cekKode)|| $_cekKode!=null){
                            $_sequenceNumber = (int)substr($_cekKode->kode_inventori, -1)+1;
                        }
                        $_kodeAkhir = $value."/".$_sequenceNumber;
                        $_oleh = Yii::$app->user->id;

                        //cek transaksi pemindahan barang dengan pengeluaran_barang_id
                        $_cekTransaksi = PemindahanBarang::find()
                                                            ->where(['pengeluaran_barang_id'=>$_barangKeluarId])
                                                            ->andWhere(['deleted'=>0])
                                                            ->one();
                       $modelBarangKeluarOne = PengeluaranBarang::find()
                                                                ->where(['pengeluaran_barang_id'=>$_barangKeluarId])
                                                                ->andWhere(['status_akhir'=>$_statusDistribusi])
                                                                ->orWhere(['status_akhir'=>$_statusPindah])
                                                                ->andWhere(['deleted'=>0])
                                                                ->one();    
                        if($_cekTransaksi == null){
                            //insert 1 pemindahan barang dengan status transaksi DISTRIBUSI
                            $modelOne = new PemindahanBarang();
                            $modelOne->pengeluaran_barang_id = $_barangKeluarId;
                            $modelOne->lokasi_akhir_id = $modelBarangKeluarOne->lokasi_id;
                            $modelOne->kode_inventori = $modelBarangKeluarOne->kode_inventori;
                            $modelOne->tanggal_pindah = $modelBarangKeluarOne->tgl_keluar;
                            $modelOne->status_transaksi = $_statusDistribusi;
                            $modelOne->oelh = $_oleh;
                            $modelOne->save();
                        }
                        //insert into pemindahan barang
                        $model = new PemindahanBarang();
                        $model->pengeluaran_barang_id = $_barangKeluarId;
                        $model->lokasi_akhir_id = $_lokasiTujuan;
                        $model->tanggal_pindah = $_tanggalPindah;
                        $model->kode_inventori = $_kodeAkhir;
                        $model->lokasi_awal_id = $modelBarangKeluarOne->lokasi_id;
                        $model->kode_inventori_awal = $modelBarangKeluarOne->kode_inventori;
                        $model->status_transaksi = $_statusPindah;
                        $model->oleh = $_oleh;

                        //update pengeluaran barang
                        if($model->save()){
                            $modelBarangKeluarOne->kode_inventori = $_kodeAkhir;
                            $modelBarangKeluarOne->tgl_keluar = $_tanggalPindah;
                            $modelBarangKeluarOne->lokasi_id = $_lokasiTujuan;
                            $modelBarangKeluarOne->status_akhir = $_statusPindah;//PINDAH
                            $modelBarangKeluarOne->save();
                        }
                        Yii::$app->messenger->addSuccessFlash("Barang dengan kode inventori: <strong>".$modelBarangKeluarOne->kode_inventori."</strong> berhasil dipindahkan ke <strong>".$modelBarangKeluarOne->lokasi->nama_lokasi."</strong>.");
                    }
                }
                return $this->redirect('pindah-barang-browse');
            }
            else{
                    return $this->render('PindahBarangForm', [
                    'modelForm'=>$modelForm,
                    'lokasiAwalList'=>$lokasiAwalList,
                    'lokasiTujuanList'=>$lokasiTujuanList
                    ]);
            }
        } 
        //cek pilihan pindah barang form
        elseif($modelForm->load(Yii::$app->request->post())){
            $_tanggalPindah = Yii::$app->request->post()['PindahBarangForm']['tgl_pindah'];
            $_lokasiAwal = Yii::$app->request->post()['PindahBarangForm']['lokasi_awal'];
            $_lokasiAkhir = Yii::$app->request->post()['PindahBarangForm']['lokasi_akhir'];

            //lokasi tujuan
            $_lokasi = Lokasi::find();
            foreach ($_lokasiAkhir as $value) {
                $_lokasi->orWhere(['lokasi_id'=>$value]);
            }
            $_lokasi->andWhere(['deleted'=>0]);
            $_lokasi = $_lokasi->all();

            //dataprovider for form
            $query = PengeluaranBarang::find();
            foreach ($_lokasiAwal as $value) {
                $query->orWhere(['lokasi_id'=>$value]);
            }
            $query->andWhere(['deleted'=>0]);
            $query->andWhere(['status_akhir'=>$_statusDistribusi]);
            $query->orWhere(['status_akhir'=>$_statusPindah]);
            $dataProvider = new ActiveDataProvider([
                    'query'=>$query,
                    'pagination'=>['pagesize'=>30],
                ]);  
            return $this->render ('PindahBarang',[
                        'dataProvider'=>$dataProvider,
                        '_lokasi'=>$_lokasi,
                        '_tanggalPindah'=>$_tanggalPindah,
                    ]);
        }
        else{
                if($modelPengeluaranBarang==null){
                    Yii::$app->messenger->addWarningFlash("Record pendistribusian barang tidak ditemukan, silahkan distribusikan barang terlebih dahulu");
                    return $this->redirect(Url::to(['pengeluaran-barang/barang-keluar-browse']));
                }
                else{
                    return $this->render('PindahBarangForm', [
                            'modelForm'=>$modelForm,
                            'lokasiAwalList'=>$lokasiAwalList,
                            'lokasiTujuanList'=>$lokasiTujuanList
                ]);
            }
        }
    }

    //detail histori pemindahan 1 distribusi barang
    //parameter : pengeluaran_barang_id
    public function actionDetailHistori($pengeluaran_barang_id)
    {
        $query = PemindahanBarang::find()
                            ->where(['deleted'=>0])
                            ->andWhere(['pengeluaran_barang_id'=>$pengeluaran_barang_id])
                            ->orderBy(['tanggal_pindah'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>['pagesize'=>30],
        ]);
        return $this->render('DetailHistori',[
                'dataProvider'=>$dataProvider,
        ]);
    }
    /**
     * Updates an existing PemindahanBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pemindahan_barang_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PemindahanBarang model.
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
     * Finds the PemindahanBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PemindahanBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PemindahanBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

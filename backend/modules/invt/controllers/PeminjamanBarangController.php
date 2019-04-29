<?php

namespace backend\modules\invt\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

use backend\modules\invt\models\PeminjamanBarang;
use backend\modules\invt\models\DetailPeminjamanBarang;
use backend\modules\invt\models\search\PeminjamanBarangSearch;
use backend\modules\invt\models\Barang;
use backend\modules\invt\models\search\BarangSearch;
use backend\modules\invt\models\JenisBarang;
use backend\modules\invt\models\Kategori;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\UnitCharged;
use backend\modules\invt\models\KembaliForm;
/**
 * PeminjamanBarangController implements the CRUD actions for PeminjamanBarang model.
 */
class PeminjamanBarangController extends Controller
{
    public $menuGroup = 'm-peminjamanbarang';
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
     * Lists all PeminjamanBarang models.
     * @return mixed
     */
    public function actionPinjamBarangBrowse(){
      $user_id = Yii::$app->user->id;
      $searchModel = new PeminjamanBarangSearch();
      $query = PeminjamanBarang::find()
                                  ->where(['deleted'=>0])
                                  ->andWhere(['oleh'=>$user_id]);
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$query,20);
      //status
      $status = [
                    0=>['status_approval'=>0,'status'=>'Belum'],
                    1=>['status_approval'=>1,'status'=>'Approve'],
                    2=>['status_approval'=>2,'status'=>'Tolak']
                ];
      $statusKembali = [
                  0=>['status_kembali'=>0, 'status'=>'Belum Kembali'],
                  1=>['status_kembali'=>1, 'status'=>'Kembali'],
                ];
      return $this->render('PinjamBarangBrowse',[
          'searchModel'=>$searchModel,
          'dataProvider'=>$dataProvider,
          'status'=>$status,
          'statusKembali'=>$statusKembali,
      ]);
    }
    public function actionPinjamBarangBrowseByadmin()
    {
        //get Unit
        $user_id = Yii::$app->user->id;
        $_getunits = UnitCharged::getUnitByUser($user_id);
        if($_getunits ==null || empty($_getunits)){
            Yii::$app->messenger->addWarningFlash("Anda bukan admin salah satu dari unit");
            return $this->redirect(url::to('barang/barang-browse'));
        }
        //status
        $status = [
                    0=>['status_approval'=>0,'status'=>'Belum'],
                    1=>['status_approval'=>1,'status'=>'Approve'],
                    2=>['status_approval'=>2,'status'=>'Tolak']
                  ];
        $statusKembali = [
                    0=>['status_kembali'=>0, 'status'=>'Belum Kembali'],
                    1=>['status_kembali'=>1, 'status'=>'Kembali'],
                  ];
        $searchModel = new PeminjamanBarangSearch();
        $dataProviders[][] = null;
        $i=0;
        foreach ($_getunits as $key => $_unit) {
          $query = PeminjamanBarang::find()
                                    ->where(['deleted'=>0])
                                    ->andWhere(['unit_id'=>$_unit->unit_id]);
          $dataProviders[$i]['dataProvider'] = $searchModel->search(Yii::$app->request->queryParams,$query,20);
          $dataProviders[$i]['unit'] = $_unit->unit->nama;
          $i++;
        }
        return $this->render('PinjamBarangBrowseByadmin', [
            'searchModel' => $searchModel,
            'dataProviders' => $dataProviders,
            'status'=>$status,
            'statusKembali'=>$statusKembali,
        ]);
    }

    /**
     * Displays a single PeminjamanBarang model.
     * @param integer $id
     * @return mixed
     */
    public function actionPinjamBarangView($id)
    {
        $model = $this->findModel($id);
        $isAdminByUnit = $this->isAdminByUnit($model->unit_id);
        $searchModel = new PeminjamanBarangSearch();
        $query = DetailPeminjamanBarang::find()
                                          ->where(['peminjaman_barang_id'=>$id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $query, 12);
        $_statusPinjam = "";
        if($model->status_approval==0){
          $_statusPinjam = 'Belum Approve';
        }elseif($model->status_approval==1){   
          $_statusPinjam = "Approve";
        }elseif($model->status_approval==2){
          $_statusPinjam =  "Tolak";
        } 

        $_statusKembali = "";
        if($model->status_kembali==0){
          $_statusKembali = 'Belum Kembali';
        }elseif($model->status_kembali==1){   
          $_statusKembali = "Kembali";
        }
        return $this->render('PinjamBarangView', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider,
            '_statusPinjam' =>$_statusPinjam,
            '_statusKembali'=>$_statusKembali,
            'isAdminByUnit'=>$isAdminByUnit,
        ]);
    }

    /**
     * Creates a new PeminjamanBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPinjamBarang()
    {
        $model = new PeminjamanBarang();
        $unit = Unit::find()
                        ->where(['deleted'=>0])
                        ->all();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            //get user
            $user_id = Yii::$app->user->id;
            $model->oleh = $user_id;
            $model->save();
            return $this->redirect(['detail-pinjam-barang','id'=>$model->peminjaman_barang_id]);
        }else{
            return $this->render('PinjamBarang',[
                'model'=>$model,
                'unit'=>$unit,
            ]);
        }
    }

    /**
     * Updates an existing PeminjamanBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEditPinjamBarang($id){
      $model = $this->findModel($id);
      //cek apakah milik user yang bersangkutan
      $user_id = Yii::$app->user->id;
      if($model->oleh != $user_id){
        Yii::$app->messenger->addErrorFlash("Pengajuan peminjaman barang ini bukan atas nama anda!");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      //cek is accept
      if($this->isAccept($id)==true){
         Yii::$app->messenger->addWarningFlash("Peminjaman barang anda telah disetujui admin unit. Anda tidak mengubah peminjaman barang.");
       return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      //cek is reject
      if($this->isReject($id)==true){
        Yii::$app->messenger->addWarningFlash("Peminjaman barang anda ditolak oleh admin unit. Anda tidak mengubah peminjaman barang.");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }

      $unit = Unit::find()
                    ->where(['deleted'=>0])
                    ->all();
      if($model->load(Yii::$app->request->post()) && $model->validate()){
        if($model->save()){
          //hapus detail peminjaman barang : peminjaman_barang_id => $id
          $modelDetail = DetailPeminjamanBarang::find()
                                            ->where(['peminjaman_barang_id'=>$id])
                                            ->all();
          foreach ($modelDetail as $key => $barang) {
            $barang->delete();
          }
        }
        return $this->redirect(['detail-pinjam-barang','id'=>$id]);
      }else{
        return $this->render('EditPinjamBarang',[
          'model'=>$model,
          'unit'=>$unit,
        ]);
      }
    }

    public function actionDetailPinjamBarang($id)
    {
        //cek id peminjaman
        $modelPengajuan = $this->findModel($id);
        if($modelPengajuan == null || $id == null)
        {
            Yii::$app->messenger->addWarningFlash('Tidak ditemukan ID pengajuan peminjaman barang');
            $this->redirect('pinjam-barang-browse');
        }
        else
        {
          //get user
          $user_id = Yii::$app->user->id;
          //cek apakah milik user
          if($modelPengajuan->oleh != $user_id){
            Yii::$app->messenger->addErrorFlash("Pengajuan peminjaman barang ini bukan atas nama anda!");
            return $this->redirect('pinjam-barang-browse');
          }
          //cek is accept
          if($this->isAccept($id)==true)
          {
            Yii::$app->messenger->addWarningFlash("Peminjaman barang anda telah disetujui admin unit. Anda tidak dapat lagi melakukan peminjaman barang untuk satu pengajuan yang sama.");
            return $this->redirect('pinjam-barang-browse');
          }
          //cek is reject
          if($this->isReject($id)==true){
            Yii::$app->messenger->addWarningFlash("Peminjaman barang anda ditolak oleh admin unit. Anda tidak dapat lagi melakukan peminjaman barang untuk satu pengajuan yang sama.");
            return $this->redirect('pinjam-barang-browse');
          }

          $searchModel = new BarangSearch();
          $modelBarang = Barang::find()
                                  ->where (['>','jumlah_gudang',0])
                                  ->andWhere(['unit_id'=>$modelPengajuan->unit_id])
                                  ->andWhere(['deleted'=>0]);
          $_jenis = JenisBarang::find()
                              ->where('deleted=:de',[':de'=>0])
                              ->all();
          $_kategori = Kategori::find()
                              ->where('deleted=:de',[':de'=>0])
                              ->all();
          $_unit = Unit::find()
                          ->where('deleted=:de',[':de'=>0])
                          ->all();

          $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelBarang, 10);
          $selection = (array)Yii::$app->request->post('selection');
          $jumlahPinjam = (array)Yii::$app->request->post('jumlah_pinjam');
          if (array_filter($selection) && array_filter($jumlahPinjam)) {
              foreach ($selection as $key => $value) {
                //validasi selection[value] dan _jumlahPinjam[value]
                $_jumlahPinjam = (int)$jumlahPinjam[$value];
                if((isset($value)&&$value!="") && ($_jumlahPinjam!=0 && $_jumlahPinjam!="")){
                  $modelPinjam = new DetailPeminjamanBarang();
                  $modelPinjam->peminjaman_barang_id = $id;
                  $modelPinjam->barang_id = $value;
                  $modelPinjam->jumlah = $_jumlahPinjam;

                  if($modelPinjam->save())
                  {
                      Yii::$app->messenger->addSuccessFlash("Barang <strong>".$modelPinjam->barang->nama_barang. "</strong> berhasil ditambahkan dalam daftar pinjaman anda");
                  }
                }
              }
              //send message to admin unit
              $_adminUnit = $this->getAdminByUnit($modelPengajuan->unit_id);
              foreach ($_adminUnit as $key => $admin) {
                Yii::$app->messenger->sendNotificationToUser($admin->user_id, $modelPengajuan->getDetailNama($modelPengajuan->oleh)." melakukan peminjaman silahkan lakukan pengecekan barang.");
              }
              Yii::$app->messenger->addSuccessFlash("Silahkan menunggu konfirmasi dari unit inventori <strong>". $modelPengajuan->unit->nama."</strong>");
              $this->redirect(['pinjam-barang-view','id'=>$id]);
          } else {
              return $this->render('DetailPinjamBarang', [
                  'dataProvider'=>$dataProvider,
                  'searchModel'=>$searchModel,
                  '_jenis'=>$_jenis,
                  '_kategori'=>$_kategori,
                  '_unit'=>$_unit,
                  'modelPengajuan'=>$modelPengajuan,
              ]);
          }
        } 
    }
    /**
     * Deletes an existing PeminjamanBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPinjamBarangDel($id)
    {
        $model = $this->findModel($id);
        //cek apakah milik user yang bersangkutan
        $user_id = Yii::$app->user->id;
        if($model->oleh != $user_id){
          Yii::$app->messenger->addErrorFlash("Pengajuan peminjaman barang ini bukan atas nama anda!");
          return $this->redirect(['pinjam-barang-view','id'=>$id]);
        }
        //cek is accept
        if($this->isAccept($id)==true){
           Yii::$app->messenger->addWarningFlash("Peminjaman barang anda telah disetujui admin unit. Anda tidak menghapus peminjaman barang.");
         return $this->redirect(['pinjam-barang-view','id'=>$id]);
        }
        //cek is reject
        if($this->isReject($id)==true){
          Yii::$app->messenger->addWarningFlash("Peminjaman barang anda ditolak oleh admin unit. Anda tidak menghapus peminjaman barang.");
          return $this->redirect(['pinjam-barang-view','id'=>$id]);
        }
        //delete
        $modelDetail = DetailPeminjamanBarang::find()
                                              ->where(['peminjaman_barang_id'=>$id])
                                              ->all();
        if($model->softDelete()){
          foreach ($modelDetail as $key => $barang) {
            $barang->delete();
          }
        }
        Yii::$app->messenger->addSuccessFlash("Peminjaman barang anda berhasil di hapus");
        return $this->redirect(['pinjam-barang-browse']);
    }

    private function isAccept($id)
    {
      $model = $this->findModel($id);
      if($model->status_approval == 1 && $model->approved_by !=null){
        return true;
      }
      return false;
    }

    private function isReject($id){
      $model = $this->findModel($id);
      if($model->status_approval == 2){
        return true;
      }
      return false;
    }

    private function isBack($id){
      $model = $this->findModel($id);
      if($model->status_kembali == 1){
        return true;
      }
      return false;
    }

    private function getAdminByUnit($unit_id){
      $modelUserCharged = UnitCharged::find()
                                    ->where(['unit_id'=>$unit_id])
                                    ->all();
      return $modelUserCharged;
    }

    private function isAdminByUnit($unit_id){
      $user_id = Yii::$app->user->id;
      $modelUserCharged = UnitCharged::find()
                                  ->where(['user_id'=>$user_id, 'unit_id'=>$unit_id])
                                  ->one();
      if($modelUserCharged != null){
        return true;
      }
      return false;
    }
    //approve
    public function actionApprove($id){
      //cek isAccept
      if($this->isAccept($id)==true)
      {
        Yii::$app->messenger->addWarningFlash("Peminjaman barang telah disetujui. Anda tidak dapat melakukan approval lagi.");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      //getuser
      $user_approve = Yii::$app->user->id;
      $modelPeminjaman = $this->findModel($id);
      $modelDetailBarang = DetailPeminjamanBarang::find()
                                                ->where(['peminjaman_barang_id'=>$id])
                                                ->all();
      //update modelPeminjaman
      $modelPeminjaman->approved_by = $user_approve;
      $modelPeminjaman->status_approval = 1;
      //update barang
      if($modelPeminjaman->save()){
        foreach ($modelDetailBarang as $key => $_barang) {
          $modelBarang = Barang::find()
                                    ->where(['deleted'=>0])
                                    ->andWhere(['barang_id'=>$_barang->barang_id])
                                    ->one();
          $modelBarang->jumlah_pinjam = $modelBarang->jumlah_pinjam + $_barang->jumlah;
          $modelBarang->jumlah_gudang = $modelBarang->jumlah_gudang - $_barang->jumlah;
          $modelBarang->save();
        }
      }
      //send notification
      Yii::$app->messenger->sendNotificationToUser($modelPeminjaman->oleh, "Peminjaman anda telah disetujui oleh admin unit.");
      Yii::$app->messenger->addSuccessFlash("Peminjaman barang atas nama : <strong>".$modelPeminjaman->getDetailNama($modelPeminjaman->oleh). "</strong> berhasil di-approve");
      return $this->redirect(['pinjam-barang-view','id'=>$id]);
    }

    //reject
    public function actionReject($id){
      $modelPeminjaman = $this->findModel($id);
      //cek apakah sudah kembali
      if($this->isBack($id)==true){
        Yii::$app->messenger->addWarningFlash("Status barang sudah kembali");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      //cek apakah sudah diaccept
      if($this->isAccept($id)==true){
        //get detal barang yang dipinjam
        $modelDetailBarang = DetailPeminjamanBarang::find()
                                                  ->where(['peminjaman_barang_id'=>$id])
                                                  ->all();
        foreach ($modelDetailBarang as $key => $_barang) {
          $modelBarang = Barang::find()
                                    ->where(['deleted'=>0])
                                    ->andWhere(['barang_id'=>$_barang->barang_id])
                                    ->one();
          $modelBarang->jumlah_pinjam = $modelBarang->jumlah_pinjam - $_barang->jumlah;
          $modelBarang->jumlah_gudang = $modelBarang->jumlah_gudang + $_barang->jumlah;
          $modelBarang->save();
        }
        $modelPeminjaman->approved_by = NULL;
      }
      $modelPeminjaman->status_approval = 2;
      if($modelPeminjaman->save()){
        //send notification
        Yii::$app->messenger->sendNotificationToUser($modelPeminjaman->oleh, "Peminjaman anda telah ditolak oleh admin unit.");
        Yii::$app->messenger->addSuccessFlash("Peminjaman barang atas nama : <strong>".$modelPeminjaman->getDetailNama($modelPeminjaman->oleh). "</strong> ditolak");
        return $this->redirect('pinjam-barang-browse');
      }
    }

    //kembali
    public function actionKembali($id){
      //cek status peminjaman
      if($this->isReject($id)==true){
        Yii::$app->messenger->addWarningFlash("Peminjaman barang telah ditolak.");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      if($this->isAccept($id)==false){
        Yii::$app->messenger->addWarningFlash("Peminjaman barang belum di-approve. Silahkan approve terlebih dahulu.");
        return $this->redirect(['pinjam-barang-view','id'=>$id]);
      }
      $modelKembali = new KembaliForm();
      $model = $this->findModel($id);
      $_statusPinjam = "";
      if($model->status_approval==0){
          $_statusPinjam = 'Belum Approve';
      }elseif($model->status_approval==1){   
          $_statusPinjam = "Approve";
      }elseif($model->status_approval==2){
          $_statusPinjam =  "Tolak";
      }

      $_statusBarang = ['bagus'=>'Bagus','rusak'=>'Rusak'];
      //detail barang
      $query = DetailPeminjamanBarang::find()
                      ->where('peminjaman_barang_id=:id',[':id'=>$id]);
      $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>['pageSize'=>20],
      ]);
      $listBarang = $query->all();
      if($modelKembali->load(Yii::$app->request->post())){
        $post = Yii::$app->request->post();
        //update status pengembalian
        $model->tgl_realisasi_kembali = $post['KembaliForm']['tgl_realisasi_kembali'];
        $model->status_kembali = 1;

        $_status = $post['KembaliForm']['cek_status_barang'];
        $modelDetail = $model->detailBarang;
        if($_status == 'bagus'){
          $model->save();
          //update tabel barang
          foreach ($modelDetail as $key => $barang) {
            $modelBarang = Barang::find()
                                  ->where(['deleted'=>0])
                                  ->andWhere(['barang_id'=>$barang->barang_id])
                                  ->one();
            $modelBarang->jumlah_gudang = $modelBarang->jumlah_gudang + $barang->jumlah;
            $modelBarang->jumlah_pinjam = $modelBarang->jumlah_pinjam - $barang->jumlah;
            $modelBarang->save();
          }
          //send notification
          Yii::$app->messenger->sendNotificationToUser($model->oleh, "Peminjaman barang anda telah kembali.");
          Yii::$app->messenger->addSuccessFlash("Peminjaman atas nama : ".$model->getDetailNama($model->oleh)." berhasil dikembalikan");
          return $this->redirect(['pinjam-barang-view','id'=>$id]);
        }elseif($_status == 'rusak'){
          $_cekJumlahRusak = (array)Yii::$app->request->post('cek_jumlah_rusak');
          if(array_filter($_cekJumlahRusak)){
            $model->save();
            foreach ($modelDetail as $key => $barang) {
              //get 1 barang
              $modelBarang = Barang::find()
                                    ->where(['barang_id'=>$barang->barang_id])
                                    ->andWhere(['deleted'=>0])
                                    ->one();
              //cek yang rusak
              $_jumlahRusak = $_cekJumlahRusak[$barang->barang_id];
              if($_jumlahRusak!=null && $_jumlahRusak!=0){
                //update detail peminjaman barang : jumlah rusak
                $barang->jumlah_rusak = $_jumlahRusak;
                $barang->save();
                //update barang
                $_sisaBagus = 0;
                if($barang->jumlah > $_jumlahRusak){
                  $_sisaBagus = $barang->jumlah - $_jumlahRusak;
                }else{
                  $_sisaBagus = 0;
                }
                $modelBarang->jumlah_gudang = $modelBarang->jumlah_gudang + $_sisaBagus;
                $modelBarang->jumlah_rusak = $modelBarang->jumlah_rusak + $_jumlahRusak;
                $modelBarang->jumlah_pinjam = $modelBarang->jumlah_pinjam - $barang->jumlah;
                $modelBarang->save();
              }else{
                $modelBarang->jumlah_gudang = $modelBarang->jumlah_gudang + $barang->jumlah;
                $modelBarang->jumlah_pinjam = $modelBarang->jumlah_pinjam - $barang->jumlah;
                $modelBarang->save();
              }
            }
            //send notification
            Yii::$app->messenger->sendNotificationToUser($model->oleh, "Peminjaman barang anda telah kembali.");
            Yii::$app->messenger->addSuccessFlash("Peminjaman atas nama : ".$model->getDetailNama($model->oleh)." berhasil dikembalikan");
            return $this->redirect(['pinjam-barang-view','id'=>$id]);
          }else{
            return $this->render('_formKembali',[
              'modelKembali'=>$modelKembali,
              '_statusPinjam'=>$_statusPinjam,
              'dataProvider'=>$dataProvider,
              '_statusBarang'=>$_statusBarang,
              'listBarang'=>$listBarang,
              'model'=>$model,
            ]);
          }
        }
      }else{
        return $this->render('_formKembali',[
          'modelKembali'=>$modelKembali,
          '_statusPinjam'=>$_statusPinjam,
          'dataProvider'=>$dataProvider,
          '_statusBarang'=>$_statusBarang,
          'listBarang'=>$listBarang,
          'model'=>$model,
        ]);
      }
    }
    /**
     * Finds the PeminjamanBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PeminjamanBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PeminjamanBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

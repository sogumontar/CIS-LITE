<?php

namespace backend\modules\invt\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\invt\models\Barang;
use backend\modules\invt\models\search\BarangSearch;
use backend\modules\invt\models\JenisBarang;
use backend\modules\invt\models\Kategori;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\Satuan;
use backend\modules\invt\models\Vendor;
use backend\modules\invt\models\Brand;
use backend\modules\invt\models\UnitCharged;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\UserCharged;
use backend\modules\invt\models\PengeluaranBarang;
use backend\modules\invt\models\SummaryJumlah;
//file
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;



/**
 * BarangController implements the CRUD actions for Barang model.
 */
class BarangController extends Controller
{
    public $menuGroup = 'm-inventori';
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
     * Lists all Barang models.
     * @return mixed
     */
    public function actionBarangBrowse()
    {
        $_jenis = JenisBarang::find()
                            ->where('deleted=:de',[':de'=>0])
                            ->all();
        $_kategori = Kategori::find()
                            ->where('deleted=:de',[':de'=>0])
                            ->all();
        $_unit = Unit::find()
                        ->where('deleted=:de',[':de'=>0])
                        ->all();
        $searchModel = new BarangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$query=null,20);
        return $this->render('BarangBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            '_jenis'=>$_jenis,
            '_kategori'=>$_kategori,
            '_unit'=>$_unit,
        ]);
    }

    public function actionBarangBrowseByadmin()
    {
        $_jenis = JenisBarang::find()
                            ->where('deleted=:de',[':de'=>0])
                            ->all();
        $_kategori = Kategori::find()
                            ->where('deleted=:de',[':de'=>0])
                            ->all();
        $_unit = Unit::find()
                        ->where('deleted=:de',[':de'=>0])
                        ->all();
        $user_id = Yii::$app->user->id;
        $_getUnits = UnitCharged::getUnitbyUser($user_id);
        if($_getUnits==null || empty($_getUnits))
        {
            Yii::$app->messenger->addWarningFlash("Anda bukan admin salah satu unit!");
            return $this->redirect('barang-browse');
        }
        $searchModel = new BarangSearch();
        $dataProviders[][] = null;
        $i=0;
        foreach ($_getUnits as $key=>$value) {
            $query= Barang::find()
                            ->where(['unit_id'=>$value->unit_id]);
            $dataProviders[$i]['dataProvider'] = $searchModel->search(Yii::$app->request->queryParams,$query,20);
            $dataProviders[$i]['unit_id'] = $value->unit_id;
            $dataProviders[$i]['unit'] = $value->unit->nama;
            $i++;
        }
       return $this->render('BarangBrowseByadmin',[
            'searchModel'=>$searchModel,
            'dataProviders'=>$dataProviders,
            '_jenis'=>$_jenis,
            '_kategori'=>$_kategori,
            '_unit'=>$_unit,
        ]);
    }

    /**
     * Displays a single Barang model.
     * @param integer $id
     * @return mixed
     */
    public function actionBarangView($barang_id)
    {
        $model = $this->findModel($barang_id);
        //get Is admin
        $isAdminByUnit = $this->isAdminByUnit($model->unit_id);
        $_barangKeluar =  $model->getPengeluaranBarangbyId($barang_id);
        $_jumlahBarangKeluar = $model->countBarangKeluar($barang_id);

        $lokasi_distribusi = $model->getLokasiDistribusi($barang_id);
        $dataProvider = new ActiveDataProvider([
                'query'=>$_barangKeluar,
                'pagination'=>['pageSize'=>5],
            ]);
        return $this->render('BarangView', [
            'model' => $model, 
            'isAdminByUnit'=>$isAdminByUnit,
            '_barangKeluar'=>$_barangKeluar,
            'dataProvider'=>$dataProvider,
            '_jumlahBarangKeluar'=>$_jumlahBarangKeluar,
            'lokasi_distribusi'=>$lokasi_distribusi,
        ]);
    }

    /**
     * Creates a new Barang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBarangAdd($unit_id)
    {
        //cek is admin
        if($this->isAdminByUnit($unit_id)==false)
        {
            Yii::$app->messenger->addWarningFlash("Anda bukan admin unit: ".Unit::findOne($unit_id)->nama);
            return $this->redirect('barang-browse');
        }
        $model_jenis_barang = JenisBarang::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_kategori_barang = Kategori::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_satuan = Satuan::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_brand = Brand::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_vendor = Vendor::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model = new Barang();
        if ($model->load(Yii::$app->request->post())) {
            //gambr barang
            $filename = UploadedFile::getInstance($model, 'nama_file');
            if($filename)
            {
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status->status =='success')
                {
                    $model->nama_file = $filename->name;
                    $model->kode_file = $status->fileinfo[0]->id;
                }
            }
            $model->unit_id = $unit_id;
            $model->save();
            return $this->redirect(['barang-view', 'barang_id' => $model->barang_id]);
        } else {
            return $this->render('BarangAdd', [
                'model' => $model,
                'model_satuan'=>$model_satuan,
                'model_kategori_barang'=>$model_kategori_barang,
                'model_jenis_barang'=>$model_jenis_barang,
                'model_brand'=>$model_brand,
                'model_vendor'=>$model_vendor,
            ]);
        }
    }

    /**
     * Updates an existing Barang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBarangEdit($barang_id,$unit_id)
    {
        //cek is admin
        if($this->isAdminByUnit($unit_id)==false)
        {
            Yii::$app->messenger->addWarningFlash("Anda bukan admin unit: ".Unit::findOne($unit_id)->nama);
            return $this->redirect('barang-browse');
        }
        $model = $this->findModel($barang_id);
        $model_jenis_barang = JenisBarang::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_kategori_barang = Kategori::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_satuan = Satuan::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_brand = Brand::find()
                                ->where(['deleted'=>0])
                                ->all();
        $model_vendor = Vendor::find()
                                ->where(['deleted'=>0])
                                ->all();
        if ($model->load(Yii::$app->request->post())) {
            //gambr barang
            $filename = UploadedFile::getInstance($model, 'nama_file');
            if($filename)
            {
                $status = \Yii::$app->fileManager->saveUploadedFiles();
                if($status->status =='success')
                {
                    $model->nama_file = $filename->name;
                    $model->kode_file = $status->fileinfo[0]->id;
                }
            }
            $model->save();
            return $this->redirect(['barang-view', 'barang_id' => $barang_id]);
        } else {
            return $this->render('BarangEdit', [
                'model' => $model,
                'model_satuan'=>$model_satuan,
                'model_kategori_barang'=>$model_kategori_barang,
                'model_jenis_barang'=>$model_jenis_barang,
                'model_vendor'=>$model_vendor,
                'model_brand'=>$model_brand,
            ]);
        }
    }

    /**
     * Deletes an existing Barang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBarangDel($barang_id,$unit_id)
    {
        //cek is admin
        if($this->isAdminByUnit($unit_id)==false)
        {
            Yii::$app->messenger->addWarningFlash("Anda bukan admin unit: ".Unit::findOne($unit_id)->nama);
            return $this->redirect('barang-browse');
        }
        $model = $this->findModel($barang_id);
        $model->softDelete();
        return $this->redirect(['barang-browse-byadmin']);
    }


    private function isAdminByUnit($unit_id)
    {
        $user_id = Yii::$app->user->id;
        $modelUserCharged = UnitCharged::find()
                            ->where([UnitCharged::tableName().".user_id"=>$user_id, UnitCharged::tableName().".unit_id"=>$unit_id])
                            ->one();
        if($modelUserCharged != null){
            return true;
        }
        return false;
    }
    /**
     * Finds the Barang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Barang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Barang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
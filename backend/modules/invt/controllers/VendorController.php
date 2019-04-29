<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\Vendor;
use backend\modules\invt\models\search\VendorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\invt\models\ArsipVendor;
use backend\modules\invt\models\FileVendor;

//file
use yii\web\UploadedFile;
/*for Ajax*/
use common\helpers\LinkHelper;
/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class VendorController extends Controller
{
    public $menuGroup = 'm-vendor';
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
     * Lists all Vendor models.
     * @return mixed
     */
    public function actionVendorBrowse()
    {
        $searchModel = new VendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('VendorBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vendor model.
     * @param integer $id
     * @return mixed
     */
    public function actionVendorView($id)
    {   //arsip
        $arsipModel = ArsipVendor::find()
                                    ->where(['deleted'=>0])
                                    ->andWhere(['vendor_id'=>$id])
                                    ->all();
        $arsip = ArsipVendor::find()->limit(1)->one();
        $arsipFile = FileVendor::find()->limit(2)->all();
        return $this->render('VendorView', [
            'model' => $this->findModel($id),
            'arsipModel'=>$arsipModel,
            'arsip'=>$arsip,
            'arsipFile'=>$arsipFile,
        ]);
    }

    /**
     * Creates a new Vendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionVendorAdd()
    {
        $model = new Vendor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['vendor-view', 'id' => $model->vendor_id]);
        } else {
            return $this->render('VendorAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Vendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionVendorEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['vendor-view', 'id' => $model->vendor_id]);
        } else {
            return $this->render('VendorEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Vendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionVendorDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['vendor-browse']);
    }

    //arsip vendor & file
    public function actionArsipAdd($id){
        $modelVendor = $this->findModel($id);
        $model = new ArsipVendor();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->vendor_id = $id;
            $model->save();

            //save file
            $_files = $_FILES['files']['name'];
            if($_files[0]!=""){
                $objFiles = Yii::$app->fileManager->saveUploadedFiles();
                if($objFiles->status == 'success'){
                    foreach ($objFiles->fileinfo as $key => $value) {
                       $_arsipFile = new FileVendor();
                       $_arsipFile->arsip_vendor_id = $model->arsip_vendor_id;
                       $_arsipFile->nama_file = $value->name;
                       $_arsipFile->kode_file = $value->id;
                       $_arsipFile->save();
                    }
                }
            }
            return $this->redirect(['vendor-view','id'=>$id]);
        }else{
            return $this->render('ArsipAdd',[
                'model'=>$model,
                'modelVendor'=>$modelVendor,
            ]);
        }
    }

    public function actionArsipView($id)
    {
        $arsip = ArsipVendor::find()
                        ->where(['arsip_vendor_id'=>$id])
                        ->andWhere(['deleted'=>0])
                        ->one();
        $arsipFile = FileVendor::find()
                                ->where(['arsip_vendor_id'=>$id])
                                ->andWhere(['deleted'=>0])
                                ->all();
        if(LinkHelper::isPjaxrequest())
        {
            return $this->renderPartial('ArsipView',[
                'arsip'=>$arsip,
                'arsipFile'=>$arsipFile,
            ]);
        }
        else
            return $this->redirect(['vendor-view','id'=>$arsip->vendor_id]);
    }

    public function actionArsipEdit($arsip_id, $vendor_id)
    {
            $model = ArsipVendor::find()
                            ->where(['arsip_vendor_id'=>$arsip_id])
                            ->andWhere(['deleted'=>0])
                            ->one();
            $modelVendor = $this->findModel($vendor_id);
            $arsipList = FileVendor::find()
                                    ->where(['arsip_vendor_id'=>$arsip_id])
                                    ->andWhere(['deleted'=>0])
                                    ->all();
            if($model->load(Yii::$app->request->post()))
            {
                $model->save();
               //save file
                $_files=$_FILES['files']['name'];
                if($_files[0]!=="")
                {
                    $objFiles = \Yii::$app->fileManager->saveUploadedFiles();
                        if($objFiles->status == 'success'){
                            foreach ($objFiles->fileinfo as $key => $value) {
                                $_arsipFile = new FileVendor();
                                $_arsipFile->arsip_vendor_id = $model->arsip_vendor_id;
                                $_arsipFile->nama_file = $value->name;
                                $_arsipFile->kode_file = $value->id;
                                $_arsipFile->save();
                            }
                        }
                }
                return $this->redirect(['vendor-view','id'=>$vendor_id]);
            }
            else
            {
                return $this->render('ArsipEdit',[
                        'model'=>$model,
                        'modelVendor'=>$modelVendor,
                        'arsipList'=>$arsipList,
                    ]);
            }
    }

    public function actionArsipDel($arsip_id, $vendor_id)
    {
            $_arsip = ArsipVendor::find()
                            ->where(['arsip_vendor_id'=>$arsip_id])
                            ->andWhere(['deleted'=>0])
                            ->one();
            $_arsipFile = FileVendor::find()
                                    ->where(['arsip_vendor_id'=>$arsip_id])
                                    ->all();
            if($_arsip->softDelete())
            {
                //hapus file dari puro dan dari database
                foreach ($_arsipFile as $key => $value) {
                   \Yii::$app->fileManager->delete($value->kode_file);
                   $value->softDelete();
                }
                 \Yii::$app->messenger->addInfoFlash("Arsip <b>".$_arsip->judul_arsip."</b> berhasil di hapus");
                return $this->redirect(['vendor-view', 'id'=>$vendor_id]);
            }
    }

    public function actionFileVendorDel($file_vendor_id, $arsip_vendor_id, $vendor_id)
    {
        $_arsipFile  = FileVendor::findOne(['file_vendor_id'=>$file_vendor_id]);
        //hapus dari Puro dan dari database;
        \Yii::$app->fileManager->delete($_arsipFile->kode_file);
        $_arsipFile->softDelete();

        return $this->redirect(['arsip-edit', 'arsip_id'=>$arsip_vendor_id, 'vendor_id'=>$vendor_id]);

    }
    /**
     * Finds the Vendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

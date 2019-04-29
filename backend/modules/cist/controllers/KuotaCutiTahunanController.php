<?php

namespace backend\modules\cist\controllers;

use backend\modules\cist\models\StatusAktifPegawai;
use backend\modules\cist\models\StatusIkatanKerjaPegawai;
use backend\modules\cist\models\WaktuGenerateKuotaCuti;
use Yii;
use backend\modules\cist\models\KuotaCutiTahunan;
use backend\modules\cist\models\search\KuotaCutiTahunanSearch;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\GolonganKuotaCuti;
use backend\modules\cist\models\search\PegawaiSearch;
use backend\modules\cist\models\JumlahLibur;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * KuotaCutiTahunanController implements the CRUD actions for KuotaCutiTahunan model.
    * controller-id: kuota-cuti-tahunan
 * controller-desc: Controller untuk me-manage data Kuota Cuti Tahunan
 */
class KuotaCutiTahunanController extends Controller
{
    public function behaviors()
    {
        return [
            // TODO: crud controller actions are bypassed by default, set the appropriate privilege
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

    public function actionImport(){
        $modelImport = new \yii\base\DynamicModel([
                    'fileImport'=>'File Import',
                ]);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'],'file',['extensions'=>'xls,xlsx']);

        if(Yii::$app->request->post()){
            ini_set('max_execution_time', 1500);

            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
            if($modelImport->fileImport && $modelImport->validate()){
                $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objReader->setLoadAllSheets();
                $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
                $count = 0;
                do{
                    $sheetData = $objPHPExcel->getSheet($count++)->toArray(null,true,true,true);
                    $baseRow = 4;
                    while(!empty($sheetData[$baseRow]['B']) && trim($sheetData[$baseRow]['B'])!=''){
                        $pegawai = Pegawai::find()->where('deleted!=1')->andWhere(['nip' => (string)$sheetData[$baseRow]['C']])->orWhere(['alias' => (string)$sheetData[$baseRow]['D']])->orWhere(['like', 'nama', (string)$sheetData[$baseRow]['B']])->one();
                        if(!empty($pegawai)){
                            $model = KuotaCutiTahunan::find()->where('deleted!=1')->andwhere(['pegawai_id' => $pegawai->pegawai_id])->one();
                            if(!empty($model)){
                                $model->kuota = (int)$sheetData[$baseRow]['E'];
                                if(!$model->save()){
                                    \Yii::$app->messenger->addErrorFlash((string)$sheetData[$baseRow]['B']);
                                }
                            }else{
                                \Yii::$app->messenger->addErrorFlash("Tidak ada data kuota ".(string)$sheetData[$baseRow]['B']);
                            }
                        }else{
                            \Yii::$app->messenger->addErrorFlash("Tidak ada data pegawai ".(string)$sheetData[$baseRow]['B']);
                        }
                        $baseRow++;
                    }
                }while($count<$objPHPExcel->getSheetCount());
                ini_set('max_execution_time', 30);
            }
        }

        return $this->render('Import',[
                'modelImport' => $modelImport,
            ]);
    }

    /**
     * Lists all KuotaCutiTahunan models.
     * @return mixed
     */

    /**
     * action-id: index
     * action-desc: Display index of kuota cuti tahunan by default
     * */
    public function actionIndex()
    {
        $searchModel = new KuotaCutiTahunanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $query = WaktuGenerateKuotaCuti::find()->all();
        $jumlah_libur = JumlahLibur::find()->all();


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'query' => $query,
            'jumlah_libur' => $jumlah_libur
        ]);
    }

    /**
     * Displays a single KuotaCutiTahunan model.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: view
     * action-desc: Display detail view of kuota cuti tahunan by default
     * */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KuotaCutiTahunan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * action-id: add
     * action-desc: generate kuota cuti tahunan
     * */
    public function actionAdd()
    {
        $model = new KuotaCutiTahunan();
        $modelPegawai = new Pegawai();
        $modelGolongan = new GolonganKuotaCuti();

        $post =Yii::$app->request->post('KuotaCutiTahunan');
        $libur = $post['jumlah_libur'];

        if($model->load(Yii::$app->request->post())) {
            if ($libur == null) {
                \Yii::$app->messenger->addErrorFlash("Harap mengisi jumlah libur!");
                return $this->render('create', [
                    'model' => $model,
                    'modelPegawai' => $modelPegawai,
                ]);
            }
            $status = $modelPegawai->find()->where(['status_aktif_pegawai_id' => [1,2]])->all();

            foreach($status as $data){
                $kuota = KuotaCutiTahunan::find()->where('deleted!=1')->andWhere(['pegawai_id' => $data['pegawai_id']])->one();
                if(empty($kuota)){
                    $kuota = new KuotaCutiTahunan();
                    $kuota->pegawai_id = $data['pegawai_id'];
                //}
                if(date('Y') == date('Y', strtotime($data['tanggal_masuk']))){
                    $kuota->kuota = 0;
                }else if(date('Y') == date('Y', strtotime($data['tanggal_masuk']))+1){
                    $kuota->kuota = 12-((int)date('m', strtotime($data['tanggal_masuk'])))+1;
                }else if($data['status_ikatan_kerja_pegawai_id']!=4){
                    $kuota_golongan = GolonganKuotaCuti::find()->where(['golongan_kuota_cuti_id' => 1])->one();
                    $kuota->kuota = $kuota_golongan->kuota;
                }else{
                    $selisih = (int)date('Y')-date('Y', strtotime($data['tanggal_masuk']));
                    $kuota_golongan = GolonganKuotaCuti::find()->where(['<=', 'min_tahun_kerja', $selisih])->andWhere(['>', 'max_tahun_kerja', $selisih])->one();
                    $kuota->kuota = $kuota_golongan->kuota;
                }
                $kuota->kuota = $kuota->kuota-$libur<0?0:$kuota->kuota-$libur;
                $kuota->save();
                }
            }

            if(WaktuGenerateKuotaCuti::find()->where('deleted!=1')->exists()){
                $waktu_generate = WaktuGenerateKuotaCuti::find()->where('deleted!=1')->one();
                $waktu_generate->waktu_generate_terakhir = date("Y-m-d H:i:s");
                $waktu_generate->save();
            } else {
                $waktu_generate = new WaktuGenerateKuotaCuti();
                $waktu_generate->waktu_generate_terakhir = date("Y-m-d H:i:s");
                $waktu_generate->save();
            }

            if(JumlahLibur::find()->where('deleted!=1')->exists()){
                $jumlah_libur = JumlahLibur::find()->where('deleted!=1')->one();
                $jumlah_libur->jumlah = $libur;
                $jumlah_libur->save();
            } else {
                $jumlah_libur = new JumlahLibur();
                $jumlah_libur->jumlah = $libur;
                $jumlah_libur->save();
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelPegawai' => $modelPegawai,
            ]);
        }
    }

    /**
     * Updates an existing KuotaCutiTahunan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: edit
     * action-desc: Edit kuota cuti tahunan by default
     * */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->messenger->addSuccessFlash("Berhasil!");
                return $this->redirect(['index']);
            } else {
                print_r($model->errors);
                die;
            }
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing KuotaCutiTahunan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    /**
     * action-id: del
     * action-desc: Deleting kuota cuti tahunan by default
     * */
    public function actionDel($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KuotaCutiTahunan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KuotaCutiTahunan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KuotaCutiTahunan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * action-id: pegawai
     * action-desc: Finding pegawai for typeahead
     * */
    public function actionPegawai($query){
        $data = [];
        $pegawais = KuotaCutiTahunan::find()->select(['pegawai_id'])->where('deleted!=1');
        $hrds = Pegawai::find()
            ->select('pegawai_id, nip, nama, tanggal_masuk')
            ->where(['not in', 'pegawai_id', $pegawais])
            ->andWhere(['in', 'status_aktif_pegawai_id', [1,2]])
            ->andWhere('nip LIKE :query')
            ->orWhere('nama LIKE :query')
            ->addParams([':query' => '%'.$query.'%'])
            ->limit(10)
            ->orderBy(['nama' => SORT_ASC])
            ->asArray()
            ->all();
        foreach($hrds as $hrd){
            $dataValue = $hrd['nama'];
            $data [] = [
                'value' => $hrd['pegawai_id'],
                'data' => $dataValue,
            ];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        echo Json::encode($data);
    }
}

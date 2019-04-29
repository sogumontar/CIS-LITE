<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;
use backend\modules\rakx\models\Program;
use backend\modules\rakx\models\MataAnggaran;
use backend\modules\rakx\models\search\MataAnggaranSearch;
use backend\modules\rakx\models\TahunAnggaran;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\rakx\models\search\StrukturJabatanHasMataAnggaranSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\SwitchHandler;

/**
 * StrukturJabatanHasMataAnggaranController implements the CRUD actions for StrukturJabatanHasMataAnggaran model.
 */
class PenugasanAnggaranController extends Controller
{
    public $menuGroup = 'rakx-penugasan-anggaran';
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
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

    /**
     * Lists all StrukturJabatanHasMataAnggaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StrukturJabatanHasMataAnggaranSearch();
        $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->all();
        $searchModel->tahun_anggaran_name = $tahun_anggaran[0]->tahun_anggaran_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $struktur_jabatan = StrukturJabatan::getJabatanHasAnggaran();
        $mata_anggaran = MataAnggaran::find()->where('deleted != 1')->orderBy(['kode_anggaran' => SORT_ASC])->All();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'struktur_jabatan' => $struktur_jabatan,
            'mata_anggaran' => $mata_anggaran,
            'tahun_anggaran' => $tahun_anggaran,
        ]);
    }

    /**
     * Displays a single StrukturJabatanHasMataAnggaran model.
     * @param integer $id
     * @return mixed
     */
    public function actionPenugasanAnggaranView($id)
    {
        return $this->render('StrukturJabatanHasMataAnggaranView', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPenugasanAnggaranBrowse(){
        $this->menuGroup = '';
        $model = new StrukturJabatanHasMataAnggaran();
        if ($model->load(Yii::$app->request->post())) {
            if(!is_null($model->tahun_anggaran_id) && !is_null($model->struktur_jabatan_id)){
                return $this->redirect(['penugasan-anggaran-manage', 'tahun_anggaran_id' => $model->tahun_anggaran_id, 'struktur_jabatan_id' => $model->struktur_jabatan_id]);
            }
        } else {
            $struktur_jabatan = StrukturJabatan::getJabatanHasAnggaran();
            $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->orderBy(['tahun' => SORT_DESC])->All();
            return $this->render('StrukturJabatanHasMataAnggaranBrowse', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'tahun_anggaran' => $tahun_anggaran,
            ]);
        }
    }

    public function actionPenugasanAnggaranManage($tahun_anggaran_id, $struktur_jabatan_id)
    {
        $this->menuGroup = '';
        if(SwitchHandler::isSwitchRequest()){
            $jabatan_has_anggaran = StrukturJabatanHasMataAnggaran::find()->where(['tahun_anggaran_id' => $tahun_anggaran_id, 'struktur_jabatan_id' => $struktur_jabatan_id, 'mata_anggaran_id' => $_POST['id']])->andWhere('deleted!=1')->orderBy(['created_at' => SORT_DESC])->one();

            if(SwitchHandler::isTurningOn()){
                if($jabatan_has_anggaran === null){
                    $jabatan_has_anggaran2 = new StrukturJabatanHasMataAnggaran();
                    $jabatan_has_anggaran2->tahun_anggaran_id = $tahun_anggaran_id;
                    $jabatan_has_anggaran2->struktur_jabatan_id = $struktur_jabatan_id;
                    $jabatan_has_anggaran2->mata_anggaran_id = $_POST['id'];
                    if(!$jabatan_has_anggaran2->save()){
                        return SwitchHandler::respondWithFailed();       
                    }
                } 
            }else{
                if($jabatan_has_anggaran !== null){
                    $program = Program::find()->where(['struktur_jabatan_has_mata_anggaran_id' => $jabatan_has_anggaran->struktur_jabatan_has_mata_anggaran_id])->andWhere('deleted!=1')->count();
                    if($program==0){
                        if(!$jabatan_has_anggaran->softDelete()){
                            return SwitchHandler::respondWithFailed();
                        }
                    }else{
                        return SwitchHandler::respondWithFailed("Mata Anggaran sudah memiliki Program !");
                    }
                }
            }
            return SwitchHandler::respondWithSuccess();
        }
            
        $searchModelMataAnggaran = new MataAnggaranSearch();
        $dataProviderMataAnggaran = $searchModelMataAnggaran->search(Yii::$app->request->queryParams);

        $tahun_anggaran = TahunAnggaran::findOne($tahun_anggaran_id);
        $struktur_jabatan = StrukturJabatan::findOne($struktur_jabatan_id);
        $tahun_anggaran_last = TahunAnggaran::getIdYearBefore($tahun_anggaran_id);
        return $this->render('StrukturJabatanHasMataAnggaranManage', [
            'searchModelMataAnggaran' => $searchModelMataAnggaran,
            'dataProviderMataAnggaran' => $dataProviderMataAnggaran,
            'tahun_anggaran' => $tahun_anggaran,
            'struktur_jabatan' => $struktur_jabatan,
            'tahun_anggaran_last' => $tahun_anggaran_last,
        ]);
    }

    /**
     * Creates a new StrukturJabatanHasMataAnggaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPenugasanAnggaranAdd()
    {
        $model = new StrukturJabatanHasMataAnggaran();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->subtotal = explode(".", $model->subtotal)[0];
            if($model->save(false))
                return $this->redirect(['penugasan-anggaran-view', 'id' => $model->struktur_jabatan_has_mata_anggaran_id]);
        } else {
            $struktur_jabatan = StrukturJabatan::getJabatanHasAnggaran();
            $mata_anggaran = MataAnggaran::find()->where('deleted != 1')->All();
            $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->All();
            return $this->render('StrukturJabatanHasMataAnggaranAdd', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'mata_anggaran' => $mata_anggaran,
                'tahun_anggaran' => $tahun_anggaran,
            ]);
        }
    }

    /**
     * Updates an existing StrukturJabatanHasMataAnggaran model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPenugasanAnggaranEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['penugasan-anggaran-view', 'id' => $model->struktur_jabatan_has_mata_anggaran_id]);
        } else {
            $struktur_jabatan = StrukturJabatan::getJabatanHasAnggaran();
            $mata_anggaran = MataAnggaran::find()->where('deleted != 1')->All();
            $tahun_anggaran = TahunAnggaran::find()->where('deleted != 1')->All();
            return $this->render('StrukturJabatanHasMataAnggaranEdit', [
                'model' => $model,
                'struktur_jabatan' => $struktur_jabatan,
                'mata_anggaran' => $mata_anggaran,
                'tahun_anggaran' => $tahun_anggaran,
            ]);
        }
    }

    /**
     * Deletes an existing StrukturJabatanHasMataAnggaran model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPenugasanAnggaranDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StrukturJabatanHasMataAnggaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StrukturJabatanHasMataAnggaran the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StrukturJabatanHasMataAnggaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

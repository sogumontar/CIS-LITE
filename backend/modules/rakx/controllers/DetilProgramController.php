<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\DetilProgram;
use backend\modules\rakx\models\Program;
use backend\modules\rakx\models\search\DetilProgram as DetilProgramSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DetilProgramController implements the CRUD actions for DetilProgram model.
 */
class DetilProgramController extends Controller
{
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
     * Lists all DetilProgram models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DetilProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DetilProgram model.
     * @param integer $id
     * @return mixed
     */
    public function actionDetilProgramView($id)
    {
        return $this->render('DetilProgramView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DetilProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionDetilProgramAdd($program_id, $kode_program, $name, $jumlah)
    {
        $program = Program::find()->where(['program_id' => $program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-penambahan-anggaran');
        
        $model = new DetilProgram();

        if ($model->load(Yii::$app->request->post())/* && $model->save()*/) {
            $model->program_id = $program_id;
            if($model->save()){
                Program::toProgramValidity($model->program_id);
                //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
                return $this->redirect(['program/program-view', 'id' => $model->program_id, 'tab' => 'data_detil']);
            }
            else echo 'error';
        } else {
            return $this->render('DetilProgramAdd', [
                'model' => $model,
                'program_id'=>$program_id,
                'kode_program'=>$kode_program,
                'name'=>$name,
                'jumlah'=>$jumlah,
            ]);
        }
    }

    /**
     * Updates an existing DetilProgram model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDetilProgramEdit($id, $program_id, $kode_program, $name, $jumlah)
    {
        $program = Program::find()->where(['program_id' => $program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-penambahan-anggaran');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Program::toProgramValidity($model->program_id);
            //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(['program/program-view', 'id' => $model->program_id, 'tab' => 'data_detil']);
        } else {
            return $this->render('DetilProgramEdit', [
                'model' => $model,
                'program_id'=>$program_id,
                'kode_program'=>$kode_program,
                'name'=>$name,
                'jumlah'=>$jumlah,
            ]);
        }
    }

    /**
     * Deletes an existing DetilProgram model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDetilProgramDel($id)
    {
        $model = $this->findModel($id);
        $program = Program::find()->where(['program_id' => $model->program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-penambahan-anggaran');
        
        if($model->softDelete()){
            $model = Program::findOne($id);
            Program::toProgramValidity($model->program_id);
            //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(['program/program-view', 'id' => $this->findModel($id)->program_id, 'tab' => 'data_detil']);
        }
    }

    /**
     * Finds the DetilProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DetilProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DetilProgram::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

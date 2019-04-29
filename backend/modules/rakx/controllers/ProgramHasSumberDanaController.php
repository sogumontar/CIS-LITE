<?php

namespace backend\modules\rakx\controllers;

use Yii;
use backend\modules\rakx\models\ProgramHasSumberDana;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;
use backend\modules\rakx\models\Program;
use backend\modules\rakx\models\search\ProgramHasSumberDanaSearch;
use backend\modules\rakx\models\SumberDana;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramHasSumberDanaController implements the CRUD actions for ProgramHasSumberDana model.
 */
class ProgramHasSumberDanaController extends Controller
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
     * Lists all ProgramHasSumberDana models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProgramHasSumberDanaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProgramHasSumberDana model.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramHasSumberDanaView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProgramHasSumberDana model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionProgramHasSumberDanaAdd($program_id, $kode_program, $name, $jumlah)
    {
        $program = Program::find()->where(['program_id' => $program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-anggaran');
        
        $model = new ProgramHasSumberDana();

        if ($model->load(Yii::$app->request->post())/* && $model->save()*/) {
            $model->program_id = $program_id;
            if($model->save()){
                Program::toProgramValidity($model->program_id);
                //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
                return $this->redirect(['program/program-view', 'id' => $model->program_id, 'tab' => 'data_dana']);
            }else echo 'error';
        } else {
            $sd = ProgramHasSumberDana::find()->select(['sumber_dana_id'])->where('deleted!=1')->andWhere(['program_id' => $program_id])->asArray()->all();
            $sumber_dana = SumberDana::find()->where('deleted!=1')->andWhere(['not in', 'sumber_dana_id', $sd])->all();
            return $this->render('ProgramHasSumberDanaAdd', [
                'model' => $model,
                'sumber_dana' => $sumber_dana,
                'program_id'=>$program_id,
                'kode_program'=>$kode_program,
                'name'=>$name,
                'jumlah'=>$jumlah,
            ]);
        }
    }

    /**
     * Updates an existing ProgramHasSumberDana model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramHasSumberDanaEdit($id, $program_id, $kode_program, $name, $jumlah)
    {
        $program = Program::find()->where(['program_id' => $program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-anggaran');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Program::toProgramValidity($model->program_id);
            //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(['program/program-view', 'id' => $model->program_id, 'tab' => 'data_dana']);
        } else {
            $sd = ProgramHasSumberDana::find()->select(['sumber_dana_id'])->where('deleted!=1')->andwhere('sumber_dana_id!='.$model->sumber_dana_id)->asArray()->all();
            $sumber_dana = SumberDana::find()->where('deleted!=1')->andWhere(['not in', 'sumber_dana_id', $sd])->all();
            return $this->render('ProgramHasSumberDanaEdit', [
                'model' => $model,
                'sumber_dana' => $sumber_dana,
                'program_id'=>$program_id,
                'kode_program'=>$kode_program,
                'name'=>$name,
                'jumlah'=>$jumlah,
            ]);
        }
    }

    /**
     * Deletes an existing ProgramHasSumberDana model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionProgramHasSumberDanaDel($id)
    {
        $model = $this->findModel($id);
        $program = Program::find()->where(['program_id' => $model->program_id])->andWhere('deleted!=1')->one();
        if($program->status_program_id == 3 || $program->status_program_id >= 5)
            Yii::$app->jobControl->setAsJob('proses-anggaran');

        //if($model->softDelete()){
            $model->forceDelete();
            //$model = Program::findOne($id);
            Program::toProgramValidity($program->program_id);

            //StrukturJabatanHasMataAnggaran::updateSubtotal($model->program->struktur_jabatan_has_mata_anggaran_id);
            return $this->redirect(['program/program-view', 'id' => $program->program_id, 'tab' => 'data_dana']);
        //}
    }

    /**
     * Finds the ProgramHasSumberDana model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProgramHasSumberDana the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProgramHasSumberDana::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

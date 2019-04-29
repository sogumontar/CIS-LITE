<?php

namespace backend\modules\inst\controllers;

use Yii;
use backend\modules\inst\models\Unit;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\Instansi;
use backend\modules\inst\models\search\UnitSearch;
use backend\modules\inst\models\search\StrukturJabatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\SwitchHandler;

class UnitController extends Controller
{
    public $menuGroup = 'inst-unit';
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            /*'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => [],
                ],*/
                
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $instansi = Instansi::find()->where('deleted != 1')->All();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'instansi' => $instansi,
        ]);
    }

    public function actionUnitMemberAdd($unit_id, $instansi_id){
        $model = $this->findModel($unit_id);

        if(SwitchHandler::isSwitchRequest()){
            $jabatan = StrukturJabatan::findOne($_POST['id']);

            if($model === null || $jabatan === null){
                //return '<script type="text/javascript">alert("1");</script>';
                return SwitchHandler::respondWithFailed();
            }

            if(SwitchHandler::isTurningOn()){
                $jabatan->unit_id = $model->unit_id;
                if(!$jabatan->save()){
                    //return '<script type="text/javascript">alert("2");</script>';
                    return SwitchHandler::respondWithFailed();       
                }
            }else{
                $jabatan->unit_id = null;
                if(!$jabatan->save()){
                    //return '<script type="text/javascript">alert("3");</script>';
                    return SwitchHandler::respondWithFailed();
                }
            }
            
            return SwitchHandler::respondWithSuccess();
        }

        //$memberSearchModel = new StrukturJabatanSearch();
        //$memberDataProvider = $memberSearchModel->searchWithWorkgroups(Yii::$app->request->queryParams, $id, true);
        $searchModel = new StrukturJabatanSearch();
        $dataProvider = $searchModel->searchByInstansiByNoUnit($instansi_id, $unit_id, Yii::$app->request->queryParams);
        $instansi = Instansi::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->one();
        return $this->render('UnitMemberAdd', ['model' => $model,
                                                'instansi' => $instansi,
                                               'dataProvider' => $dataProvider,
                                               'searchModel' => $searchModel,
                                               ]);
    }

    public function actionUnitView($id)
    {
        return $this->render('UnitView', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUnitAdd()
    {
        $this->menuGroup = '';
        $model = new Unit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $kepala = StrukturJabatan::find()->where(['struktur_jabatan_id' => $model->kepala])->andWhere('deleted != 1')->one();
            $kepala->unit_id = $model->unit_id;
            if($kepala->save())
                return $this->redirect(['unit-view', 'id' => $model->unit_id]);
        } else {
            $struktur_jabatan = array();//StrukturJabatan::find()->where(['not', ['deleted' => 1]])->All();
            $instansi = Instansi::find()->where('deleted != 1')->All();
            return $this->render('UnitAdd', [
                'model' => $model,
                'instansi' => $instansi,
                'struktur_jabatan' => $struktur_jabatan,
            ]);
        }
    }

    public function actionKepalaByInstansiList($instansi_id)
    {
        /*$is_kepala = Unit::find()->select('kepala')->where(['not', ['kepala' => null]])->andWhere('deleted != 1')->all();
        $temp = array();
        foreach($is_kepala as $i)
            $temp[] = $i->kepala;*/
         $kepalas = StrukturJabatan::find()
         ->where(['instansi_id' => $instansi_id])
         //->andWhere(['not in', 'struktur_jabatan_id', $temp])
         ->andWhere(['unit_id' => null])
         ->andWhere('deleted != 1')
         ->orderBy(['parent' => SORT_ASC])
         ->all();

         echo "<option value>Kepala</option>";
         if(!empty($kepalas)){
            foreach($kepalas as $k){
                echo "<option value='".$k->struktur_jabatan_id."'>".$k->jabatan."</option>";
            }
         }
    }

    public function actionUnitEdit($id)
    {
        $model = $this->findModel($id);
        $k = $model->kepala;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($k!=$model->kepala){
                if(Yii::$app->runAction('/inst/struktur-jabatan/unit-edit', ['id' => $k, 'unit' => null]) && Yii::$app->runAction('/inst/struktur-jabatan/unit-edit', ['id' => $model->kepala, 'unit' => $model->unit_id])){
                    return $this->redirect(['unit-view', 'id' => $model->unit_id]);
                }
            }
            return $this->redirect(['unit-view', 'id' => $model->unit_id]);
        } else {
            $struktur_jabatan = StrukturJabatan::find()
                ->where(['unit_id' => null])
                ->orWhere(['unit_id' => $model->unit_id])
                ->andWhere('deleted != 1')
                ->andWhere(['instansi_id' => $model->instansi_id])
                ->All();
            $instansi = Instansi::find()->where('deleted != 1')->All();
            return $this->render('UnitEdit', [
                'model' => $model,
                'instansi' => $instansi,
                'struktur_jabatan' => $struktur_jabatan,
            ]);
        }
    }

    public function actionUnitDel($id)
    {
        $_unit = Unit::find()
            ->where(['unit_id'=>$id])
            ->andWhere('deleted != 1')
            ->one();

        if($this->_strukturUnitDel($id) && $_unit->softDelete())
        {
            //hapus file dari puro dan dari database
            //\Yii::$app->fileManager->delete($_complaint->image);
            \Yii::$app->messenger->addInfoFlash("Unit <b>".$_unit->name."</b> berhasil di hapus");
            return $this->redirect(['index']);
        }
    }

    public function _strukturUnitDel($id)
    {
        $strukturs = StrukturJabatan::find()->where(['unit_id' => $id])->andWhere('deleted != 1')->all();
        foreach($strukturs as $s){
            $s->unit_id = null;
            if(!$s->save())
                return false;
        }
        return true;
    }

    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

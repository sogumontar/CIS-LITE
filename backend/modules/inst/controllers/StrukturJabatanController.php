<?php

namespace backend\modules\inst\controllers;

use Yii;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\search\StrukturJabatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\modules\inst\models\Instansi;
use backend\modules\inst\models\Unit;

/**
 * StrukturJabatanController implements the CRUD actions for StrukturJabatan model.
 */
class StrukturJabatanController extends Controller
{
    public function behaviors()
    {
        return [
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

    /**
     * Lists all StrukturJabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StrukturJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$struktur_jabatan = StrukturJabatan::find()->where(['not', ['deleted' => 1]])->All();
        $instansi = Instansi::find()->where('deleted != 1')->All();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'instansi' => $instansi,
        ]);


        /*$searchModel = new StrukturJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $parent = StrukturJabatan::find()->where(['not', ['deleted' => 1]])->All();
        $instansi = Instansi::find()->where(['not', ['deleted' => 1]])->All();
        $unit = Unit::find()->where(['not', ['deleted' => 1]])->All();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'parent' => $parent,
            'instansi' => $instansi,
            'unit' => $unit,
        ]);*/
    }

    public function actionStrukturJabatanView($id, $otherRenderer = false)
    {
        $model = $this->findModel($id);
        //if($otherRenderer){
            $inst = Instansi::find()->where(['instansi_id' => $model->instansi_id])->andWhere('deleted != 1')->one();
        //}
        return $this->render('StrukturJabatanView', [
            'model' => $model,
            'otherRenderer' => $otherRenderer,
            'instansi_name' => $inst->name,
        ]);
    }

    public function actionStrukturJabatanEdit($id, $otherRenderer = false)
    {
        $model = $this->findModel($id);
        $old_unit = $model->unit_id;

        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if($old_unit != $model->unit_id && $old_unit!=null){
                $unit = Unit::find()->where(['unit_id' => $old_unit])->andWhere('deleted != 1')->one();
                if($unit->kepala == $model->struktur_jabatan_id){
                    \Yii::$app->messenger->addErrorFlash("Struktur Jabatan <b>".$model->jabatan."</b> merupakan Pejabat Unit <b>".$unit->name."</b>, tidak dapat berganti Unit");
                    return $this->redirect(Yii::$app->request->referrer);
                }
                else if($model->save())
                    return $this->redirect(['struktur-jabatan-view', 'id' => $model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer]);
            }
            else if($model->save())
                return $this->redirect(['struktur-jabatan-view', 'id' => $model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer]);
        } else {
            $inst = Instansi::find()->where(['instansi_id' => $model->instansi_id])->andWhere('deleted != 1')->one();
            $parent = StrukturJabatan::find()->where(['not', ['deleted' => 1]])->All();
            $instansi = Instansi::find()->where(['not', ['deleted' => 1]])->All();
            $unit = Unit::find()->where(['not', ['deleted' => 1]])->All();
            $tenant = [['tenant_id' => 0, 'tenant_name' => 'Single'],['tenant_id' => 1, 'tenant_name' => 'Multi Tenant']];
            $mata_anggaran = [['ma_id' => 1, 'ma_name' => 'Ya'],['ma_id' => 0, 'ma_name' => 'Tidak']];
            $laporan = [['lap_id' => 1, 'lap_name' => 'Ya'],['lap_id' => 0, 'lap_name' => 'Tidak']];
            return $this->render('StrukturJabatanEdit', [
                'model' => $model,  
                'parent' => $parent,
                'instansi' => $instansi,
                'unit' => $unit,
                'instansi_name' => $inst->name,
                'otherRenderer' => $otherRenderer,
                'tenant' => $tenant,
                'mata_anggaran' => $mata_anggaran,
                'laporan' => $laporan,
            ]);
        }
    }

    public function actionUnitEdit($id, $unit){
        $jabatan = StrukturJabatan::find()->where(['struktur_jabatan_id' => $id])->one();
        $jabatan->unit_id = $unit;
        
        $authStatus = false;
        if($jabatan->save())
            $authStatus = true;
        return $authStatus;
    }

    public function actionStrukturJabatanAdd($instansi_id, $parent, $otherRenderer = false)
    {
        $model = new StrukturJabatan();
        $model->instansi_id=$instansi_id;
        $parent_id = $parent;
        $parent_name = "-";
        if($parent!=0){
            $model->parent=$parent;
            $p = StrukturJabatan::findOne(['struktur_jabatan_id' => $parent, 'deleted' => 0]);
            $parent_name = $p->jabatan;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['inst-manager/strukturs', 'instansi_id' => $model->instansi_id]);
            //return $this->redirect(['struktur-jabatan-view', 'id' => $model->struktur_jabatan_id]);
        } else {
            $parent = StrukturJabatan::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->orderBy(['jabatan' => SORT_ASC])->All();
            $instansi = Instansi::find()->where('deleted != 1')->orderBy(['name' => SORT_ASC])->All();
            $unit = Unit::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->orderBy(['name' => SORT_ASC])->All();
            $inst = Instansi::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->one();
            $tenant = [['tenant_id' => 0, 'tenant_name' => 'Single'],['tenant_id' => 1, 'tenant_name' => 'Multi Tenant']];
            $mata_anggaran = [['ma_id' => 1, 'ma_name' => 'Ya'],['ma_id' => 0, 'ma_name' => 'Tidak']];
            $laporan = [['lap_id' => 1, 'lap_name' => 'Ya'],['lap_id' => 0, 'lap_name' => 'Tidak']];
            return $this->render('StrukturJabatanAdd', [
                'model' => $model,
                'parent' => $parent,
                'parent_id' => $parent_id,
                'parent_name' => $parent_name,
                'instansi' => $instansi,
                'inst' => $inst,
                'unit' => $unit,
                'instansi_id' => $instansi_id,
                'otherRenderer' => $otherRenderer,
                'tenant' => $tenant,
                'mata_anggaran' => $mata_anggaran,
                'laporan' => $laporan,
            ]);
        }
    }

    public function actionStrukturJabatanDel($id, $otherRenderer=false, $confirm=false)
    {
        if($confirm){
            if($this->_strukturDel($id)){
                $struktur = StrukturJabatan::find()
                    ->where(['struktur_jabatan_id' => $id])
                    ->andWhere('deleted = 1')
                    ->one();
                \Yii::$app->messenger->addInfoFlash("Struktur Jabatan <b>".$struktur->jabatan."</b> berhasil di hapus");
                return $this->redirect(['inst-manager/strukturs?instansi_id='.$struktur->instansi_id]);
            }
            else { echo 'FAILED'; die; }
        }
        $s = StrukturJabatan::findOne(['struktur_jabatan_id' => $id, 'deleted' => 0]);
        $i = Instansi::findOne(['instansi_id' => $s->instansi_id, 'deleted' => 0]);
        return $this->render('confirmDelete', ['id' => $id, 'model' => $s, 'instansi_name' => $i->name, 'otherRenderer' => $otherRenderer]);
    }

    public function _strukturDel($jabatan_id){
        $struktur = StrukturJabatan::find()
            ->with('strukturJabatans')
            ->where(['struktur_jabatan_id' => $jabatan_id])
            ->andWhere('deleted != 1')
            ->one();
        foreach($struktur->strukturJabatans as $s){
            if(!$this->_strukturDel($s->struktur_jabatan_id))
                return false;
        }
        $pejabats = Pejabat::find()
            ->where(['struktur_jabatan_id' => $struktur->struktur_jabatan_id])
            ->andWhere('deleted != 1')
            ->all();
        foreach($pejabats as $p){
            if(!$p->softDelete())
                return false;   
        }
        if(!$struktur->softDelete())
            return false;
        return true;
    }

    protected function findModel($id)
    {
        if (($model = StrukturJabatan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\modules\inst\controllers;

use Yii;
use backend\modules\inst\models\Instansi;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\Unit;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\search\InstansiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\inst\models\form\StructureDefinition;
use common\helpers\LinkHelper;
use yii\helpers\Url;


class InstManagerController extends Controller
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
                        'delete' => ['POST'],
                    ],
                ],
            ];
    }

    public function actionIndex()
    {
        $inss = Instansi::find()->where('deleted != 1')->all();

        return $this->render('index', ['inss' => $inss]);
    }

    public function actionFindJabatan($query, $instansi_id){
        $data = [];
        $jabatans = StrukturJabatan::find()
                    ->where(['instansi_id' => $instansi_id])
                    ->andWhere('deleted != 1')
                    ->andWhere('jabatan LIKE :jabatan')
                    ->addParams([':jabatan' => '%'.$query.'%'])
                    ->limit(10)
                    ->asArray()
                    ->all();
        foreach ($jabatans as $jabatan) {
            $data []  = [
                            'value' => $jabatan['struktur_jabatan_id'],
                            'data' => $jabatan['jabatan']
                        ];
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        echo Json::encode($data);
    }

    public function actionStrukturs($instansi_id){

        $strukturs = StrukturJabatan::find()->with(['strukturJabatans', 'unit', 'pejabats'])
                                 ->where(['instansi_id' => $instansi_id])
                                 ->andWhere('deleted != 1')
                                 ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                                 ->all();       
        
        $inst = Instansi::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->one();
        $strukturs = $this->_buildStrukturs($strukturs, $instansi_id);

        return $this->render('strukturs', ['instansi_id'=>$instansi_id, 'strukturs' => $strukturs, 'instansi_name' => $inst->name]);
    }

    /*public function actionChildren(){
        if(null !== Yii::$app->request->post('instansi_id') && null !== Yii::$app->request->post('jabatan_id')){
            $instansi_id = Yii::$app->request->post('instansi_id');
            $jabatan_id = Yii::$app->request->post('jabatan_id');
            $strukturs = StrukturJabatan::find()->with(['unit'])
                                     ->where(['parent' => $jabatan_id, 'instansi_id' => $instansi_id])
                                     ->andWhere('deleted != 1')
                                     ->orderBy(['jabatan' => SORT_ASC])
                                     ->asArray()->all();
            
            return json_encode($strukturs);
        }else{
            return "Load data failed !";
        }
    }*/

    public function _buildStrukturs($strukturs, $instansi_id)
    {
        $index = array();
        $struktur_key = array();
        $strukturs_res = array();
        $tree_res = array();
        foreach($strukturs as $s)
        {
            $struktur_key[$s->struktur_jabatan_id] = $s;
        }

        $i = 0;
        foreach($strukturs as $s)
        {
            if(!in_array($s->struktur_jabatan_id, $index)){
                $this->_recursiveStrukturJabatan($struktur_key, $struktur_key[$s->struktur_jabatan_id], $i, $index, $strukturs_res, $instansi_id, $tree_res);
            }
        }
        return $tree_res;
    }
        
    function _recursiveStrukturJabatan($struktur_key, $struktur_jabatan, $i, &$index, &$strukturs_res, $instansi_id, &$tree_res)
    {
        $strukturs_res[] = $struktur_jabatan;
        $index[] = $struktur_jabatan->struktur_jabatan_id;
        /*if($i==0){
          $tree_res .= '<tr class="treegrid-'.$struktur_jabatan->struktur_jabatan_id.'">';
        }else{
          $tree_res .= '<tr class="treegrid-'.$struktur_jabatan->struktur_jabatan_id.' treegrid-parent-'.$struktur_jabatan->parent.'">';
        }*/
        $tree_res[] = $struktur_jabatan;
        if(isset($struktur_jabatan->strukturJabatans)){
            foreach($struktur_jabatan->strukturJabatans as $s){
              if($s->deleted!=1 && $s->instansi_id==$struktur_jabatan->instansi_id){
                $this->_recursiveStrukturJabatan($struktur_key, $struktur_key[$s->struktur_jabatan_id], $i+1, $index, $strukturs_res, $instansi_id, $tree_res);
              }
            }
        }
        $tree_res[] = ['parent' => $struktur_jabatan->struktur_jabatan_id];
    }

    public function actionInstansiAdd()
    {
        $model = new Instansi();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('InstansiAdd', [
                'model' => $model,
            ]);
        }
    }

    public function actionInstansiEdit($id)
    {
        $model = $this->findModelInstansi($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('InstansiEdit', [
                'model' => $model,
            ]);
        }
    }

    public function actionInstansiDel($id, $confirm=false){
        if($confirm){
            if($this->_strukturDel($id)){
                $instansi = Instansi::find()
                    ->where(['instansi_id' => $id])
                    ->andWhere('deleted = 1')
                    ->one();
                \Yii::$app->messenger->addInfoFlash("Instansi <b>".$instansi->name."</b> berhasil di hapus");
                return $this->redirect(['index']);
            }
        }
        $s = Instansi::findOne(['instansi_id' => $id, 'deleted' => 0]);
        return $this->render('confirmDelete', ['id' => $id, 'name' => $s->name]);
    }

    public function _strukturDel($instansi_id){
        $strukturs = StrukturJabatan::find()
            ->select(['struktur_jabatan_id'])
            ->where(['instansi_id' => $instansi_id])
            ->andWhere('deleted != 1')
            ->all();
        $pejabats = Pejabat::find()
            ->where(['in', 'struktur_jabatan_id', $strukturs])
            ->andWhere('deleted != 1')
            ->all();
        $strukturs = StrukturJabatan::find()
            ->where(['instansi_id' => $instansi_id])
            ->andWhere('deleted != 1')
            ->all();
        $units = Unit::find()
            ->where(['instansi_id' => $instansi_id])
            ->andWhere('deleted != 1')
            ->all();
        $instansi = Instansi::find()
            ->where(['instansi_id' => $instansi_id])
            ->andWhere('deleted != 1')
            ->one();
        //\Yii::$app->debugger->print_array($pejabats, true);
        foreach($units as $unit){
            if(!$unit->softDelete())
                return false;
        }
        foreach($strukturs as $struktur){
            if(!$struktur->softDelete())
                return false;
        }
        foreach($pejabats as $pejabat){
            if(!$pejabat->softDelete())
                return false;
        }
        if(!$instansi->softDelete())
            return false;
        return true;
    }    

    public function actionImportStructureDefinition($instansi_id, $parent_id){
        $model = new StructureDefinition();

        //array hasil
        $struct_result = null;
        if (\Yii::$app->request->isPost) {
            $definitionFile = \Yii::$app->fileManager->saveAsLocalTemp('file');

            /************struktur dari json*************/
            $structureJson = json_decode(file_get_contents($definitionFile->localPath[0]));          

            /************kalo json tidak valid*************/
            if(!$structureJson){
                \Yii::$app->messenger->addErrorFlash("Definition file is not valid !!");
                return $this->render('importStructureDefinition', ['model' => $model, 'structure' => $struct_result]);
            }
            
            /************mencari struktur yg sama dr database*************/
            $structureExisting = StrukturJabatan::find()
                                 ->where(['instansi_id' => $instansi_id, 'jabatan' => $structureJson->jabatan])
                                 ->andWhere('deleted != 1')
                                 ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                                 ->one();

            $struct_key = array();
            $this->_pairStructure($instansi_id, 'jabatan', $struct_key);
            //\Yii::$app->debugger->print_array($struct_key, true); 
            $i=0;

            if(isset($structureJson->strukturJabatans)){
                $this->_recursiveAddStrukturJabatan($struct_key, $structureJson, $struct_result, $struct_result['struktur_jabatan_id'], $instansi_id, $i++);   
            }
            else{
                foreach($structureJson as $s)
                {
                    $this->_recursiveAddStrukturJabatan($struct_key, $s, $struct_result, $struct_result['struktur_jabatan_id'], $instansi_id, $i++);
                }
            }
            //\Yii::$app->debugger->print_array($struct_result, true); 
        } 

        return $this->render('importStructureDefinition', ['model' => $model, 'structure' => $struct_result]);
    }

    function _addStrukturJabatanToArray(&$struct_result, $struktur_jabatan_id, $jabatan, $inisial, $is_multi_tenant, $unit_id, $is_existed, $msg){
        $struct_result[] = [
                    'struktur_jabatan_id' => $struktur_jabatan_id,
                    'jabatan' => $jabatan,
                    'inisial' => $inisial,
                    'is_multi_tenant' => $is_multi_tenant,
                    'unit_id' => $unit_id,
                    'isExisted' => $is_existed,
                    'msg' => $msg,
                    'strukturJabatans' => []
                ];
    }

    function _addStrukturJabatan($instansi_id, $jabatan, $parent, $inisial, $is_multi_tenant, $unit_id){
        $newStructure = new StrukturJabatan();
        $newStructure->instansi_id = $instansi_id;
        $newStructure->jabatan = $jabatan;
        if($parent!=0)
            $newStructure->parent = $parent;
        $newStructure->inisial = $inisial;
        $newStructure->is_multi_tenant = $is_multi_tenant;
        $newStructure->unit_id = $unit_id;

        if($newStructure->save()){
            return $newStructure;
        }
    }

    function _isUniqueInisial($instansi_id, $inisial){
         $structureExisting = StrukturJabatan::find()
                                 ->where(['instansi_id' => $instansi_id, 'inisial' => $inisial])
                                 ->andWhere('deleted != 1')
                                 ->one();
        return $structureExisting == null;
    }

    function _recursiveAddStrukturJabatan($struct_key, $struktur_json, &$struct_result, $parent, $instansi_id, $i){
        //\Yii::$app->debugger->print_array($struktur_json, true); 
        $subparent = 0;
        //data baru
        if(!isset($struct_key[$struktur_json->jabatan])){
            $isUniqueInisial = $this->_isUniqueInisial($instansi_id, $struktur_json->inisial);

            if($isUniqueInisial){
                //simpan ke database
                $newStruktur = $this->_addStrukturJabatan($instansi_id, $struktur_json->jabatan, $parent, $struktur_json->inisial, $struktur_json->is_multi_tenant, $struktur_json->unit_id);
            }
            //simpan ke array
            $this->_addStrukturJabatanToArray($struct_result, $newStruktur->struktur_jabatan_id, $newStruktur->jabatan, $newStruktur->inisial, $newStruktur->is_multi_tenant, $newStruktur->unit_id, !$isUniqueInisial, $isUniqueInisial?"Data Added":"Inisial is already used");
            $subparent = $newStruktur->struktur_jabatan_id;
        }else{
            //simpan ke array
            $this->_addStrukturJabatanToArray($struct_result, $struct_key[$struktur_json->jabatan]->struktur_jabatan_id, $struct_key[$struktur_json->jabatan]->jabatan, $struct_key[$struktur_json->jabatan]->inisial, $struct_key[$struktur_json->jabatan]->is_multi_tenant, $struct_key[$struktur_json->jabatan]->unit_id, true, "Jabatan is already used");

            $subparent = $struct_key[$struktur_json->jabatan]->struktur_jabatan_id;
        }
        $j=0;
        foreach($struktur_json->strukturJabatans as $s){
            $this->_recursiveAddStrukturJabatan($struct_key, $s, $struct_result[$i]['strukturJabatans'], $subparent, $instansi_id, $j++);
        }
    }

    public function actionExportStructureToJson($jabatan_id, $instansi_id){
        $struct_key = array();

        $this->_pairStructure($instansi_id, 'jabatan', $struct_key);
        
        $structureExisting = StrukturJabatan::find()->with(['strukturJabatans'])
                                 ->where(['instansi_id' => $instansi_id, 'struktur_jabatan_id' => $jabatan_id])
                                 ->andWhere('deleted != 1')
                                 ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                                 ->one();
        $struct_result = [
                    'struktur_jabatan_id' => $structureExisting->struktur_jabatan_id,
                    'jabatan' => $structureExisting->jabatan,
                    'inisial' => $structureExisting->inisial,
                    'is_multi_tenant' => $structureExisting->is_multi_tenant,
                    'unit_id' => $structureExisting->unit_id,
                    'strukturJabatans' => []
                ];
        $i=0;
        foreach($structureExisting->strukturJabatans as $s){
            if($s->deleted!=1 && $s->instansi_id==$instansi_id){
                $this->_recursiveAddStrukturJabatan($struct_key, $struct_key[$s->jabatan], $struct_result['strukturJabatans'], $struct_result['struktur_jabatan_id'], $instansi_id, $i++);
            }
        }

        //\Yii::$app->debugger->print_array($strukturs_res, true);

        header('Content-Disposition: attachment; filename='.$struct_result['jabatan'].'.json');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        echo Json::encode($struct_result);
    }

    protected function findModelInstansi($id)
    {
        if (($model = Instansi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionTrees(){
        if(null !== Yii::$app->request->post('instansi_id')){
            $instansi_id = Yii::$app->request->post('instansi_id');
            $strukturs = StrukturJabatan::find()->with(['strukturJabatans', 'unit'])
                                 ->where(['instansi_id' => $instansi_id])
                                 ->andWhere('deleted != 1')
                                 ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                                 ->all();
            $struktur_pair = array();
            $this->_pairStructure($strukturs, 'struktur_jabatan_id', $struktur_pair);
            $tree_res = array();
            $i = 0;
            foreach($strukturs as $s){
                if(isset($s->parent))
                    break;
                $tree_res[$i]['innerHTML'] = ['struktur_jabatan_id' => $s->struktur_jabatan_id, 'jabatan' => $s->jabatan,
                    'unit_id' => $s->unit_id, 'unit_name' => $s->unit['name']];
                foreach($s->pejabats as $p){
                    if($p->status_aktif==1){
                        $tree_res[$i]['innerHTML']['pejabats'][] = ['nama' => $p->pegawai['nama'], 'hp' => $p->pegawai['hp'], 'email' => $p->pegawai['email']];
                    }
                }
                if(isset($s->strukturJabatans)){
                    $j=0;
                    foreach($s->strukturJabatans as $t){
                        if($t->deleted!=1 && $t->instansi_id==$s->instansi_id){
                            $this->_recursiveTrees($struktur_pair, $struktur_pair[$t->struktur_jabatan_id], $tree_res[$i]['children'], $j++);
                        }
                    }
                }
                $i++;
            }
            /*echo "<pre>";
            print_r($tree_res);
            die;*/
            return json_encode($tree_res);
        }else{
            return "Ajax failed";
        }
    }

    public function _recursiveTrees($struktur_pair, $s, &$result, $i){
        $result[$i]['innerHTML'] = ['struktur_jabatan_id' => $s->struktur_jabatan_id, 'jabatan' => $s->jabatan,
            'unit_id' => $s->unit_id, 'unit_name' => $s->unit['name']];
        foreach($s->pejabats as $p){
            if($p->status_aktif==1){
                $result[$i]['innerHTML']['pejabats'][] = ['nama' => $p->pegawai['nama'], 'hp' => $p->pegawai['hp'], 'email' => $p->pegawai['email']];
            }
        }
        if(isset($s->strukturJabatans)){
            $j=0;
            foreach($s->strukturJabatans as $t){
                if($t->deleted!=1 && $t->instansi_id==$s->instansi_id){
                    $this->_recursiveTrees($struktur_pair, $struktur_pair[$t->struktur_jabatan_id], $result[$i]['children'], $j++);
                }
            }
        }       
    }

    function _pairStructure($strukturs, $attr, &$result){

        foreach($strukturs as $s)
        {
            $result[$s->getAttribute($attr)] = $s;
        }
    }

    public function actionTreeView($instansi_id=null)
    {
        $instansis = Instansi::find()->where('deleted != 1')->all();
        if($instansi_id==null){
            if(isset($instansis[0]->instansi_id))
                $instansi_id = $instansis[0]->instansi_id;  
        }
        
        //$struct_result = $this->_getTreeStruct($instansi_id);
        $strukturs = StrukturJabatan::find()->with(['strukturJabatans', 'unit'])
                                 ->where(['instansi_id' => $instansi_id])
                                 ->andWhere('deleted != 1')
                                 ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                                 ->all();

        $strukturs_pair = StrukturJabatan::find()->with(['strukturJabatans', 'unit'])
                     ->where(['instansi_id' => $instansi_id])
                     ->andWhere('deleted != 1')
                     ->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC])
                     ->all();
        //echo "<pre>";
        //\Yii::$app->debugger->print_array($struct_result, true);

        $inst = Instansi::find()->where(['instansi_id' => $instansi_id])->andWhere('deleted != 1')->one();

        //if(LinkHelper::isPjaxRequest()){
        //    return 'tss';

            //return $this->renderPartial('trees', ['strukturs'=>$struct_result, 'instansi_id' => $instansi_id, 'instansi_name' => $inst->name]);
        //}
        if(isset($instansis) && isset($instansi_id) && isset($inst))
        return $this->render('TreeView', ['instansis' => $instansis, 'strukturs'=>$strukturs, 'strukturs_pair' => $strukturs_pair, 'instansi_id' => $instansi_id, 'instansi_name' => $inst->name]);

        /*return $this->render('TreeView', [
            'strukturs' => $struct_result,
            'instansi_id' => $instansi_id,
            'instansi_name' => $inst->name,
        ]);*/
    }
}

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use backend\assets\JqueryTreegridAsset;

$uiHelper = Yii::$app->uiHelper;
JqueryTreegridAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\LokasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Lokasi';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<table class="tree table">
    <thead>
        <tr>
            <th class="col-md-3">Nama Lokasi</th>
            <th class="col-md-5">Deskripsi Lokasi</th>
            <th class="col-md-4">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($lokasis as $key => $lokasi) {
        ?>
        <tr class="treegrid-i-<?=$lokasi->lokasi_id?>">
                <td><?=$lokasi->nama_lokasi?></td>
                <td><?=$lokasi->desc?></td>
                <td>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-eye', 'tooltip' => "Detail Lokasi", 'url'=>Url::toRoute(['lokasi-view', 'lokasi_id'=>$lokasi->lokasi_id])]) ?>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-pencil', 'tooltip' => "Edit Lokasi", 'url'=>Url::toRoute(['lokasi-edit', 'lokasi_id'=>$lokasi->lokasi_id])]) ?>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-trash', 'tooltip' => "Hapus Lokasi", 'url'=>Url::toRoute(['lokasi-del', 'lokasi_id'=>$lokasi->lokasi_id])]) ?>
                </td>
        </tr>
        <?php
            //render childs
            if($lokasi->getChilds()!=null){
                echo $this->render('childs',['_parentId'=>$lokasi->lokasi_id, '_childs'=>$lokasi->getChilds()]);
            }
        ?>
        <tr class="treegrid-ia-<?=$lokasi->lokasi_id?> treegrid-parent-i-<?=$lokasi->lokasi_id?>">
            <td><?= LinkHelper::renderLinkButton(['label'=> '_____',
                                                  'icon'=> 'glyphicon-plus', 
                                                  'url'=>Url::toRoute(["lokasi-add",'parent_id'=>$lokasi->lokasi_id]), 
                                                  'class'=>'btn-success btn-xs'])?> 
            </td>
            <td class="text-grey italic">Tambah Detail Lokasi</td> 
            <td></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td><?= LinkHelper::renderLinkButton(['label'=> '______',
                                                  'icon'=> 'glyphicon-plus', 
                                                  'url'=>Url::toRoute(["lokasi-add",'parent_id'=>null]), 
                                                  'class'=>'btn-success btn-xs'])?> 
            </td>
            <td class="text-grey italic">Tambah Lokasi</td>  
            <td></td>
        </tr>
    </tbody>
</table>

<?php 
  $this->registerJs(
    "$(document).ready(function() {
        $('.tree').treegrid({
          expanderExpandedClass: 'glyphicon glyphicon-minus',
          expanderCollapsedClass: 'glyphicon glyphicon-plus',
          initialState: 'collapsed'
        });
    });

  ", 
    View::POS_END);
?>

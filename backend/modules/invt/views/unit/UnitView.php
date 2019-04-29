<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;

$uiHelper = Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Unit */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'unit', 'url' => ['unit-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
 <div class="pull-right">
     <?=$uiHelper->renderButtonSet([
        'template' => ['admin','edit','hapus'],
        'buttons' => [
            'admin' =>['url' => Url::to(['unit/manage-admin', 'unit_id'=> $model->unit_id]), 'label' => 'Manage Admin', 'icon' => 'fa fa-user'],
            'edit' =>['url' => Url::to(['unit/unit-edit', 'id'=> $model->unit_id]), 'label' => 'Edit Unit', 'icon' => 'fa fa-pencil'],
            'hapus'=>['url' => Url::to(['unit/unit-del', 'id'=> $model->unit_id]), 'label' => 'Hapus Unit', 'icon' => 'fa fa-trash'],
        ]  
     ]) ?>
 </div> 

<?=$uiHelper->beginSingleRowBlock(['id'=>'barang-content']); ?>
    <?= DetailView::widget([
        'model' => $model,
        'options' =>[
                'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            [
                'attribute'=>'nama',
                'label'=>'Nama Unit',
            ],            
            [
                'attribute'=>'desc',
                'label'=>'Deskripsi Unit',
            ], 
        ],
    ]) ?>

<?=$uiHelper->renderContentSubHeader("Admin Unit: " . $model->nama) ?>
<?=$uiHelper->renderLine() ?>
<?=$uiHelper->beginSingleRowBlock(['id' => 'admin-unit']) ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $modelsearch,
            'tableOptions' => ['class' => 'table table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'email',
            ],
        ]); ?>
<?=$uiHelper->endSingleRowBlock() ?>

<?=$uiHelper->endSingleRowBlock() ?>

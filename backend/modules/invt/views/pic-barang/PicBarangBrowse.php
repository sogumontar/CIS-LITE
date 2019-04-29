<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\ToolsColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;
$uiHelper = Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PicBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar PIC Barang';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang','url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="pic-barang-index">
<?php
    foreach ($dataProviders as $value) {
?>
<?=$uiHelper->renderContentSubHeader('Unit: '.$value['unit']);?>
<?=$uiHelper->renderLine();?>
     <div class="pull-right">
         <?=$uiHelper->renderButtonSet([
            'template' => ['tambah'],
            'buttons' => [
                'tambah' =>['url' => Url::to(['pic-barang/list-distribusi', 'unit_id'=>$value['unit_id']]), 'label' => 'Assign PIC', 'icon' => 'fa fa-user-plus'],
            ]  
         ]) ?>
     </div> 
<?php Pjax::begin()?>
    <?= GridView::widget([
        'dataProvider' => $value['dataProvider'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Barang',
                'format'=>'raw',
                'value'=>function($model){
                    return "<a href='".Url::toRoute(['/invt/pic-barang/pic-barang-view','id'=>$model->pic_barang_id])."'>".$model->pengeluaranBarang->barang->nama_barang."</a>";
                },
            ],
            [
                'label'=>'Kategori Barang',
                'value'=>'pengeluaranBarang.barang.kategori.nama',
            ],
            [
                'attribute'=>'pegawai_id',
                'value'=>'pegawai.nama',
            ],
            'tgl_assign',
            'keterangan:ntext',

            [
                'class' => 'common\components\ToolsColumn',
                'template' => '{edit} {del}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action === 'edit') {
                        return Url::to(['pic-barang-edit','id'=>$key]);
                    }elseif ($action === 'del') {
                        return Url::to(['pic-barang-del','id'=>$key]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>
<?php Pjax::end()?>
<?php
    }
?>
</div>

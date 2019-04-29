<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PeminjamanBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peminjaman Barang';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginSingleRowBlock(['id'=>'pinjam-barang-browse'])?>
<?php
    foreach ($dataProviders as $value) {
?>
<?= $uiHelper->renderContentSubHeader('Unit: '.$value['unit'])?>
<?= $uiHelper->renderLine()?>
<?php Pjax::begin()?>
    <?= GridView::widget([
        'dataProvider' => $value['dataProvider'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Peminjam',
                'format'=>'raw',
                'value'=>function ($model){
                        return "<a href='".Url::toRoute(['peminjaman-barang/pinjam-barang-view', 'id' => $model->peminjaman_barang_id])."'>".$model->getDetailNama($model->oleh)."</a>";
                }
            ],
            [
                'label' =>'Status Approval',
                'attribute'=>'status_approval',
                'filter'=> ArrayHelper::map($status,'status_approval','status'),
                'value'=>function($model){
                    if($model->status_approval==0){
                        return 'Belum Approve';
                    }elseif($model->status_approval==1)
                    {   
                        return "Approve";
                    }elseif($model->status_approval==2){
                        return "Tolak";
                    }
                }
            ],

            'tgl_pinjam',
            'tgl_kembali',
            [
                'label' =>'Status Kembali',
                'attribute'=>'status_kembali',
                'filter'=> ArrayHelper::map($statusKembali,'status_kembali','status'),
                'value'=>function($model){
                    if($model->status_kembali==0){
                        return 'Belum Kembali';
                    }elseif($model->status_kembali==1){   
                        return "Kembali";
                    }
                }
            ],
            [
                'label'=>'Tanggal Realisasi Kembali',
                'attribute'=>'tgl_realisasi_kembali',
            ],
            [
                'class' => 'common\components\ToolsColumn',
                'template' => '{detail}',
                'buttons' => [
                    'detail'=>function ($url,$model) {
                        return ToolsColumn::renderCustomButton($url, $model, 'Detail', 'fa fa-bars');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='detail'){
                        return Url::to(['peminjaman-barang/pinjam-barang-view', 'id'=>$model->peminjaman_barang_id]);
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
<?= $uiHelper->endSingleRowBlock()?>

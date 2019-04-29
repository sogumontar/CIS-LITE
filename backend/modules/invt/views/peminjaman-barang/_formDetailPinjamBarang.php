<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PeminjamanBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<?= $uiHelper->beginSingleRowBlock(['id' => 'pindah-barang',]) ?>
<?= $this->render('_detailPengajuan',['modelPengajuan'=>$modelPengajuan])?>
<?= $uiHelper->renderLine()?>

Silahkan pilih barang dan masukkan jumlah barang yang akan dipinjam.<br><br>
<?=$this->render('_searchBarang',['searchModel'=>$searchModel, '_jenis'=>$_jenis, '_kategori'=>$_kategori,]);?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-sm-4',
            'wrapper' => 'col-sm-5',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['height'=>'10'];
        },
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                // you may configure additional properties here
                'multiple'=>false,
                'header'=>'Cek',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->barang_id];
                },
            ],
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama_barang',
                'label'=>'Nama',
                'format'=>'raw',
                'value'=>function ($model){
                        return "<a href='".Url::toRoute(['/invt/barang/barang-view', 'barang_id' => $model->barang_id])."'>".$model->nama_barang."</a>";
                }
            ],
            [
                'label'=>'Jenis',
                'value'=>'jenisBarang.nama',
            ],
            [
                'label'=>'Kategori',
                'value'=>'kategori.nama',
            ],
            [
                'label'=>'Jumlah Gudang',
                'attribute'=>'jumlah_gudang',
                'contentOptions'=>['class'=>'col-xs-1']
            ],
            [
                'label'=>"Jumlah Pinjam",
                'filter'=>false,
                'format'=>'raw',
                'value'=>function($data){
                        return Html::textInput('jumlah_pinjam['.$data->barang_id.']',null,['style'=>"width: 4em",'type'=>'number','min'=>1,'max'=>$data->jumlah_gudang]);
                }
            ],
        ],
    ]); ?>
    <br>
    <?=$uiHelper->beginContentBlock(['id'=>'grid-system1', 'width'=>12]) ?>
        <div class="form-group">
            <div class='col-sm-offset-6 col-sm-4'></div>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>
    <?=$uiHelper->endContentBlock() ?>

    <?php ActiveForm::end(); ?>
<?=$uiHelper->endSingleRowBlock()?>

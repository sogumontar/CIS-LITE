<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PemindahanBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<?= $uiHelper->beginSingleRowBlock(['id' => 'pindah-barang',]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-sm-3',
            'wrapper' => 'col-sm-5',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>
<div style="display:none">
    <?= Html::textInput('tanggal_pindah',$_tanggalPindah);?>
</div>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel'=>$searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Nama Barang',
                'format'=>'raw',
                'value'=>'barang.nama_barang',
                'contentOptions'=>['class'=>'col-xs-3',],
            ],
            [
                'label'=>'Kode Inventori Awal',
                'attribute'=>'kode_inventori',
                'contentOptions'=>['class'=>'col-xs-3']
            ],
            [
                'label'=>'Lokasi Awal',
                'attribute'=>'lokasi.nama_lokasi',
                'contentOptions'=>['class'=>'col-xs-2']
            ],
            [
                'label'=>'Lokasi Tujuan',
                'filter'=>false,
                'format'=>'raw',
                'value'=>function($data)use($_lokasi){
                        return Html::dropDownList('lokasi['.$data->barang_id."|".$data->pengeluaran_barang_id.']',null,ArrayHelper::map($_lokasi, 'lokasi_id', 'nama_lokasi'),
                        [
                            'prompt' => 'Lokasi',
                        ]);
                }
            ],
            [
                'label'=>"Prefiks Kode Inventori",
                'filter'=>false,
                'format'=>'raw',
                'value'=>function($data){
                        return Html::textInput('prefiks_kode['.$data->barang_id."|".$data->pengeluaran_barang_id.']',null,['size'=>35,]);
                }
            ],
        ],
    ]); 
    ?>
    <br>
    <?=$uiHelper->beginContentBlock(['id'=>'grid-system1', 'width'=>12]) ?>
        <div class="form-group">
            <div class='col-sm-offset-6 col-sm-4'></div>
                <?= Html::submitButton('Pindahkan', ['class' => 'btn btn-success']) ?>
        </div>
    <?=$uiHelper->endContentBlock() ?>

    <?php ActiveForm::end(); ?>

<?=$uiHelper->endSingleRowBlock()?>

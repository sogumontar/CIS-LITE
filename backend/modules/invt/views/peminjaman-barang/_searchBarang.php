<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
$uiHelper = \Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PeminjamanBarangSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-search">
    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'get',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-md-2',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>
<?=$uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 4,
    ]) ?>
    <?= $form->field($searchModel, 'nama_barang', [
                'horizontalCssClasses' => ['wrapper' => 'col-sm-9',],
                'inputOptions' => ['placeHolder'=>'Barang',]
                ])->label('Barang');
    ?>
    <?=$uiHelper->endContentBlock()?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 4,
    ]) ?>
    <?= $form->field($searchModel, 'kategori_id', [
                'horizontalCssClasses' => ['wrapper' => 'col-sm-9',],
                ])->dropDownList(ArrayHelper::map($_kategori, 'kategori_id', 'nama'),
                ['prompt'=>"Kategori"])
            ->label('Kategori'); 
    ?>
    <?=$uiHelper->endContentBlock()?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 4,
    ]) ?>
    <?= $form->field($searchModel, 'jenis_barang_id', [
                'horizontalCssClasses' => ['wrapper' => 'col-sm-9',],
                ])->dropDownList(ArrayHelper::map($_jenis, 'jenis_barang_id', 'nama'),
                ['prompt'=>"Jenis"])
            ->label('Jenis'); 
    ?>
    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>

<?=$uiHelper->beginContentRow() ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
            <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Hapus', ['class' => 'btn btn-default']) ?>
    </div>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

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
            'label' => 'col-md-6',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>
<?=$uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 8,
    ]) ?>
    <?= $form->field($searchModel, 'nama_barang', [
                'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                'inputOptions' => ['placeHolder'=>'Barang',]
                ])->label('Barang');
    ?>
    <?=$uiHelper->endContentBlock()?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system2',
            'width' => 4,
    ]) ?>
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
    <?=$uiHelper->endContentBlock()?>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

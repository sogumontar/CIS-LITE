<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 $uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-md-4',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-13',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>


    <?= $form->field($model, 'nama',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                       'inputOptions' => ['placeHolder'=>'Kategori',]
                ])->textInput(['maxlength' => true]);
     ?>

    <?= $form->field($model, 'desc',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Deksripsi',]
                ])->textarea(['rows' => 6]);
     ?>

<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-md-offset-4 col-sm-1'></div>
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Unit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-form">

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-md-3',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-13',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

    <?= $form->field($model, 'nama',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-7',],
                       'inputOptions' => ['placeHolder'=>'Nama Unit',]
                ])->textInput(['maxlength' => true])
                ->label("Nama Unit");
    ?>

    <?= $form->field($model, 'desc',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-7',],
                    ])->textarea(['rows' => 6]) 
                ->label("Deskripsi Unit");
    ?>

<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-sm-offset-3 col-sm-1'></div>
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

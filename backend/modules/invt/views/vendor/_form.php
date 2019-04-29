<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 $uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-form">

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
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Vendor',]
                ])->textInput(['maxlength' => true]);
     ?>
    <?= $form->field($model, 'alamat',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],    
                       'inputOptions' => ['placeHolder'=>'Alamat',]
                ])->textarea(['rows' => 6]);
     ?>

    <?= $form->field($model, 'telp',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                       'inputOptions' => ['placeHolder'=>'Telepon',]
                ])->textInput(['maxlength' => true]);
     ?>
    <?= $form->field($model, 'email',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Email',]
                ])->textInput(['maxlength' => true]);
     ?>
    <?= $form->field($model, 'link',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Link/Website',]
                ])->textInput(['maxlength' => true]);
     ?>
    <?= $form->field($model, 'contact_person',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Contact Person',]
                ])->textInput(['maxlength' => true]);
     ?>
    <?= $form->field($model, 'telp_contact_person',[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                       'inputOptions' => ['placeHolder'=>'Telepon Contact Person',]
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

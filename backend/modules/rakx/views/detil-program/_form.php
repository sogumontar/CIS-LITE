<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInputAsset;
use common\widgets\Redactor;

MaskedInputAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\DetilProgram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detil-program-form">

    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
    ]) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'desc')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],])  
    ?>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div></div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs("
    $('#detilprogram-jumlah').inputmask('currency', {radixPoint:'.', prefix: 'Rp ', 'autoUnmask' : true, removeMaskOnSubmit: true});
    $('#detilprogram-jumlah').keypress(function(event){
        isNumber(event);
    });
    
    function isNumber(event){
        var charCode = event.which;
        // backspace & delete
        if (charCode == 46 || charCode == 8) {
            // nothing
        }else{
            // dot(titik) & space(spasi)
            if (charCode === 190 || charCode === 32) {
                event.preventDefault();
            }
            // other than number 0 - 9
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        }
        return true;
    }
");
?>

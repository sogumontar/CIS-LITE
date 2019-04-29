<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInputAsset;

//MaskedInputAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StrukturJabatanHasMataAnggaran */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="struktur-jabatan-has-mata-anggaran-form">

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

    <?= $form->field($model, 'struktur_jabatan_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]
        ])->dropDownList(
            ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),["prompt"=>"Jabatan"])->label('Jabatan')
    ?>

    <?= $form->field($model, 'tahun_anggaran_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-3',]
        ])->dropDownList(
            ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'),["prompt"=>"Tahun Anggaran"])->label('Tahun Anggaran')
    ?>

    <?= $form->field($model, 'mata_anggaran_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]
        ])->dropDownList(
            ArrayHelper::map($mata_anggaran, 'mata_anggaran_id', 'name'),["prompt"=>"Mata Anggaran"])->label('Mata Anggaran')
    ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div></div>

    <?php ActiveForm::end(); ?>

</div>

<?php

/*$this->registerJs("
    $('#strukturjabatanhasmataanggaran-subtotal').inputmask('currency', {radixPoint:'.', prefix: 'Rp ', 'autoUnmask' : true, removeMaskOnSubmit: true});

    $('#strukturjabatanhasmataanggaran-subtotal').keypress(function(event){
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
");*/
?>

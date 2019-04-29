<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\web\View;
use common\widgets\Redactor;
use backend\modules\hrdx\assets\HrdxAsset;


HrdxAsset::register($this);

$uiHelper = \Yii::$app->uiHelper;

?>

<?=$uiHelper->beginSingleRowBlock(['id'=> 'cuti-content'])?>
    <?=$uiHelper->beginContentRow() ?>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        //'options' => ['enctype' => 'multipart/form-data'],
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'wrapper' => 'col-sm-6',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>


    <?= $form->field($model, 'dari_tanggal')->widget(DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options'=>['class'=>'form-control','style'=>'width:250px;'],
    ]) ?>

    <?= $form->field($model, 'sampai_tanggal')->widget(DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'options'=>['class'=>'form-control','style'=>'width:250px;'],
    ]) ?>

    <?= $form->field($model, 'jumlah_hari')->textInput(['style'=>'width:100px']);?>

    <?= $form->field($model, 'alasan')->widget(Redactor::className());?>

    <div class="form-group field-pengalihan-tugas">
        <label class="control-label col-sm-3" for="pengalihan-tugas">Pengalihan Tugas</label>
        <div class="col-sm-6">
            <select name="pengalihan_tugas[]" class="js-example-basic-multiple" multiple="multiple" style="width:100%;">
                <?php
                foreach ($penerima_tugas as $key => $value) {
                    echo "<option value='".$value->pegawai_id."'>".$value->nama."</option>";
                }
                ?>
            </select>
        <div class="help-block help-block-error "></div>
        </div>

    </div>

    <div class="form-group ">
        <div class="col-sm-6 col-sm-offset-3">
            <?= Html::submitButton($model->isNewRecord ? 'Ajukan' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?=$uiHelper->endContentRow() ?>
<?=$uiHelper->endSingleRowBlock()?>


<?php 
  $this->registerJs(
    "$('.js-example-basic-multiple').select2();", 
    View::POS_END);
?>
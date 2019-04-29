<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12, 'header'=>'Unggah Laporan Penugasan', 'icon' => 'fa fa-upload']) ?>

	<?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'options' => ['enctype' => 'multipart/form-data'],
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3',
                    'wrapper' => 'col-sm-6',
                    'error' => '',
                    'hint' => '',
                ],
            ],
            //'enableAjaxValidation'=>true,
    ]) ?>

		<br>
		<div class="form-group field-materi-files">
            <label class="control-label col-sm-3" for="materi-files">Laporan Penugasan</label>
            <div class="col-sm-9">
              <div id="file_input">
                  <input type="file" class="form-control" name="files[]">
              </div>
            </div>
    	</div>
		
		<br>
	    <div class="form-group">
	        <div class="col-sm-offset-3 col-sm-6">
	            <?= Html::submitButton('Unggah', ['class' => 'btn btn-primary']) ?>
	        </div>
	    </div>

    <?php ActiveForm::end(); ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;

use common\helpers\LinkHelper;
use yii\helpers\Url;

$uiHelper = \Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\dimx\models\Arsip */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arsip-form">
<?php Pjax::begin();?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-4',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-8',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>

    <?= $form->field($model, 'judul_arsip',[
        'horizontalCssClasses'=>['wrapper'=>'col-sm-4'],
        'inputOptions'=>['placeHolder'=>'Judul Arsip']
    ])->textInput(['maxlength' => true])->label('Judul Arsip') ?>

    <?= $form->field($model, 'desc',[
        'horizontalCssClasses'=>['wrapper'=>'col-sm-6'],
        'inputOptions'=>['placeHolder'=>'Deskripsi Arsip']
    ])->textarea(['rows' => 6])->label('Deskripsi Arsip') ?>

<div class="form-group field-testform-name required">
    <label class="control-label col-sm-4" for="testform-name">Attachment(s)</label>
    <div class="col-sm-4">
        <div id="file_input">
            <input type="file" class="form-control" name="files[]">
        </div>
        <div>
           <a href="#" onclick="addMoreFiles()">Add More</a>
        </div>
    <div class="help-block help-block-error "></div>
</div>

<?php
    if(!$model->isNewRecord)
    {
        if(empty($arsipList)==false)
        {
?>
<div class="col-sm-12">
    <label class="control-label col-sm-4">File(s)</label>
    <div class="col-sm-4">
        <table class="table table-striped">
            <tbody>
                <?php
                    foreach ($arsipList as $key => $value) {
                ?>
                    <tr>
                        <td>
                            <?= LinkHelper::renderLink(['options'=>'target = _blank','label'=>"- ".$value->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($value->kode_file)])?>
                        </td>
                        <td width="15%">
                            <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-trash', 'tooltip' => "Hapus File",'url'=>Url::to(['vendor/file-vendor-del', 'file_vendor_id'=>$value->file_vendor_id, 'arsip_vendor_id'=>$model->arsip_vendor_id, 'vendor_id'=>$model->vendor_id])])?>
                        </td>
                    </tr>
                <?php    
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
    }
}
?>

<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

<!--javascript -->
<?php 
  $this->registerJs(
    "function addMoreFiles(){

           $('#file_input').append('<input class=form-control type=file name=files[] />')
       }
  ", 
    View::POS_END);
?>
    <?php ActiveForm::end(); ?>
<?php Pjax::end();?>
</div>

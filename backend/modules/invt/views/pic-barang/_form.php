<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\jui\DatePicker;

use common\helpers\LinkHelper;
use yii\helpers\Url;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PicBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pic-barang-form">

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

    <?= $form->field($model, 'pengeluaranBarang.barang.nama_barang',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                    ])->textInput(['readonly'=>true,'value'=>$modelPengeluaran->barang->nama_barang])
                    ->label('Barang'); 
    ?>
    <?= $form->field($model, 'pengeluaranBarang.barang.kategori_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                    ])->textInput(['readonly'=>true,'value'=>$modelPengeluaran->barang->kategori->nama])
                    ->label('Kategori Barang'); 
    ?>

    <?= $form->field($model, 'pengeluaranBarang.kode_inventori',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                    ])->textInput(['readonly'=>true,'value'=>$modelPengeluaran->kode_inventori])
                    ->label('Kode Inventori'); 
    ?>

    <?= $form->field($model, 'pengeluaranBarang.tgl_keluar',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-2',],
                    ])->textInput(['readonly'=>true,'value'=>$modelPengeluaran->tgl_keluar])
                    ->label('Tanggal Distribusi/Pindah'); 
    ?>
    <?= $form->field($model, 'pengeluaranBarang.lokasi_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                    ])->textInput(['readonly'=>true,'value'=>$modelPengeluaran->lokasi->nama_lokasi])
                    ->label('Lokasi/Ruang'); 
    ?>

    <?= $form->field($model, 'pegawai_id',[
        'horizontalCssClasses'=>['wrapper'=>'col-sm-5'],
    ])->dropDownList(ArrayHelper::map($modelPegawai, 'pegawai_id','nama'),['prompt'=>'PIC'])
    ->label('PIC') ?>


    <?= $form->field($model, 'tgl_assign',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">
                                 <span class="   glyphicon glyphicon-calendar"></span>
                                </span>{input}</div>',
             ])->widget(DatePicker::className(),
             [
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions'=>
                 [
                    'changeMonth'=>'true',
                    'changeYear'=>'true',
                    'yearRange'=>"-25:date('Y')",
                 ],
              'options'=>['size'=>27,'changeMonth'=>'true','class'=>'form-control']
    ])->label('Tanggal Assign') ?>

    <?= $form->field($model, 'keterangan',[
        'horizontalCssClasses'=>['wrapper'=>'col-sm-7']
    ])->textarea(['rows' => 6]) ?>

<?php
    if(!$model->isNewRecord)
    {
        if(empty($fileList)==false)
        {
?>
<div class="col-sm-12">
    <label class="control-label col-sm-4">File(s)</label>
    <div class="col-sm-4">
        <table class="table table-striped">
            <tbody>
                <?php
                    foreach ($fileList as $key => $value) {
                ?>
                    <tr>
                        <td>
                            <?= LinkHelper::renderLink(['options'=>'target = _blank','label'=>"- ".$value->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($value->kode_file)])?>
                        </td>
                        <td width="20%">
                            <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-trash', 'tooltip' => "Hapus File",'url'=>Url::to(['pic-barang/pic-file-del', 'pic_barang_file_id'=>$value->pic_barang_file_id, 'pic_barang_id'=>$model->pic_barang_id])])?>
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

<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
        <?= Html::submitButton($model->isNewRecord ? 'Assign' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
</div>

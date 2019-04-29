<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PengeluaranBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pengeluaran-barang-form">


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
    <?= $form->field($model, 'unit',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                    ])->textInput(['readonly'=>true,'value'=>$model->barang->unit->nama])
                    ->label('Unit'); 
    ?>
    <?= $form->field($model, 'total_jumlah',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-2',],
                    ])->textInput(['readonly'=>true,'value'=>$model->barang->jumlah])
                    ->label('Total Jumlah'); 
    ?>
    <?= $form->field($model, 'status_akhir',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                    ])->textInput(['readonly'=>true,'value'=>$model->status_akhir])
                    ->label('Status Akhir'); 
    ?>

    <?= $form->field($model, 'jumlah',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-2',],
                    ])->textInput(['readonly'=>true, 'value'=>$model->jumlah]); 
    ?>
    
    <?= $form->field($model, 'lokasi_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                    ])->dropDownList(ArrayHelper::map($_lokasi, 'lokasi_id', 'nama_lokasi' ), ["prompt"=>"Lokasi"])
                ->label("Lokasi Akhir"); 
    ?>

    <?= $form->field($model, 'kode_inventori',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                    ])->textInput(['maxlength' => true, 'value'=>substr($model->kode_inventori, 0,-2)]) 
                    ->label('Prefix Kode Inventori')
    ?>


    <?= $form->field($model, 'tgl_keluar',[
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
    ])->label('Tanggal Distribusi/Pindah') ?>
<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
            <?= Html::submitButton('Edit', ['class' => 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>
    <?php ActiveForm::end(); ?>

</div>

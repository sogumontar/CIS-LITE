<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Barang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-form">

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

    <?= $form->field($model, 'kategori_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                    ])->dropDownList(ArrayHelper::map($model_kategori_barang, 'kategori_id', 'nama' ), ["prompt"=>"Kategori"])
                ->label("Kategori"); 
    ?>
    <?= $form->field($model, 'nama_barang' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-7',],
                       'inputOptions' => ['placeHolder'=>'Nama Barang',]
                ])->textInput(['maxlength' => true])
                ->label('Nama Barang');
     ?>

    <?= $form->field($model, 'jenis_barang_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                    ])->dropDownList(ArrayHelper::map($model_jenis_barang, 'jenis_barang_id', 'nama' ), ["prompt"=>"Jenis Barang"])
                ->label("Jenis Barang"); 
    ?>

    <?= $form->field($model, 'jumlah' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                       'inputOptions' => ['placeHolder'=>'Jumlah',]
                ])->textInput(['maxlength' => true])
                ->label('Total Jumlah');
     ?>

    <?= $form->field($model, 'satuan_id',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                    ])->dropDownList(ArrayHelper::map($model_satuan, 'satuan_id', 'nama' ), ["prompt"=>"Satuan"])
                ->label("Satuan"); 
    ?>
    <div class="form-group">
        <label class="control-label col-md-4">Data Perangkat </label>
        <div class="input-group col-md-3">
            <button type="button" class="btn btn-primary btn-xs col-md-offset-1" data-toggle="collapse" data-target="#perangkat">
                <i class="fa fa-arrow-circle-down"></i> Detail Perangkat
            </button>
            <?=$uiHelper->renderTooltip("Silahkan isi detail perangkat, apabila jenis barang yang ditambahkan adalah perangkat") ?>
        </div>
    </div>

    <div id="perangkat" class="collapse">
            <?= $form->field($model, 'serial_number' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-5',],
                       'inputOptions' => ['placeHolder'=>'Serial Number',]
                ])->textInput(['maxlength' => true])
                ->label('Serial Number');
            ?>
            <?= $form->field($model, 'vendor_id' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                ])->dropDownList(ArrayHelper::map($model_vendor, 'vendor_id', 'nama' ), ["prompt"=>"Vendor"])
                ->label('Vendor');
            ?>
             <?= $form->field($model, 'brand_id' ,[
                                   'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                            ])->dropDownList(ArrayHelper::map($model_brand, 'brand_id', 'nama' ), ["prompt"=>"Brand"])
                            ->label('Brand');
            ?>
    </div>

    <?= $form->field($model, 'supplier' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                       'inputOptions' => ['placeHolder'=>'Supplier',]
                ])->textInput(['maxlength' => true])
                ->label('Supplier Barang');
     ?>
    <?= $form->field($model, 'harga_per_barang' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                       'inputOptions' => ['placeHolder'=>'Harga',]
                ])->textInput(['maxlength' => true])
                ->label(true);
     ?>

    <?= $form->field($model, 'total_harga' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
                       'inputOptions' => ['placeHolder'=>'Total Harga',]
                ])->textInput(['maxlength' => true])
                ->label('Total Harga Barang');
     ?>
    <?= $form->field($model, 'tanggal_masuk',[
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
    ])->label('Tanggal Pemasukan') ?>

    <?= $form->field($model, 'kapasitas' ,[
                       'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
                       'inputOptions' => ['placeHolder'=>'Kapasitas',]
                ])->textInput(['maxlength' => true])
                ->label('Kapasitas Barang');
     ?>

    <?= $form->field($model, 'desc',[
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-7',],
                    ])->textarea(['rows' => 5]) 
                    ->label('Deskripsi Barang');
    ?>
    <?= $form->field($model, 'nama_file',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
        ])->fileInput()
        ->label('Gambar Barang');
    ?>
<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-md-offset-4 col-sm-1'></div>
            <?= Html::submitButton($model->isNewRecord ? 'Tambah Barang' : 'Edit Barang', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

<?php ActiveForm::end(); ?>
</div>

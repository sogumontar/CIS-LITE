<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

$uiHelper = Yii::$app->uiHelper;
$this->title = 'Lokasi Pemindahan Barang';
$this->params['breadcrumbs'][] = ['label' => 'Histori Pemindahan Barang', 'url' => ['pindah-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = "Pilih";
$this->params['header'] = $this->title;
?>

<?=$uiHelper->beginSingleRowBlock(['id'=> 'lokasi-pindah-barang'])?>
<div class="petunjuk">
	<p>Silahkan pilih lokasi awal dan lokasi tujuan barang yang akan dipindahkan.</p>
</div>
<?=$uiHelper->renderLine();?>
    <?php $form = ActiveForm::begin(
        [  
            'layout'=> 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses'=> [
                    'label'=>'col-md-4',
                    'offset'=>'col-md-offset-10',
                    'wrapper'=>'col-md-8',
                    'error'=>'',
                    'hint'=>'',
                ],
            ],
        ]); ?>

        <?= $form->field($modelForm, 'tgl_pindah',[
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
        ])->label('Tanggal Pindah') ?>
        
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system1',
            'width'=>6,
        ])?>
            <?= $form->field($modelForm, 'lokasi_awal')->inline(false)->checkboxList($lokasiAwalList) ?>
        <?=$uiHelper->endContentBlock()?>

        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>6,
        ])?>
            <?= $form->field($modelForm, 'lokasi_akhir')->inline(false)->checkboxList($lokasiTujuanList) ?>
        <?=$uiHelper->endContentBlock()?>

    <?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system1',
                'width'=>12
            ]) ?>
        <div class="form-group">
            <div class='col-sm-offset-4 col-sm-1'></div>
              <?= Html::submitButton('Pilih', ['class' =>'btn btn-success']) ?>
        </div>
        <?=$uiHelper->endContentBlock() ?>
    <?=$uiHelper->endContentRow() ?>
    <?php ActiveForm::end(); ?>
<?=$uiHelper->endSingleRowBlock()?>
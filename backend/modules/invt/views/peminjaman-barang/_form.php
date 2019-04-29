<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
 $uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pinjam-barang-form">

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

    <?= $form->field($model, 'unit_id',[
        'horizontalCssClasses'=>['wrapper'=>'col-sm-3'],
    ])->dropDownList(
        ArrayHelper::map($unit, 'unit_id', 'nama'),['prompt'=>'Unit']
        )->label('Unit Inventori')
    ?>

    <?= $form->field($model, 'tgl_pinjam',[
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
    ])->label('Tanggal Pinjam')
    ?>

    <?= $form->field($model, 'tgl_kembali',[
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
    ])->label('Tanggal Kembali')
    ?>
    <?= $form->field($model, 'deskripsi',[
                'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
            ]
        )->textarea(['rows' => 6])->label('Alasan/Keperluan');
    ?>
<?=$uiHelper->beginContentRow() ?>
        <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
        ]) ?>
    <div class="form-group">
        <div class='col-md-offset-4 col-sm-1'></div>
            <?= Html::submitButton($model->isNewRecord ? 'Ajukan' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
        <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

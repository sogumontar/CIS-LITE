<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\search\StrukturJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="struktur-jabatan-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'get',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-md-2',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>

    <?=$uiHelper->beginContentRow() ?>
        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 6,
        ]) ?>
                <?= $form->field($searchModel, 'instansi_id',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($instansi, 'instansi_id', 'name'),
                            ['prompt'=>'Instansi'])
                        ->label ('Instansi');
                ?>  
                <?= $form->field($searchModel, 'jabatan', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                           'inputOptions' => ['placeHolder'=>'Jabatan',]
                    ])->label('Jabatan');
                ?>
                <?= $form->field($searchModel, 'parent',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($parent, 'struktur_jabatan_id', 'jabatan'),
                            ['prompt'=>'Parent'])
                        ->label ('Parent');
                ?>

        <?=$uiHelper->endContentBlock()?>


        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 6,
        ]) ?>

                <?= $form->field($searchModel, 'inisial', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                           'inputOptions' => ['placeHolder'=>'Inisial',]
                    ])->label('Inisial');
                ?>
                <!--<?= $form->field($searchModel, 'is_multi_tenant', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                           'inputOptions' => ['placeHolder'=>'Jabatan',]
                    ])->label('Jabatan');
                ?>-->
                <?= $form->field($searchModel, 'unit_id',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($unit, 'unit_id', 'name'),
                            ['prompt'=>'Unit'])
                        ->label ('Unit');
                ?>

        <?=$uiHelper->endContentBlock()?>
<?=$uiHelper->endContentRow() ?>

<?=$uiHelper->beginContentRow() ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
            <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Hapus', ['class' => 'btn btn-default']) ?>
    </div>
<?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\search\UnitSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-search">

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
                <?= $form->field($searchModel, 'name', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                           'inputOptions' => ['placeHolder'=>'Name',]
                    ])->label('Name');
                ?>
                
                <?= $form->field($searchModel, 'inisial', [
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        'inputOptions' => ['placeHolder'=>'Inisial',]
                    ])->label('Inisial');
                ?>

        <?=$uiHelper->endContentBlock()?>


        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 6,
        ]) ?>

                    <?= $form->field($searchModel, 'kepala',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-6',],
                        ])->dropDownList(ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),
                            ['prompt'=>'Kepala'])
                        ->label ('Kepala');
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

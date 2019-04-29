<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
$uiHelper = \Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\dimx\models\search\DimSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dim-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'get',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-md-3',
            'offset' => 'col-md-offset-12',
            'wrapper' => 'col-md-12',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>

<?=$uiHelper->beginContentRow() ?>
        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1',
            'width' => 6,
        ]) ?>
                
                <?= $form->field($searchModel, 'dim_nama', [
                        'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        'inputOptions' => ['placeHolder'=>'Nama Mahasiswa',]
                    ])->label('Nama');
                ?>
                <?= $form->field($searchModel, 'dim_asrama',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($asrama, 'asrama_id', 'name'),
                            ['prompt'=>'All']) 
                        ->label ('Asrama');
                    ?>
        <?=$uiHelper->endContentBlock()?>


        <?= $uiHelper->beginContentBlock(['id' => 'grid-system2',
            'width' => 6,
        ]) ?>  
                    <?= $form->field($searchModel, 'dim_prodi',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($prodi, 'ref_kbk_id', 'kbk_ind', 'jenjangId.nama'),
                            ['prompt'=>'All']) 
                        ->label ('Prodi');
                    ?>
                    <?= $form->field($searchModel, 'dim_dosen',[
                            'horizontalCssClasses' => ['wrapper' => 'col-sm-8',],
                        ])->dropDownList(ArrayHelper::map($dosen_wali, 'dosen_wali_id', 'dosenWali.nama'),
                            ['prompt'=>'All']) 
                        ->label ('Dosen Wali');
                    ?>
        <?=$uiHelper->endContentBlock()?>
<?=$uiHelper->endContentRow() ?>

<?=$uiHelper->beginContentRow() ?>
    <div class="form-group">
        <div class='col-sm-offset-4 col-sm-1'></div>
            <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Hapus', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
<?=$uiHelper->endContentRow() ?>
    <?php ActiveForm::end(); ?>

</div>

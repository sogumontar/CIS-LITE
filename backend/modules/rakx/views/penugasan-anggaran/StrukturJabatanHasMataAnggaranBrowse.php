
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Browse Mata Anggaran';
$this->params['header'] = 'Browse Mata Anggaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$ui = \Yii::$app->uiHelper;
?>

<div class="penugasan-pengajaran-form">

<?=$ui->renderContentSubHeader("Browse Mata Anggaran") ?>
<?=$ui->renderLine() ?>

    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3',
                    'wrapper' => 'col-sm-6',
                    'error' => '',
                    'hint' => '',
                ],
            ],
    ]) ?>

    <?= $form->field($model, 'tahun_anggaran_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-6',]
        ])->dropDownList(
            ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'), ['options' => [$tahun_anggaran[0]->tahun_anggaran_id => ['Selected'=>'selected']]])
    ?>

    <?= $form->field($model, 'struktur_jabatan_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-6',]
        ])->dropDownList(
            ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),["prompt"=>"Struktur Jabatan"])
    ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-3">
            <?= Html::submitButton('Browse', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\admd\models\Dis */

$this->title = 'Import Excel';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="asrama-create">

    <div class="callout callout-info">
      <?php
        echo "<b>Peringatan</b><br/>";
        echo '1. Harap memastikan bahwa NIM yang akan di-entry sesuai dengan NIM pada database CIS<br/>';
        echo '2. Harap memastikan bahwa kamar yang akan di-entry sesuai dengan yang ada di asrama<br/>';
        echo '3. Harap menggunakan template excel yang telah disediakan<br/>';
        echo '4. Meng-import data akan menghapus(menimpa) data yang ada sebelumnya<br/>';
      ?>
    </div>

    <?php $form = ActiveForm::begin([
          // 'layout' => 'horizontal',
          'options' => ['enctype' => 'multipart/form-data'],
          // 'fieldConfig' => [
          //     'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
          //     'horizontalCssClasses' => [
          //         'label' => 'col-sm-2',
          //         'wrapper' => 'col-sm-9',
          //         'error' => '',
          //         'hint' => '',
          //     ],
          // ],
    ]) ?>

    <?= $form->field($modelImport,'fileImport')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

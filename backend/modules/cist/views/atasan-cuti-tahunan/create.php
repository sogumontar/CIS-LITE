<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\AtasanCutiTahunan */

$this->title = 'Create Atasan Cuti Tahunan';
$this->params['breadcrumbs'][] = ['label' => 'Atasan Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-cuti-tahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

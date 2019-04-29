<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\GolonganKuotaCuti */

$this->title = 'Create Golongan Kuota Cuti';
$this->params['breadcrumbs'][] = ['label' => 'Golongan Kuota Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golongan-kuota-cuti-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

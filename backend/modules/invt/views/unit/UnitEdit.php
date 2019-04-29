<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Unit */

$this->title = 'Edit Unit: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['unit-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['unit-view', 'id' => $model->nama]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header']=$this->title;
?>
<div class="unit-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

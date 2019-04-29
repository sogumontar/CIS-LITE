<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Satuan */

$this->title = 'Edit Satuan: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['satuan-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['satuan-view', 'id' => $model->satuan_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header'] = $this->title;
?>
<div class="satuan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

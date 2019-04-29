<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Vendor */

$this->title = 'Edit Vendor: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['vendor-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['vendor-view', 'id' => $model->vendor_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params ['header'] = $this->title;
?>
<div class="vendor-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

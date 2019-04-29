<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\dimx\models\Arsip */

$this->title = 'Edit Arsip: '.$model->judul_arsip;
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['vendor-browse']];
$this->params['breadcrumbs'][] = ['label' =>$modelVendor->nama, 'url' => ['vendor-view', 'id' => $modelVendor->vendor_id]];
$this->params['header']= $this->title;
?>
<div class="arsip-update">
    <?= $this->render('_formArsip', [
        'model' => $model,
        'modelVendor'=>$modelVendor,
        'arsipList'=>$arsipList,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\dimx\models\Arsip */

$this->title = 'Tambah Arsip';
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['vendor-browse']];
$this->params['breadcrumbs'][] = ['label' =>$modelVendor->nama, 'url' => ['vendor-view', 'id' => $modelVendor->vendor_id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']= $this->title;
?>
<div class="arsip-create">

    <?= $this->render('_formArsip', [
        'model' => $model,
    ]) ?>

</div>

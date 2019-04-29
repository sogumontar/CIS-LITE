<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */

$this->title = 'Edit Brand: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Brand', 'url' => ['brand-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['brand-view', 'id' => $model->brand_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header'] = $this->title;
?>
<div class="brand-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

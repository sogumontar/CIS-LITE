<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Kategori */

$this->title = 'Edit Kategori: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Kategori', 'url' => ['kategori-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['kategori-view', 'id' => $model->kategori_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header'] = $this->title;
?>
<div class="kategori-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Lokasi */

$this->title = 'Tambah Lokasi';
$this->params['breadcrumbs'][] = ['label' => 'Lokasi', 'url' => ['lokasi-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']= $this->title;
?>
<div class="lokasi-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

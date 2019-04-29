<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\JenisBarang */

$this->title = 'Tambah Jenis Barang';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Barang', 'url' => ['jenis-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="jenis-barang-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

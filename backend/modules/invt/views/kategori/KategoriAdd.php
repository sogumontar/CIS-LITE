<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Kategori */

$this->title = 'Tambah Kategori';
$this->params['breadcrumbs'][] = ['label' => 'Kategori', 'url' => ['kategori-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="kategori-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPenggunaanRuangan */

$this->title = 'Request Izin Penggunaan Ruangan';
$this->params['breadcrumbs'][] = ['label' => 'Izin Penggunaan Ruangan', 'url' => ['index-mahasiswa']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-ruangan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formByMahasiswa', [
        'model' => $model,
    ]) ?>

</div>

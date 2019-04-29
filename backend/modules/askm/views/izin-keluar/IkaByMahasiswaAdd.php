<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinKeluar */

$this->title = 'Request Izin Keluar';
$this->params['breadcrumbs'][] = ['label' => 'Izin Keluar', 'url' => ['ika-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-keluar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

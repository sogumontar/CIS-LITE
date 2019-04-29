<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */

$this->title = 'Request Izin Tambahan Jam Kolaboratif';
$this->params['breadcrumbs'][] = ['label' => 'Izin Tambahan Jam Kolaboratif', 'url' => ['izin-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-kolaboratif-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formByMahasiswa', [
        'model' => $model,
    ]) ?>

</div>

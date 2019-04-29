<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StrukturJabatanHasMataAnggaran */

$this->title = 'Create Penugasan Anggaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="struktur-jabatan-has-mata-anggaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'mata_anggaran' => $mata_anggaran,
        'tahun_anggaran' => $tahun_anggaran,
    ]) ?>

</div>

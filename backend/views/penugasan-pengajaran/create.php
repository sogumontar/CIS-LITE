<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */

$this->title = 'Create Penugasan Pengajaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Pengajarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-pengajaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

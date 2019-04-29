<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Kamar */

$this->title = 'Edit Kamar: ' . ' ' . $model->nomor_kamar;
$this->params['breadcrumbs'][] = ['label' => 'Kamar', 'url' => ['view', 'id' => $_GET['id']]];
$this->params['breadcrumbs'][] = ['label' => $model->nomor_kamar, 'url' => ['view', 'id' => $model->kamar_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kamar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formEditKamar', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriIzin */

$this->title = 'Edit Kategori Izin: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Izin', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->kategori_izin_id]];
$this->params['breadcrumbs'][] = 'Edit';
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-izin-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

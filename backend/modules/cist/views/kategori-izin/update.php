<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriIzin */

$this->title = 'Update Kategori Izin: ' . ' ' . $model->kategori_izin_id;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Izins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->kategori_izin_id]];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-izin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

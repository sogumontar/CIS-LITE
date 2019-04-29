<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriIzin */

$this->title = 'Tambah Kategori Izin';
$this->params['breadcrumbs'][] = ['label' => 'Kategori Izin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-izin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

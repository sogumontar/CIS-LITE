<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriCutiNontahunan */

$this->title = 'Edit Kategori Cuti Nontahunan: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name    , 'url' => ['view', 'id' => $model->kategori_cuti_nontahunan_id]];
$this->params['breadcrumbs'][] = 'Edit';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-cuti-nontahunan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'satuan' => $satuan
    ]) ?>

</div>

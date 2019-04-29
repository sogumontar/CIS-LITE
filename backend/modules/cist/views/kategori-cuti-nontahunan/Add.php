<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriCutiNontahunan */

$this->title = 'Tambah Kategori Cuti Nontahunan';
$this->params['breadcrumbs'][] = ['label' => 'Kategori Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-cuti-nontahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'satuan' => $satuan
    ]) ?>

</div>

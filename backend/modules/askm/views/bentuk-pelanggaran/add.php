<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\BentukPelanggaran */

$this->title = 'Tambah Bentuk Pelanggaran';
$this->params['breadcrumbs'][] = ['label' => 'Bentuk Pelanggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="bentuk-pelanggaran-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

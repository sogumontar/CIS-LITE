<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\TingkatPelanggaran */

$this->title = 'Tambah Tingkat Pelanggaran';
$this->params['breadcrumbs'][] = ['label' => 'Tingkat Pelanggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="tingkat-pelanggaran-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

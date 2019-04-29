<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pembinaan */

$this->title = 'Tambah Bentuk Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Bentuk Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pembinaan-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

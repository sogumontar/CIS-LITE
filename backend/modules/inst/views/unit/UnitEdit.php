<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */

$this->title = 'Update Unit: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Management Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['unit-view', 'id' => $model->unit_id]];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="unit-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'instansi' => $instansi,
    ]) ?>

</div>

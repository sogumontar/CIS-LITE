<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */

$this->title = 'Create Unit';
$this->params['breadcrumbs'][] = ['label' => 'Management Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'instansi' => $instansi,
    ]) ?>

</div>

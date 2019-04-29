<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ProgramHasWaktu */

$this->title = 'Create Program Has Waktu';
$this->params['breadcrumbs'][] = ['label' => 'Program Has Waktus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-has-waktu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

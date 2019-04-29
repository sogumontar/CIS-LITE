<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ReviewProgram */

$this->title = 'Update Review Program: ' . ' ' . $model->review_program_id;
$this->params['breadcrumbs'][] = ['label' => 'Review Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->review_program_id, 'url' => ['view', 'id' => $model->review_program_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="review-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

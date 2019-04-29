<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ReviewProgram */

$this->title = 'Create Review Program';
$this->params['breadcrumbs'][] = ['label' => 'Review Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

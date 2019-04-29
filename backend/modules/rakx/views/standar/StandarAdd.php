<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Standar */

$this->title = 'Create Standar';
$this->params['breadcrumbs'][] = ['label' => 'Standar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="standar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

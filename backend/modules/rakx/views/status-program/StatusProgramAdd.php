<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StatusProgram */

$this->title = 'Create Status Program';
$this->params['breadcrumbs'][] = ['label' => 'Status Program', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pedoman */

$this->title = 'Edit Pedoman';
$this->params['breadcrumbs'][] = ['label' => 'Pedoman', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->judul, 'url' => ['view', 'id' => $model->pedoman_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="pedoman-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

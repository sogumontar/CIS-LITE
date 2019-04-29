<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimKamar */

$this->title = 'Update Dim Kamar: ' . ' ' . $model->id_dim_kamar;
$this->params['breadcrumbs'][] = ['label' => 'Dim Kamars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_dim_kamar, 'url' => ['view', 'id' => $model->id_dim_kamar]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dim-kamar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

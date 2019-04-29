<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Management Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="unit-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <p>
        <?= Html::a('Update', ['unit-edit', 'id' => $model->unit_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['unit-del', 'id' => $model->unit_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'instansi_id',
                'label' => 'Instansi',
                'value' => $model->instansi['name']
            ],
            'name',
            'inisial',
            'desc:ntext',
            [
                'attribute' => 'kepala',
                'value' => $model->kepala0['jabatan']
            ],
        ],
    ]) ?>

</div>

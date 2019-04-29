<?php

/* @var $this yii\web\View */
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;
use common\helpers\LinkHelper;
use common\components\SwitchButton;
use yii\helpers\Url;

$uiHelper = \Yii::$app->uiHelper;
?>

<div id="app-container">

    <p>
        <?= Html::a('Update', ['edit', 'id' => $model->staf_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['del', 'id' => $model->staf_id], [
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
        	'prodi_id',
        	'aktif_start',
        	'aktif_end',
        ],
    ]) ?>
</div>
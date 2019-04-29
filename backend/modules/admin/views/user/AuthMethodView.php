<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\AuthenticationMethod */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authentication Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authentication-method-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Edit', ['edit', 'id' => $model->authentication_method_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['del', 'id' => $model->authentication_method_id], [
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
            'authentication_method_id',
            'name',
            'server_address',
            'authentication_string',
            'desc',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>

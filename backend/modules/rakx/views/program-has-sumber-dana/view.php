<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ProgramHasSumberDana */

$this->title = $model->program_has_sumber_dana_id;
$this->params['breadcrumbs'][] = ['label' => 'Program Has Sumber Danas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-has-sumber-dana-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->program_has_sumber_dana_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->program_has_sumber_dana_id], [
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
            'program_has_sumber_dana_id',
            'program_id',
            'sumber_dana_id',
            'jumlah',
            'desc:ntext',
            'deleted',
            'deleted_at',
            'deleted_by',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>

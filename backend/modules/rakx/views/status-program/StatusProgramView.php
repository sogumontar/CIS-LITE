<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StatusProgram */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Status Program', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="status-program-view">

    <p>
        <?= Html::a('Update', ['status-program-edit', 'id' => $model->status_program_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'desc:ntext',
        ],
    ]) ?>

</div>

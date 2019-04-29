<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Satuan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="satuan-view">

    <p>
        <?= Html::a('Update', ['satuan-edit', 'id' => $model->satuan_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                    'attribute' => 'desc',
                    'format' => 'html',
                ],
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\SumberDana */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sumber Dana', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="sumber-dana-view">

    <p>
        <?= Html::a('Update', ['sumber-dana-edit', 'id' => $model->sumber_dana_id], ['class' => 'btn btn-primary']) ?>
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

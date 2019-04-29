<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['satuan-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="satuan-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nama',
            'desc:ntext',
        ],
    ]) ?>

</div>

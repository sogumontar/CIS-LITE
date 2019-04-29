<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Lokasi */

$this->title = $model->nama_lokasi;
$this->params['breadcrumbs'][] = ['label' => 'Lokasi', 'url' => ['lokasi-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
<div class="lokasi-view">
    <?= DetailView::widget([
        'model' => $model,
        'options'=>[
            'class'=>'table table-condensed detail-view',
        ],
        'attributes' => [
            [
                'label'=>'Parent ID',
                'value'=>$model->detail==null?0:$model->detail->nama_lokasi,
            ],
            'nama_lokasi',
            'desc',
        ],
    ]) ?>

</div>

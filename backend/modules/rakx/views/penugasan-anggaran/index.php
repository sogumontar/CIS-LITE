<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\StrukturJabatanHasMataAnggaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penugasan Anggaran';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Penugasan Anggaran';
?>
<div class="struktur-jabatan-has-mata-anggaran-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'tahun_anggaran_name',
                'value' => 'tahunAnggaran.tahun',
                'label' => 'Tahun Anggaran',
                'filter' => ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            [
                'attribute' => 'struktur_jabatan_jabatan',
                'value' => 'strukturJabatan.jabatan',
                'label' => 'Jabatan',
                'filter' => ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            [
                'attribute' => 'mata_anggaran_name',
                'value' => function($model){ return $model->mataAnggaran->kode_anggaran.' '.$model->mataAnggaran->name;},
                'label' => 'Mata Anggaran',
                'filter' => ArrayHelper::map($mata_anggaran, 'mata_anggaran_id', function($model){ return $model->kode_anggaran.' '.$model->name;}),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'footer' => '<div style="text-align:right;font-weight:bold;">Total</div>',
            ],
            [
                'attribute' => 'subtotal',
                'value' => function($model){
                    return "Rp".number_format($model->subtotal,2,',','.');
                },
                'filter' => '',
                'footer' => StrukturJabatanHasMataAnggaran::getTotal($dataProvider->models, 'subtotal'),
            ],
        ],
    ]); 
    Pjax::end()?>

</div>

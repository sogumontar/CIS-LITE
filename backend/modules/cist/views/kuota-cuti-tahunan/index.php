<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\KuotaCutiTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kuota Cuti Tahunan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kuota-cuti-tahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class='pull-left'>
        <h2>
        <?php
            foreach($query as $data){
                echo 'Generate Terakhir: '.date("d-M-Y H:i:s", strtotime($data['waktu_generate_terakhir']));
                break;
            }
        ?>
        <br />
        <?php
            foreach($jumlah_libur as $data){
                echo 'Jumlah Libur: '.$data->jumlah;
                break;
            }
        ?>
    </h2>
    </div>
    <div class='pull-right'>
        <?= Html::a('Golongan Kuota Cuti', ['/cist/golongan-kuota-cuti/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Generate Kuota Cuti Tahunan', ['add'], ['class' => 'btn btn-success']) ?>
    </div>

    <br><br><br><br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:12px;'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

             //'pegawai_id',
            [
                'attribute' => 'pegawai_nama',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::to(['/hrdx/pegawai/view','id' => $model->pegawai_id])."'>".$model->pegawai->nama."</a>";
                },
                'label'     => 'Nama Pegawai',
            ],
            [
                'attribute' => 'pegawai_ikatan',
                'value'=>function($model){
                    return isset($model->pegawai->statusIkatanKerjaPegawai) && !empty($model->pegawai->statusIkatanKerjaPegawai)?$model->pegawai->statusIkatanKerjaPegawai->nama:'-';
                },
                'label'     => 'Ikatan kerja',
            ],
            [
                'attribute' => 'pegawai_masuk',
                'value'=>function($model){
                    return date('d M Y', strtotime($model->pegawai->tanggal_masuk));
                },
                'label'     => 'Mulai Kerja',
            ],
            [
                'attribute' => 'kuota',
                'value' => 'kuota',
                'label' => 'Kuota Cuti',
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{edit}',
                'header' => 'Action',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'edit') {
                        return Url::toRoute(['edit', 'id' => $key]);
                    }
                }

            ],
        ],
    ]); ?>

</div>

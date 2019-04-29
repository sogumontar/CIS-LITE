<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use dosamigos\datetimepicker\DateTimePicker;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\PoinPelanggaran;
use backend\modules\askm\models\Pembinaan;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPenilaian */

$this->title = $model->dim['nama'];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-penilaian-view">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-print"></i> Print Nilai', ['dim-penilaian/print-nilai', 'id' => $model->penilaian_id], ['target' => '_blank'], ['class' => 'btn btn-default btn-flat btn-set btn-md']) ?>
        </p>
    </div>

    <h1>Nilai Perilaku: <?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'akumulasi_skor',
            'nilai_huruf',
            [
                'label' => 'Bentuk pembinaan',
                'value' => $model->pembinaan,
            ]
        ],
    ]) ?>

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-thumbs-down"></i> Tambah Pelanggaran', ['dim-pelanggaran/add', 'id' => $model->penilaian_id], ['class' => 'btn btn-default btn-flat btn-set btn-md']) ?>
            <?php echo Html::a('<i class="fa fa-thumbs-up"></i> Tambah Perbuatan Baik', ['poin-kebaikan/add', 'penilaian_id' => $model->penilaian_id], ['class' => 'btn btn-default btn-flat btn-set btn-md']); ?>
        </p>
    </div>

    <h2>Daftar Pelanggaran & Perbuatan Baik</h2>
    <?= $uiHelper->renderLine(); ?>

    <?= $uiHelper->beginTab([
        'icon' => 'fa fa-shield',
        'tabs' => [
            ['id' => 'tab_1', 'label' => 'Pelanggaran', 'isActive' => true],
            ['id' => 'tab_2', 'label' => 'Perbuatan Baik', 'isActive' => false],
        ]
    ]) ?>

    <?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive' => true]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'poin_id',
                'label' => 'Pelanggaran',
                'format' => 'raw',
                'filter'=>ArrayHelper::map(PoinPelanggaran::find()->andWhere('deleted!=1')->asArray()->all(), 'poin_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'poin.name',
            ],
            [
                'attribute' => 'pelanggaran_poin',
                'label'=>'Poin',
                'headerOptions' => ['style' => 'width:80px'],
                'format' => 'raw',
                'value'=> 'poin.poin',
            ],
            [
                'attribute' => 'status_pelanggaran',
                'label'=>'Status',
                'format' => 'raw',
                'value'=> function($model){
                    if ($model->status_pelanggaran == 0) {
                        return 'Tidak diputihkan';
                    } else{
                        return 'Sudah diputihkan';
                    }
                },
            ],
            // [
            //     'attribute'=>'pembinaan_id',
            //     'label' => 'Pembinaan',
            //     'format' => 'raw',
            //     'filter'=>ArrayHelper::map(Pembinaan::find()->andWhere('deleted!=1')->asArray()->all(), 'pembinaan_id', 'name'),
            //     'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            //     'value' => 'pembinaan.name',
            // ],
            [
                'attribute'=>'tanggal',
                'label' => 'Tanggal',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->tanggal==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y', strtotime($model->tanggal));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'tanggal',
                    'template'=>'{input}{reset}{button}',
                        'clientOptions' => [
                            'startView' => 2,
                            'minView' => 2,
                            'maxView' => 2,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                ])
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                    },
                    'pemutihan' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Tebus Dosa', 'fa fa-thumbs-up');
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['dim-pelanggaran/view', 'id' => $key, 'penilaian_id' => $model->penilaian_id]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['dim-pelanggaran/edit', 'id' => $key, 'penilaian_id' => $model->penilaian_id]);
                    }else if ($action === 'pemutihan') {
                        return Url::toRoute(['poin-kebaikan/add', 'id' => $key]);
                    }
                }
            ],
        ],
    ]); ?>
    <?= $uiHelper->endTabContent() ?>

    <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive' => false]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'desc:ntext',

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['poin-kebaikan/view', 'id' => $key, 'penilaian_id' => $model->penilaian_id]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['poin-kebaikan/edit', 'id' => $key, 'penilaian_id' => $model->penilaian_id]);
                    }
                }
            ],
        ],
    ]); ?>

    <?= $uiHelper->endTabContent() ?>

</div>

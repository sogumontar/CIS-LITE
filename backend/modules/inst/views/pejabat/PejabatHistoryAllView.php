<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\helpers\LinkHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\adak\models\Pengajaran */

$this->title = 'History Pejabat';
$this->params['breadcrumbs'][] = ['label' => 'Manajemen Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-history-view">

    <?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-book']);?>
    <?=$uiHelper->renderLine(); ?>

    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-history-pejabat',]) ?>
    <div>
        <p>
            <?php
                echo 'Untuk Menambahkan Pejabat, Silahkan pilih <b>"Tambah Pejabat"</b> pada menu dropdown <b>"Management Pejabat"</b> di sebelah kanan atas, atau Silahkan pilih <b>' . Html::a('Struktur Jabatan', Url::to(['/inst/inst-manager/index'])) . '</b> dari Instansi.';
            ?>
        </p>
    </div>
    <?=$uiHelper->endSingleRowBlock()?>
    <div>
        <p>
            Pilih Jabatan untuk melihat History Pejabat
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Jabatan',
                'attribute' => 'jabatan',
                'format' => 'html',
                'value' => function($model){
                    return LinkHelper::renderLink([
                        'label' => (isset($model['jabatan'])?$model['jabatan']:'Data tidak valid'),
                        'url' => Url::to(['/inst/pejabat/pejabat-by-jabatan-view', 'jabatan_id' => $model['struktur_jabatan_id'], 'otherRenderer' => true]),
                    ]);
                },
            ],
            [
                'attribute' => 'inisial',
            ],
            [
                'label' => 'Instansi',
                'attribute' => 'instansi_name',
                'value' => 'instansi.name',
            ],
            [
                'label' => 'Unit',
                'attribute' => 'unit_name',
                'value' => function($model){
                    return (isset($model->unit['name'])?$model->unit['name']:'-');
                }
            ],
        ],
    ]); ?>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\Url;
use common\helpers\LinkHelper;


use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\mref\models\search\DosenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = "Dosen";
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Dosen';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dosen-index">

    <?= $uiHelper->renderContentSubHeader('List '. $this->title, ['icon' => 'fa fa-list']);?>
    <?=$uiHelper->renderLine(); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-dosen',
            //'width' => 4,
        ]); ?>
    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        //'template' => '<tr><th>{}</th>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nidn',
            //'dosen_id',
            [
                'label' => 'Nama',
                'format'=>'raw',
                'attribute' => 'pegawai_id',
                'value' =>function ($data) {
                            return LinkHelper::renderLink(['label'=> (isset($data->pegawai) && !is_null($data->pegawai) && trim($data->pegawai->nama)!='')?$data->pegawai->nama:' ', 'data-pjax' => 0,'url'=>Url::toRoute(['view','id' => $data->dosen_id])]);
                        },
            ],
            [
                'label' => 'Prodi',
                'attribute' => 'prodi_id',
                'value' => function($data){ return isset($data->prodi->kbk_ind)?($data->prodi->jenjang->nama.'-'.$data->prodi->kbk_ind):''; }
            ],

            [
                'header' => '<a href>Status Aktif</a>',
                'attribute' => 'status_aktif',
                'value' => 'pegawai.statusAktifPegawai.desc'
            ],
            [
                'label' => 'Jabatan Akademik',
                'attribute' => 'jabatan_akademik_id',
                'value' => 'jabatanAkademik.desc'
            ],
            [
              'class' => 'common\components\ToolsColumn',
              'template' => '{view} {del}',
              'urlCreator' => function ($action, $model, $key, $index){
                if ($action === 'view') {
                    return Url::to(['view','id' => $model->dosen_id]);
                }elseif(($action === 'del')) {
                    return  Url::to(['del','id' => $model->dosen_id]);
                }
              }
            ],
        ],
    ]); ?>
    <?= $uiHelper->endContentBlock(); ?>
</div>

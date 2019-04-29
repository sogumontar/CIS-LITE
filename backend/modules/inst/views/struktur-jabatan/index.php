<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inst\models\search\InstansiSear */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Struktur Jabatan';
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['pejabat/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>

<div class="struktur-jabatan-index">

<?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-list']);?>
    <?=$uiHelper->renderLine(); ?>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-jabatan',]) ?>
    <?php
        //echo 'Silahkan pilih ' . Html::a('Jabatan', Url::to(['struktur-jabatan/index'])) . ' dari Instansi.<br />';
        //echo '*Pilih <code>Nama Pegawai</code> untuk alokasi Jabatan<br />';
        //echo '**Pilih <code>Jabatan</code> untuk alokasi Pegawai menjadi Pejabat';
    ?>
    <?=$uiHelper->endSingleRowBlock()?>

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'jabatan',
                'label' => 'Nama Jabatan',
            ],
            [
                'attribute' => 'inisial',
            ],
            [
                'attribute' => 'parent',
                'label' => 'Atasan',
                'value' => function($data){
                    if(is_null($data['parent']))
                        return '-';
                    else return $data['parent0']->jabatan;
                },
            ],
            [
                'attribute' => 'unit_id',
                'label' => 'Unit',
                'value' => function($data){
                    if(is_null($data['unit_id']))
                        return '-';
                    else return $data['unit']->name;
                },
            ],
            [
                'attribute' => 'inisial_inst',
                'label' => 'Instansi',
                'value' => 'instansi.inisial',
                'filter' => ArrayHelper::map($instansi, 'inisial', 'inisial'),
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'class' => 'common\components\ToolsColumn',
                'header' => 'Aksi',
                'template' => '{del}',
                'buttons' => [
                    'del' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Keluar dari Matakuliah', 'fa fa-trash');
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'del') {
                        if(isset($model->penugasan_pengajaran_id)){
                            return Url::to(['quit-from-course', 'penugasanPengajaranId' => $model->penugasan_pengajaran_id]);
                        }elseif(isset($model->mahasiswa_assistant_id)){
                            return Url::to(['quit-from-course', 'mahasiswaAssistantId' => $model->mahasiswa_assistant_id]);
                        }else{
                            return false;
                        }
                    }
                },
                'contentOptions' => ['class' => 'col-xs-1']
            ],
        ],
    ]);
    Pjax::end() ?>

</div>


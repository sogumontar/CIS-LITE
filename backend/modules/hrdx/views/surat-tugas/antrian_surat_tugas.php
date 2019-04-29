<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\SuratTugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Antrian Surat Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'Nama Perequest',
                'format' =>'text',
                'value' => function($model){
                    return $model->pegawai->nama;
                }
            ],
            'tugas',
            'no_surat_tugas',
            'tanggal_berangkat',
            'tanggal_kembali',
            'tanggal_mulai',
            'tanggal_selesai',

            [
                'class' => 'common\components\ToolsColumn',
                'template'=>'{detail}',
                'buttons'=>[
                    'detail' => function ($url, $model){
                        return ToolsColumn::renderCustomButton(Url::to(['detail-antrian','id'=>$model->surat_tugas_id]), $model, 'Detail Surat Tugas', 'fa fa-eye');
                    },
                ],
            ],
        ],
    ]); ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>

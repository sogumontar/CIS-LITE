<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\SuratTugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Surat Tugas';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Histori Surat Tugas '.$pegawai['nama'];
?>
<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>
    
    <div class="pull-right">
        <?php
        if (isset($user_role)) {
            if ($user_role['role']['name']=='wr2' || $user_role['role']['name']=='hrd') {
                echo $uiHelper->renderButtonSet([
                    'template' => ['add','list','accepted'],
                    'buttons' => [
                        'add' => ['url' => Url::toRoute(['add']), 'label' => 'Request Surat Tugas', 'icon' => 'fa fa-plus-square'],
                        'list' => ['url' => Url::toRoute(['antrian-surat-tugas']), 'label' => 'Antrian Surat Tugas', 'icon' => 'fa fa-book'],
                        'accepted' => ['url' => Url::toRoute(['surat-tugas-diterima']), 'label' => 'Surat Tugas yang Diterima', 'icon' => 'fa fa-check-circle'],
                    ]
                ]); 
            }
        }else{
            echo $uiHelper->renderButtonSet([
                'template' => ['add'],
                'buttons' => [
                    'add' => ['url' => Url::toRoute(['add']), 'label' => 'Request Surat Tugas', 'icon' => 'fa fa-plus-square'],
                    // 'fasilitas' => ['url' => Url::toRoute(['atur-fasilitas-perjalanan','id'=>$model->surat_tugas_id]), 'label' => 'Atur Fasilitas Perjalanan', 'icon' => 'fa fa-car'],
                    // 'print' => ['url' => Url::toRoute(['cetak-surat-tugas','id'=>$model->surat_tugas_id]), 'label' => 'Cetak Surat Tugas', 'icon' => 'fa fa-print'],
                    // 'laporan' => ['url' => Url::toRoute(['unggah-laporan','id'=>$model->surat_tugas_id]), 'label' => 'Unggah Laporan', 'icon' => 'fa fa-upload'],
                ]
            ]);
        }
        ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'tugas',
            'no_surat_tugas',
            'lokasi_tugas',
            'tanggal_berangkat',
            'tanggal_kembali',
            'tanggal_mulai',
            'tanggal_selesai',
            // 'keterangan:ntext',
            // 'pemberi_tugas',
            // 'catatan',
            // 'deleted',
            // 'deleted_by',
            // 'deleted_at',
            // 'updated_by',
            // 'updated_at',
            // 'created_by',
            // 'created_at',

            [
                'class' => 'common\components\ToolsColumn',
                'template'=>'{detail}',
                'buttons'=>[
                    'detail' => function ($url, $model){
                        return ToolsColumn::renderCustomButton(Url::to(['detail','id'=>$model->surat_tugas_id]), $model, 'Detail Surat Tugas', 'fa fa-eye');
                    },
                ],
            ],
        ],
    ]); ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>

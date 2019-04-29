<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Manajemen Penugasan Anggaran';
$this->params['header'] = 'Manajemen Penugasan Anggaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Browse Mata Anggaran', 'url' => ['penugasan-anggaran-browse']];
$this->params['breadcrumbs'][] = $this->title;

$ui = \Yii::$app->uiHelper;
?>

<div class="penugasan-pengajaran-data">
    <?= $ui->renderContentSubHeader("Data Mata Anggaran: " . $struktur_jabatan->jabatan.' - Tahun Anggaran '.$tahun_anggaran->tahun) ?>
    <?= $ui->renderLine() ?>
    <?= GridView::widget([
            'dataProvider' => $dataProviderMataAnggaran,
            'filterModel' => $searchModelMataAnggaran,
            'tableOptions' => ['class' => 'table table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'kode_anggaran',
                'name',
                [
                    'header' => 'Penugasan Terakhir',
                    'value' => function ($data) use ($tahun_anggaran_last, $struktur_jabatan){
                        if($tahun_anggaran_last==0)
                            return '<i class="fa fa-times-circle" style="color:red;"></i>';
                        if(StrukturJabatanHasMataAnggaran::isExist($tahun_anggaran_last, $struktur_jabatan->struktur_jabatan_id, $data->mata_anggaran_id))
                            return '<i class="fa fa-check-circle" style="color:green;"></i>';
                        else return '<i class="fa fa-times-circle" style="color:red;"></i>';
                     },
                     'format' => 'html',
                ],
                ['class' => 'common\components\SwitchColumn',
                 'header' => 'Penugasan',
                 'flag' => function ($data) use ($tahun_anggaran, $struktur_jabatan){
                    return StrukturJabatanHasMataAnggaran::isExist($tahun_anggaran->tahun_anggaran_id, $struktur_jabatan->struktur_jabatan_id, $data->mata_anggaran_id);
                 },
                 'contentOptions' => ['class' => 'col-xs-1']
                ],
            ],
        ]); ?>
</div>
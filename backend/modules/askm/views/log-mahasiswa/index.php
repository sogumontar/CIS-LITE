<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Pegawai;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\LogMahasiswaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="log-mahasiswa-index">

    <?= $uiHelper->renderContentSubHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>

    <div class="callout callout-info">
      <?php
        echo "<b>Keterangan</b><br/>";
        echo '1. Row berwarna kuning = Mahasiswa berada di luar kampus/asrama<br/>';
        echo '2. Row berwarna biru = Mahasiswa berada di dalam kampus/asrama<br/>';
        echo '3. Row berwarna merah = Mahasiswa terlambat memasuki kampus/asrama<br/>';
      ?>
    </div>

    <?= $uiHelper->beginContentRow(); ?>
    <?= $this->render('_search', ['searchModel' => $searchModel, 'prodi' => $prodi, 'dosen_wali' => $dosen_wali, 'asrama' => $asrama]); ?>
    <?= $uiHelper->endContentRow(); ?>
    <br /><br />
    <?php

        // $status1 = ($status == NULL)?'active':'';
        // $status2 = ($status == 1)?'active':'';

        // $toolbarItemStatusRequest =
        //     "<a href='".Url::to(['index'])."' class='btn btn-default '><i class='fa fa-list'></i> <span class='toolbar-label'>All</span></a>
        //     <a href='".Url::to(['index-ik'])."' class='btn btn-info'><i class='fa fa-road'></i> <span class='toolbar-label'>Izin Keluar</span></a>
        //     <a href='".Url::to(['index-ib'])."' class='btn btn-success '><i class='fa fa-home'></i> <span class='toolbar-label'>Izin Bermalam</span></a>
        //     "
        //     ;

    ?>

    <?php /* echo Yii::$app->uiHelper->renderToolbar([
    'pull-right' => true,
    'groupTemplate' => ['groupStatusExpired'],
    'groups' => [
        'groupStatusExpired' => [
            'template' => ['filterStatus'],
            'buttons' => [
                'filterStatus' => $toolbarItemStatusRequest,
            ]
        ],
    ],
]); */ ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'rowOptions' => function($model){
            if($model->ket != '-') return ['class' => 'danger'];
            else{
                if($model->jenis_log == 'Jam Keluar-Masuk'){
                    $tanggal = $model->waktu_kembali;
                    $day = date('Y-m-d H:i:s', strtotime($tanggal));//Yii::$app->formatter->asDate($tanggal,'EEEE');
                    $dayOut = date('Y-m-d H:i:s', strtotime($model->waktu_keluar));//Yii::$app->formatter->asDate($model->waktu_keluar,'EEEE');
                    $time = date('H:i', strtotime($tanggal));//Yii::$app->formatter->asTime($tanggal,'HH:mm');
                    if (is_null($tanggal) && $model->waktu_keluar!=NULL) {
                        return ['class' => 'warning'];
                    }
                }else{
                    // $waktu_keluar = Yii::$app->formatter->asDate($model->waktu_keluar,'EEEE');
                    // $waktu_kembali = Yii::$app->formatter->asDate($model->waktu_kembali,'EEEE');
                    // $rencana_keluar = Yii::$app->formatter->asDate($model->rencana_keluar,'EEEE');
                    // $rencana_kembali = Yii::$app->formatter->asDate($model->rencana_kembali,'EEEE');
                    if(is_null($model->waktu_kembali)){
                        return ['class' => 'warning'];
                    }
                }
                return ['class' => 'info'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->nama."</a>";
                },
            ],
            // [
            //     'attribute'=>'dim_prodi',
            //     'label' => 'Prodi',
            //     'filter'=>ArrayHelper::map(Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
            //         return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
            //     }])->all(), 'ref_kbk_id', function($data){
            //         if (is_null($data->jenjang_id)) {
            //             return '';
            //         } else{
            //             return $data->kbk_ind;
            //         }

            //     }, 'jenjangId.nama'),
            //     'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            //     'value'=> function($model){
            //         return $model->dim->kbkId==null?null:$model->dim->kbkId->jenjangId->nama." ".$model->dim->kbkId->kbk_ind;
            //     },
            // ],
            // [
            //     'attribute'=>'dim_asrama',
            //     'label' => 'Asrama',
            //     'format' => 'raw',
            //     'headerOptions' => ['style' => 'width:80px'],
            //     'filter'=>ArrayHelper::map(Asrama::find()->andWhere('deleted!=1')->asArray()->all(), 'asrama_id', 'name'),
            //     'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            //     'value' => 'dim.dimAsrama.kamar.asrama.name',
            // ],
            // [
            //     'attribute' => 'dim_dosen',
            //     'label' => 'Dosen Wali',
            //     'format' => 'raw',
            //     'value' => 'dim.registrasis.dosenWali.nama',
            // ],
            [
                'attribute'=>'waktu_keluar',
                'label' => 'Waktu Keluar',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value' => function($model){
                        if (is_null($model->waktu_keluar)) {
                            return '-';
                        }else{
                            return date('d M Y H:i', strtotime($model->waktu_keluar));
                        }
                    },
                // 'filter'=>DateTimePicker::widget([
                //     'model'=>$searchModel,
                //     'attribute'=>'tanggal_keluar',
                //     'template'=>'{input}{reset}{button}',
                //         'clientOptions' => [
                //             'startView' => 2,
                //             'minView' => 2,
                //             'maxView' => 2,
                //             'autoclose' => true,
                //             'format' => 'yyyy-mm-dd',
                //         ],
                // ])
            ],
            [
                'attribute'=>'waktu_kembali',
                'label' => 'Waktu Kembali',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value' => function($model){
                        if (is_null($model->waktu_kembali)) {
                            return '-';
                        }else{
                            return date('d M Y H:i', strtotime($model->waktu_kembali));
                        }
                    },
                // 'filter'=>DateTimePicker::widget([
                //     'model'=>$searchModel,
                //     'attribute'=>'tanggal_masuk',
                //     'template'=>'{input}{reset}{button}',
                //         'clientOptions' => [
                //             'startView' => 2,
                //             'minView' => 2,
                //             'maxView' => 2,
                //             'autoclose' => true,
                //             'format' => 'yyyy-mm-dd',
                //         ],
                // ])
            ],
            [
                'attribute' => 'jenis_log',
                'label' => 'Jenis Log',
                'contentOptions' => ['style' => 'font-weight:bold;text-align:center'],
                'headerOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'ket',
                'label' => 'Keterangan',
                'headerOptions' => ['style' => 'width:40px'],
            ]

        ],
    ]); ?>

</div>

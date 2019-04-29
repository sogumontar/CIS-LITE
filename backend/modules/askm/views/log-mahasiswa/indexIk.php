<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Asrama;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\LogMahasiswaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Log Mahasiswa Izin Keluar';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
Yii::$app->timeZone = 'UTC';
?>
<div class="log-mahasiswa-index">

    <?= $uiHelper->renderContentSubHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php

        // $status1 = ($status == NULL)?'active':'';
        // $status2 = ($status == 1)?'active':'';

        $toolbarItemStatusRequest =
            "<a href='".Url::to(['index-luar'])."' class='btn btn-default'><i class='fa fa-sign-out'></i> <span class='toolbar-label'>Log</span></a>

            <a href='".Url::to(['index-ik'])."' class='btn btn-info'><i class='fa fa-road'></i> <span class='toolbar-label'>Izin Keluar</span></a>
            <a href='".Url::to(['index-ib'])."' class='btn btn-success '><i class='fa fa-home'></i> <span class='toolbar-label'>Izin Bermalam</span></a>
            "
            ;

    ?>

    <?=Yii::$app->uiHelper->renderToolbar([
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
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            $kembali = $model->realisasi_kembali;
            $berangkat = $model->realisasi_berangkat;
            $date = Yii::$app->formatter->asDate($kembali,'EEEE');
            $rencana_kembali = Yii::$app->formatter->asDate($model->rencana_kembali,'EEEE');
            $time = Yii::$app->formatter->asTime($kembali,'HH:mm');
            $time_kembali = Yii::$app->formatter->asTime($rencana_kembali,'HH:mm');

            if($time <= $time_kembali && $date == $rencana_kembali){
                return ['class' => 'info'];
            } elseif ($berangkat != null && $kembali == null) {
                return ['class' => 'warning'];
            } elseif ($berangkat == null && $kembali == null) {
                return ['class' => 'info'];
            } else{
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'dim_nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            [
                'attribute'=>'dim_prodi',
                'label' => 'Prodi',
                'filter'=>ArrayHelper::map(Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
                    return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
                }])->all(), 'ref_kbk_id', function($data){
                    if (is_null($data->jenjang_id)) {
                        return '';
                    } else{
                        return $data->kbk_ind;
                    }

                }, 'jenjangId.nama'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value'=> function($model){
                    return $model->dim->kbkId==null?null:$model->dim->kbkId->jenjangId->nama." ".$model->dim->kbkId->kbk_ind;
                },
            ],
            [
                'attribute'=>'dim_asrama',
                'label' => 'Asrama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'filter'=>ArrayHelper::map(Asrama::find()->andWhere('deleted!=1')->asArray()->all(), 'asrama_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'dim.dimAsrama.kamar.asrama.name',
            ],
            [
                'attribute' => 'dim_dosen',
                'label' => 'Dosen Wali',
                'format' => 'raw',
                'value' => 'dim.registrasis.dosenWali.nama',
            ],
            [
                'attribute'=>'realisasi_berangkat',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->realisasi_berangkat==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->realisasi_berangkat));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'realisasi_berangkat',
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
            [
                'attribute'=>'rencana_kembali',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->rencana_kembali==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->rencana_kembali));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'rencana_kembali',
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
                'template' => '{view}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['izin-keluar/ika-by-baak-view', 'id' => $key]);
                    }
                }
            ],
        ],
    ]); ?>

</div>

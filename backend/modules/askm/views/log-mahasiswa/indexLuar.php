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

$this->title = 'Log Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="log-mahasiswa-index">

    <?= $uiHelper->renderContentSubHeader($this->title);?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $uiHelper->renderLine(); ?>

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

    <?php echo Yii::$app->uiHelper->renderToolbar([
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
            $tanggal = $model->tanggal_masuk;
            $day = Yii::$app->formatter->asDate($tanggal,'EEEE');
            $dayOut = Yii::$app->formatter->asDate($model->tanggal_keluar,'EEEE');
            $time = Yii::$app->formatter->asTime($tanggal,'HH:mm');

            if (is_null($tanggal) && $model->tanggal_keluar!=NULL) {
                return ['class' => 'warning'];
            } elseif($day=='Sunday'||$day=='Monday'||$day=='Tuesday'||$day=='Wednesday'||$day=='Thursday'){
                if($day==$dayOut){
                    if($time<='19:00'){
                        return ['class' => 'info'];
                    } elseif($time>='19:00'){
                        return ['class' => 'danger'];
                    }
                    else{
                        return ['class' => 'warning'];
                    }
                }
                else{
                    return ['class' => 'danger'];
                }
            }
            else if($day=='Friday'||$day=='Saturday'){
                if($day==$dayOut){
                    if($time<='20:00'){
                        return ['class' => 'info'];
                    } elseif($time>='20:00'){
                        return ['class' => 'danger'];
                    }
                    else{
                        return ['class' => 'warning'];
                    }
                }
                else{
                    return ['class' => 'danger'];
                }
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
                'attribute'=>'tanggal_keluar',
                'label' => 'Waktu Keluar',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value' => function($model){
                        if (is_null($model->tanggal_keluar)) {
                            return '-';
                        }else{
                            return Yii::$app->formatter->asDateTime($model->tanggal_keluar, 'php:d M Y H:i');
                        }
                    },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'tanggal_keluar',
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
            // [
            //     'attribute'=>'tanggal_masuk',
            //     'label' => 'Tanggal Masuk',
            //     'format'=> 'raw',
            //     'headerOptions' => ['style' => 'color:#3c8dbc'],
            //     'value' => function($model){
            //             if (is_null($model->tanggal_masuk)) {
            //                 return '-';
            //             }else{
            //                 return Yii::$app->formatter->asDateTime($model->tanggal_masuk, 'php:d M Y H:i');
            //             }
            //         },
            //     'filter'=>DateTimePicker::widget([
            //         'model'=>$searchModel,
            //         'attribute'=>'tanggal_masuk',
            //         'template'=>'{input}{reset}{button}',
            //             'clientOptions' => [
            //                 'startView' => 2,
            //                 'minView' => 2,
            //                 'maxView' => 2,
            //                 'autoclose' => true,
            //                 'format' => 'yyyy-mm-dd',
            //             ],
            //     ])
            // ],
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

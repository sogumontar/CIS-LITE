<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use backend\modules\askm\models\StatusRequest;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Pegawai;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\IzinBermalamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Izin Bermalam';
$this->params['breadcrumbs'][] = ['label' => 'Izin Bermalam', 'url' => ['index-admin']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Izin Bermalam';

$status_url = urldecode('IzinBermalamSearch%5Bstatus_request_id%5D');

$uiHelper=\Yii::$app->uiHelper;
$pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>
<div class="izin-bermalam-index">

    <div class="pull-right">
        Manage Request
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-flat btn-set btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span style="font-size: 18px;" class="fa fa-gear"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li>
                    <!-- <button type="button" onclick="submit()">Setujui yang dipilih</button> -->
                    <a href="#" id="selected-approve"><i class="fa fa-check"></i>Setujui yang dipilih</a>
                </li>
                <li>
                    <a href="/cis-lite/backend/web/index.php/askm/izin-bermalam/approve-all?id_keasramaan=<?= $pegawai->pegawai_id ?>"><i class="fa fa-check"></i>Setujui semua request</a>
                </li>
                <li>
                    <a href="#" id="selected-reject"><i class="fa fa-times"></i>Tolak yang dipilih</a>
                </li>
                <li>
                    <a href="/cis-lite/backend/web/index.php/askm/izin-bermalam/reject-all?id_keasramaan=<?= $pegawai->pegawai_id ?>"><i class="fa fa-times"></i>Tolak semua request</a>
                </li>
            </ul>
        </div>
    </div>

    <?= $uiHelper->renderContentSubHeader(' '.$this->title, ['icon' => 'fa fa-list']);?>
    <?= $uiHelper->renderLine(); ?>

    <?php // echo $this->render('_searchByAdmin', ['model' => $searchModel]); ?>

    <?php
        $status1 = ($status_request_id == 0)?'active':'';
        $status2 = ($status_request_id == 1)?'active':'';
        $status3 = ($status_request_id == 2)?'active':'';
        $status4 = ($status_request_id == 3)?'active':'';

        $toolbarItemStatusRequest =
            "<a href='".Url::to(['izin-bermalam/izin-by-admin-index'])."' class='btn btn-default ".$status1."'><i class='fa fa-list'></i><span class='toolbar-label'>All</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-admin-index', $status_url => 1])."' class='btn btn-info ".$status2."'><i class='fa fa-info'></i><span class='toolbar-label'>Requested</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-admin-index', $status_url => 2])."' class='btn btn-success ".$status3."'><i class='fa fa-check'></i><span class='toolbar-label'>Accepted</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-admin-index', $status_url => 3])."' class='btn btn-danger ".$status4."'><i class='fa fa-ban'></i><span class='toolbar-label'>Rejected</span></a>
            <a href='".Url::to(['izin-by-admin-index', $status_url => 4])."' class='btn btn-warning ".$status4."'><i class='fa fa-times'></i><span class='toolbar-label'>Canceled</span></a>
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

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'tabel-izin-bermalam',
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered', 'style' => 'font-size:12px;'],
        'rowOptions' => function($model){
            if($model->status_request_id == 1){
                return ['class' => 'info'];
            } else if($model->status_request_id == 2){
                return ['class' => 'success'];
            } else if($model->status_request_id == 3){
                return ['class' => 'danger'];
            } else {
                return ['class' => 'warning'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'realisasi_berangkat',
            // 'realisasi_kembali',
            // 'desc:ntext',
            // 'tujuan',
            // [
            // 'attribute' => 'dim_nama',
            // 'label' => 'Nama Mahasiswa',
            // 'format' => 'raw',
            // 'value' => 'dim.nama',
            // ],
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
                'attribute' => 'dim_angkatan',
                'label' => 'Angkatan',
                'headerOptions' => ['style' => 'width:20px'],
                'format' => 'raw',
                'value' => 'dim.thn_masuk',
                'filter'=>ArrayHelper::map($angkatan, 'thn_masuk', 'thn_masuk'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            'desc',
            [
                'attribute' => 'tujuan',
                'label' => 'Tempat Tujuan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:100px'],
                'value' => 'tujuan',
            ],
            [
                'attribute'=>'rencana_berangkat',
                'label' => 'Rencana Berangkat',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->rencana_berangkat==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->rencana_berangkat));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'rencana_berangkat',
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
                'label' => 'Rencana Kembali',
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
            [
                'attribute'=>'dim_asrama',
                'label' => 'Asrama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'filter'=>ArrayHelper::map(Asrama::find()->andWhere('deleted!=1')->asArray()->all(), 'asrama_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'dim.dimAsrama.kamar.asrama.name',
            ],
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {approve} {reject} {print}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'reject' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject', 'fa fa-times');
                        }
                    },
                    'approve' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve', 'fa fa-check');
                        }
                    },
                    'print' => function ($url, $model){
                        if ($model->status_request_id == 2) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Print', 'fa fa-print');
                        } else {
                            return "";
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
                    if ($action === 'view') {
                        return Url::toRoute(['izin-by-admin-view', 'id' => $key]);
                    }else if ($action === 'approve') {
                        return Url::toRoute(['approve-by-keasramaan-index', 'id_ib' => $model->izin_bermalam_id, 'id_keasramaan' => $pegawai->pegawai_id]);
                    // }else if ($action === 'del') {
                    //     return Url::toRoute(['del', 'id' => $key]);
                    }else if ($action === 'reject') {
                        return Url::toRoute(['reject-by-keasramaan-index', 'id_ib' => $model->izin_bermalam_id, 'id_keasramaan' => $pegawai->pegawai_id]);
                    }else if ($action === 'print') {
                        return Url::toRoute(['print-ib', 'id' => $key]);
                    }
                }
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                // 'contentOptions' => ['style' => 'width: 50px'],
                'name' => 'checked',
                'header' => '',
                'checkboxOptions' => function($model){
                    if ($model->status_request_id != 1) {
                        return ['value' => $model->izin_bermalam_id, 'disabled' => true,];
                    } else{
                        return ['value' => $model->izin_bermalam_id];
                    }
                }
            ]
        ],
    ]);
    Pjax::end() ?>
</div>

<!-- <script type="text/javascript">
    function submit(){
        var dialog = confirm("Setujui request yang dipilih ?");
        if (dialog == true) {
            var keys = $('#tabel-izin-bermalam').yiiGridView('getSelectedRows');
            // alert(keys);
            var ajax = new XMLHttpRequest();
            $.ajax({
                type: "POST",
                url: 'approve-selected',
                data: {keylist: keys},
                success: function(result){
                    console.log(result);
                }
            });
        }
    }
</script> -->

<script type="text/javascript">
    document.getElementById('selected-approve').onclick = function submit(){
        var keys = $('#tabel-izin-bermalam').yiiGridView('getSelectedRows');
            // alert(keys);
            var ajax = new XMLHttpRequest();
            var keasramaan_id = "<?php $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            echo $pegawai->pegawai_id; ?>"
            var htmlString = "approve-selected?id_keasramaan=" + keasramaan_id;
            $.ajax({
                type: "POST",
                url: htmlString,
                data: {keylist: keys},
                success: function(result){
                    console.log(result);
                }
            });
    }

    document.getElementById('selected-reject').onclick = function submit(){
        var keys = $('#tabel-izin-bermalam').yiiGridView('getSelectedRows');
            // alert(keys);
            var ajax = new XMLHttpRequest();
            var keasramaan_id = "<?php $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
            echo $pegawai->pegawai_id; ?>"
            var htmlString = "reject-selected?id_keasramaan=" + keasramaan_id;
            $.ajax({
                type: "POST",
                url: htmlString,
                data: {keylist: keys},
                success: function(result){
                    console.log(result);
                }
            });
    }
</script>

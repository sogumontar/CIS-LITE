<?php

use yii\helpers\Html;
use backend\modules\cist\assets\CistAsset;
use yii\widgets\DetailView;
use backend\modules\cist\models\AtasanCutiTahunan;
use backend\modules\cist\models\Pegawai;

CistAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan*/

$this->title = $model->pegawai->nama;
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Tahunan', 'url' => ['index-by-wr2']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-izin-view">

    <?php if ($model->statusCutiTahunan->status_by_atasan == 6 && $model->statusCutiTahunan->status_by_wr2 == 1) { ?>
        <div class="pull-right">
            <p>
                <?= Html::a('<i class="fa fa-check"></i> Setuju', ['accept-by-wr2', 'id' => $model->permohonan_cuti_tahunan_id], ['class' => 'btn btn-success']) ?>
                <?php echo Html::a('<i class="fa fa-times"></i> Tolak', ['reject-by-wr2', 'id' => $model->permohonan_cuti_tahunan_id], ['class' => 'btn btn-danger']); ?>
            </p>
        </div>
    <?php } ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <div class="row">
        <div class="col-md-12">
            <!--STEP HERE!!!!!-->
            <div class="border-box">
                <div class="stepwizard col-md-6">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-md-3">
                            <a href="#step-1" type="button" class="btn btn-success btn-circle disabled">1</a>
                            <p>Ajukan Izin</p>
                        </div>
                        <?php
                        if($model->statusIzin->status_by_atasan == 1){
                            echo '<div class="stepwizard-step col-md-4">
                                <a href="#step-2" type="button" class="btn btn-default btn-circle disabled">2</a>
                                <p>Atasan</p>
                               </div>';
                            echo '<div class="stepwizard-step col-md-4">
                                <a href="#step-3" type="button" class="btn btn-default btn-circle disabled">3</a>
                                <p>WR 2</p>
                               </div>';
                        } else if($model->statusIzin->status_by_atasan == 6){
                            echo '<div class="stepwizard-step col-md-4">
                                <a href="#step-3" type="button" class="btn btn-success btn-circle disabled">2</a>
                                <p>Atasan</p>
                               </div>';
                            if($model->statusIzin->status_by_wr2 == 1) {
                                echo '<div class="stepwizard-step col-md-4">
                                        <a href="#step-4" type="button" class="btn btn-default btn-circle disabled">3</a>
                                        <p>WR 2</p>
                                       </div>';
                            }
                            else if($model->statusIzin->status_by_wr2 == 6){
                                echo '<div class="stepwizard-step col-md-4">
                                        <a href="#step-4" type="button" class="btn btn-success btn-circle disabled">3</a>
                                        <p>WR 2</p>
                                       </div>';
                            }
                            else if($model->statusIzin->status_by_wr2 == 4){
                                echo '<div class="stepwizard-step col-md-4">
                                        <a href="#step-4" type="button" class="btn btn-danger btn-circle disabled">3</a>
                                        <p>WR 2</p>
                                       </div>';
                            }
                        }
                        else if($model->statusIzin->status_by_atasan == 4){
                            echo '<div class="stepwizard-step col-md-4">
                                <a href="#step-3" type="button" class="btn btn-danger btn-circle disabled">2</a>
                                <p>Atasan</p>
                               </div>';
                            echo '<div class="stepwizard-step col-md-4">
                                <a href="#step-4" type="button" class="btn btn-warning btn-circle disabled">3</a>
                                <p>WR 2</p>
                               </div>';
                        } else if($model->statusIzin->status_by_atasan == 5 && $model->statusIzin->status_by_wr2 == 5){
                            echo '<div class="stepwizard-step col-md-4">
                                    <a href="#step-2" type="button" class="btn btn-warning btn-circle disabled">2</a>
                                    <p>Atasan</p>
                                   </div>';
                            echo '<div class="stepwizard-step col-md-4">
                                    <a href="#step-3" type="button" class="btn btn-warning btn-circle disabled">3</a>
                                    <p>WR 2</p>
                                   </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
            <div class="col-xs-12">
                <div class="col-md-12">
                    <div class=" ">
                        <div class="box-header">

                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-md-4" for="status">Status</label>
                                <?php
                                if($model->statusIzin->status_by_atasan == 1) {
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_atasan',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh Atasan'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-gray">Waiting</span> Oleh <label>Atasan</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_atasan == 6){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_atasan',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh Atasan'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-green">Accepted</span> Oleh <label>Atasan</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_atasan == 4){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_atasan',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh Atasan'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-red">Rejected</span> Oleh <label>Atasan</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_atasan == 5){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_atasan',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh Atasan'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-yellow">Canceled</span> Oleh <label>Pemohon</label>',
                                    ]);
                                }
                                ?>
                                <label class="col-md-4" for="status"></label>
                                <?php
                                if($model->statusIzin->status_by_wr2 == 1) {
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_wr2',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh WR2'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-gray">Waiting</span> Oleh <label>WR 2</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_wr2 == 6){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_wr2',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh WR2'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-green">Accepted</span> Oleh <label>WR 2</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_wr2 == 4){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_wr2',
                                                'value' => 'statusIzin.status_by_atasan.status.status',
                                                'label' => 'Status Oleh WR2'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-red">Rejected</span> Oleh <label>WR 2</label>',
                                    ]);
                                }
                                else if($model->statusIzin->status_by_wr2 == 5){
                                    echo DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'status_by_wr2',
                                                'value' => 'statusIzin.status_by_wr2.status.status',
                                                'label' => 'Status Oleh WR2'
                                            ],
                                        ],
                                        'template' => '<span class="label bg-yellow">Canceled</span> Oleh <label>Pemohon</label>',
                                    ]);
                                }
                                ?>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-md-4" for="nama">Pemohon</label>
                                <?= DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        [
                                            'attribute' => 'pegawai_id',
                                            'value'     => $model->pegawai->nama,
                                        ],
                                    ],
                                    'template' => '<label class="input-group" id="nama">{value}</label>',
                                ]);
                                ?>
                                <div class="help-block help-block-error "></div>
                            </div> -->
                            <!-- <div class="form-group">
                                <label class="col-md-4" for="jabatan">Jabatan</label>
                                <label class="input-group" id="nama">Dosen</label>
                                <div class="help-block help-block-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="prodi">Prodi/Bagian</label>
                                <label class="input-group" id="Bagian">Teknik Informatika</label>
                                <div class="help-block help-block-error "></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="Tanggal Masuk">Tanggal Masuk</label>
                                <div class="input-group col-sm-5">
                                    <label class="input-group" id="waktuizin">09/06/2013</label>
                                </div>
                                <div class="help-block help-block-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="NIDN">NIDN/NIK</label>
                                <label class="input-group" id="NIDN">080989999</label>
                                <div class="help-block help-block-error"></div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Izin</label>
                                <div class="input-group col-sm-5">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'waktu_pelaksanaan',
                                                'value' => function($model){
                                                    $date = explode(',', $model->waktu_pelaksanaan);
                                                    $date2 = '';
                                                    $first = true;
                                                    foreach ($date as $d) {
                                                        $d = trim($d);
                                                        if(!$first){
                                                             $date2.= '<br />';
                                                        }
                                                        $date2.= '- '.date('d M Y', strtotime($d));
                                                        if ($first) {
                                                             $first = false;
                                                        }
                                                    }
                                                    return $date2;
                                                },
                                                'format' => 'raw'
                                            ],
                                        ],
                                        'template' => '<label class="input-group" id="waktucuti">{value}</label>',
                                    ])
                                    ?>
                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="lamaizin">Lama Izin (Hari)</label>
                                <?= DetailView::widget([
                                    'model' => $model,
                                    'attributes' => [
                                        'lama_cuti',
                                    ],
                                    'template' => '<label class="input-group" id="lamaizin">{value}</label>',
                                ])
                                ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="alasanizin">Alasan Izin</label>
                                <form>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            'alasan_cuti',
                                        ],
                                        'template' => '<label class="input-group" id="alasanizin">{value}</label>',
                                    ])
                                    ?>
                                </form>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="pengalihantugas">Pengalihan Tugas</label>
                                <form>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            'pengalihan_tugas',
                                        ],
                                        'template' => '<label class="input-group" id="pengalihantugas">{value}</label>',
                                    ])
                                    ?>
                                </form>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4" for="pengalihantugas">Atasan</label>
                                <form>
                                    <?php
                                        $mdl = new AtasanCutiTahunan();
                                        $atasan = $mdl->find()->where(['permohonan_cuti_tahunan_id' => $model->permohonan_cuti_tahunan_id])->all();
                                    ?>
                                    <?php
                                    $sum = "";
                                    $first = true;
                                    foreach($atasan as $data){
                                        $pegawai = Pegawai::find()->andWhere(['pegawai_id' => $data['pegawai_id']])->one();
                                        if (!$first) {
                                            $sum .= "<br /> ";
                                        }
                                        $sum .= '- '.$pegawai['nama'];
                                        if ($first) {
                                            $first = false;
                                        }
                                    }
                                    ?>
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'atasan',
                                                'value' => $sum,
                                                'format' => 'raw'
                                            ],
                                        ],
                                        'template' => '<label class="input-group" id="atasan">{value}</label>',
                                    ]) ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

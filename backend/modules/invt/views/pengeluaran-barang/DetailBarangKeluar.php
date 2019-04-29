<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detail Distribusi Barang';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = $this->title;;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginSingleRowBlock(['id' => 'detail-distribusi',]) ?>
 <div class="pull-right">
     <?=$uiHelper->renderButtonSet([
        'template' => ['tambah',],
        'buttons' => [
            'tambah' =>['url' => Url::to(['pengeluaran-barang/tambah-barang-distribusi', 'keterangan_pengeluaran_id'=>$modelKeterangan->keterangan_pengeluaran_id, ]), 'label' => 'Tambah Barang', 'icon' => 'fa fa-plus'],
        ]  
     ]) ?>
 </div> 
    <div class="table-detail">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="col-md-3">Unit</th>
                    <td><?php echo $modelKeterangan->detailUnit->nama ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Lokasi Awal Distribusi</th>
                    <td><?php echo $modelKeterangan->lokasi->nama_lokasi?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Awal Distribusi</th>
                    <td><?=Yii::$app->formatter->asDate($modelKeterangan->tgl_keluar, 'long')?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Total Jumlah Keluar</th>
                    <td><?php echo $modelKeterangan->total_barang_keluar ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Kerangan Distribusi</th>
                    <td><?php echo $modelKeterangan->keterangan ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Dikeluarkan Oleh</th>
                    <td><?php echo $modelKeterangan->getDetailOleh($modelKeterangan->oleh) ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Barang</th>
                    <td><?php 
                        foreach ($detailBarang as $key => $value) {
                            echo "<li>".$value->barang->nama_barang." : ".$value->jumlahBarang."</li>";
                        }
                    ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Lokasi</th>
                    <td><?php 
                        foreach ($detailLokasi as $key => $value) {
                            echo "<li>".$value->lokasi->nama_lokasi." : ".$value->jum_barang_lokasi."</li>";
                        }
                    ?></td>
                </tr>
                <tr>
                    <th class="col-md-2">Detail Barang</th>
                    <td><?php 
                            echo GridView::widget([
                                'dataProvider'=>$dataProvider,
                                'options'=>['class'=>'table table-bordered'],
                                 'rowOptions' => function ($model, $index, $widget, $grid){
                                      return ['height'=>'10'];
                                    },
                                'columns'=>[
                                    ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'label'=>'Nama Barang',
                                            'format'=>'raw',
                                            //mungkin akan diganti dengan histori transaksi abrang distribusi
                                            'value'=>function($model){
                                                return $model->historiPemindahan==null?$model->barang->nama_barang:"<a href='".Url::toRoute(['/invt/pemindahan-barang/detail-histori','pengeluaran_barang_id'=>$model->pengeluaran_barang_id])."'>".$model->barang->nama_barang."</a>";
                                            }
                                        ],
                                        [
                                            'label'=>'Kode Inventori',
                                            'attribute'=>'kode_inventori',
                                        ],
                                        [
                                            'label'=>'Lokasi Akhir',
                                            'value'=>'lokasi.nama_lokasi'
                                        ],
                                        [
                                            'label'=>'Tgl Distribusi/Pindah',
                                            'value'=>'tgl_keluar',
                                        ],
                                        'status_akhir',
                                        [
                                            'class' => 'common\components\ToolsColumn',
                                            'template' => '{edit} {del}',
                                            'urlCreator' => function ($action, $model, $key, $index) {
                                                if($action==='edit'){
                                                    return Url::to(['pengeluaran-barang/barang-keluar-edit', 'id'=>$key,]);
                                                }elseif($action=='del'){
                                                    return Url::to(['pengeluaran-barang/barang-keluar-del', 'id'=>$key,]);
                                                }
                                            },
                                            'contentOptions'=>['class'=>'col-xs-1']
                                        ],
                                    ]
                                ]);
                            ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

<?=$uiHelper->endSingleRowBlock()?>

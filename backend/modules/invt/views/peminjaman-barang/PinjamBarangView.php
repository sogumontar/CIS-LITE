<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PeminjamanBarang */

$this->title = "Detail Peminjaman Barang";
$this->params['breadcrumbs'][] = $isAdminByUnit==false?['label' => 'Peminjaman Barang', 'url' => ['pinjam-barang-browse']]:
                                                 ['label' => 'Peminjaman Barang', 'url' => ['pinjam-barang-browse-byadmin']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginSingleRowBlock(['id' => 'detail-pinjam-barang',]) ?>
    <div class="table-detail">
     <div class="pull-right">
         <?=$uiHelper->renderButtonSet([
            'template' => ['edit','hapus','kembali','approve', 'reject'],
            'buttons' => [
                'edit'=>['url'=>Url::to(['peminjaman-barang/edit-pinjam-barang','id'=>$model->peminjaman_barang_id]), 'label'=>'Edit', 'icon'=>'fa fa-pencil'],
                'hapus'=>['url'=>Url::to(['peminjaman-barang/pinjam-barang-del', 'id'=>$model->peminjaman_barang_id]), 'label'=>'Hapus', 'icon'=>'fa fa-trash'],
                'kembali' =>['url' => Url::to(['peminjaman-barang/kembali', 'id'=>$model->peminjaman_barang_id]), 'label' => 'Cek Kembali', 'icon' => 'fa fa-arrow-left'],
                'approve' =>['url' => Url::to(['peminjaman-barang/approve', 'id'=>$model->peminjaman_barang_id]), 'label' => 'Approve', 'icon' => 'fa fa-check'],
                'reject' =>['url' => Url::to(['peminjaman-barang/reject', 'id'=>$model->peminjaman_barang_id]), 'label' => 'Reject', 'icon' => 'fa fa-times'],
            ]  
         ]) ?>
     </div> 
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="col-md-3">Nama Peminjam </th>
                    <td><?= $model->getDetailNama($model->oleh) ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Unit Inventori</th>
                    <td><?php echo $model->unit->nama ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Pinjam</th>
                    <td><?=Yii::$app->formatter->asDate($model->tgl_pinjam, 'long')?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Kembali</th>
                    <td><?=Yii::$app->formatter->asDate($model->tgl_kembali, 'long')?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Status Approval</th>
                    <td><?= $_statusPinjam; ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Disetujui Oleh</th>
                    <td><?=$model->approved_by==null?"-":$model->getDetailNama($model->approved_by)?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Deskripsi/Alasan</th>
                    <td><?= $model->deskripsi?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Status Kembali</th>
                    <td><?= $_statusKembali?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Realisasi Kembali</th>
                    <td><?=$model->tgl_realisasi_kembali==null?"-":Yii::$app->formatter->asDate($model->tgl_realisasi_kembali, 'long')?></td>
                </tr>
                <tr>
                    <th class="dol-md-3">Barang</th>
                    <td><?php 
                            echo GridView::widget([
                                'dataProvider'=>$dataProvider,
                                'options'=>['class'=>'table table-bordered'],
                                 'rowOptions' => function ($model, $index, $widget, $grid){
                                      return ['height'=>'5'];
                                    },
                                'columns'=>[
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'label'=>'Nama Barang',
                                        'format'=>'raw',
                                        'value'=>function($model){
                                            return "<a href='".Url::toRoute(['/invt/barang/barang-view','barang_id'=>$model->barang_id])."'>".$model->barang->nama_barang."</a>";
                                        }
                                    ],
                                    'barang.jenisBarang.nama',
                                    [
                                        'label'=>'Jumlah Pinjam',
                                        'attribute'=>'jumlah',
                                    ],
                                    [
                                        'label'=>'Status Barang Kembali',
                                        'value'=>function($data)use($model){
                                            if($model->status_kembali==0){
                                                return "Belum Kembali";
                                            }else{
                                                if($data->jumlah_rusak==0){
                                                    return "Bagus";
                                                }else
                                                    return $data->jumlah_rusak." rusak";
                                            }
                                        }
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

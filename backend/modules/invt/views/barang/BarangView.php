<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Barang */

$this->title = $model->nama_barang;
$this->params['breadcrumbs'][] = $isAdminByUnit==false?['label' => 'Daftar Inventori', 'url' => ['barang-browse']]:
                                                 ['label' => 'Daftar Inventori', 'url' => ['barang-browse-byadmin']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
 <div class="pull-right">
     <?=$uiHelper->renderButtonSet([
        'template' => ['edit','hapus'],
        'buttons' => [
            'edit' =>['url' => Url::to(['barang/barang-edit', 'barang_id'=>$model->barang_id, 'unit_id'=>$model->unit_id]), 'label' => 'Edit Barang', 'icon' => 'fa fa-pencil'],
            'hapus'=>['url' => Url::to(['barang/barang-del', 'barang_id'=>$model->barang_id, 'unit_id'=>$model->unit_id]), 'label' => 'Hapus Barang', 'icon' => 'fa fa-trash'],
        ]  
     ]) ?>
 </div> 
<?= $uiHelper->beginTab([
    'tabs'=>[
        ['id'=>'data_barang','label'=>'Data Barang', 'isActive'=>true],
        ['id'=>'data_distribusi', 'label'=>'Data Distribusi Barang', 'isActive'=>false],
    ],
])?>
<?= $uiHelper->beginTabContent(['id'=>'data_barang', 'isActive'=>true])?>
    <?=$uiHelper->beginContentRow()?> 
        <?=$uiHelper->beginContentBlock(['id'=>'data-barang','width'=>9]); ?>
            <div class="table-detail">
            <table class="table table-bordered">
                <tbody>
                    <tr height="3">
                        <th class="col-md-3">Unit Inventori</th>
                        <td><?php echo $model->unit->nama ?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Nama Barang</th>
                        <td><?= $model->nama_barang?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Jenis Barang</th>
                        <td><?php echo $model->jenisBarang->nama ?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Kategori Barang</th>
                        <td><?php echo $model->kategori->nama ?></td>
                    </tr>                 
                    <tr height="3">
                        <th class="col-md-3">Serial Number</th>
                        <td><?php echo $model->serial_number?></td>
                    </tr>                    
                    <tr height="3">
                        <th class="col-md-3">Brand</th>
                        <td><?php echo $model->brand==null?"":$model->brand->nama?></td>
                    </tr>                    
                    <tr height="3">
                        <th class="col-md-3">Vendor</th>
                        <td><?php echo $model->vendor==null?"":$model->vendor->nama?></td>
                    </tr>                    
                    <tr height="3">
                        <th class="col-md-3">Supplier Barang</th>
                        <td><?php echo  $model->supplier==null?"-":$model->supplier; ?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Harga per Barang</th>
                        <td><?php echo $model->harga_per_barang?></td>
                    </tr>                  
                    <tr height="3">
                        <th class="col-md-3">Total Harga</th>
                        <td><?php echo $model->total_harga?></td>
                    </tr>                    
                    <tr height="3">
                        <th class="col-md-3">Tanggal Masuk</th>
                        <td><?php echo $model->tanggal_masuk?></td>
                    </tr>                    
                    <tr height="3">
                        <th class="col-md-3">Satuan Barang</th>
                        <td><?php echo $model->satuan==null?"":$model->satuan->nama?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Kapasitas Barang</th>
                        <td><?=$model->kapasitas?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Deskripsi</th>
                        <td><?php echo $model->desc ?></td>
                    </tr>
                    <tr height="3">
                        <th class="col-md-3">Total Jumlah</th>
                        <td><?php echo $model->jumlah." ".$model->satuan->nama?></td>
                    </tr> 
                </tbody>
            </table>
        </div>
        <?=$uiHelper->endContentBlock(); ?>
        
        <?=$uiHelper->beginContentBlock(['id'=>'gambar-barang','width'=>3]); ?>
            <img src= "<?=\Yii::$app->fileManager->generateUri($model->kode_file)?>" class="img-thumbnail" width="250" />
        <?=$uiHelper->endContentBlock(); ?>
    <?=$uiHelper->endContentRow()?> 
<?= $uiHelper->endTabContent() ?>

<?=$uiHelper->beginTabContent(['id'=>'data_distribusi', 'isActive'=>false])?>
<table class="table table-bordered">
    <tbody>
        <tr height="3">
            <th class="col-md-3">Jumlah Barang Keluar</th>
            <td>
                <?php
                    if($_jumlahBarangKeluar==0){
                        echo "Tidak Ada";
                    }else
                        echo $_jumlahBarangKeluar." ".$model->satuan->nama;
                ?>
            </td>
        </tr>
        <tr height="3">
            <th class="col-md-3">Lokasi Barang</th>
            <td>
                <?php
                    if($lokasi_distribusi==null){
                        echo "Tidak Ada";
                    }else{
                ?>
                    <table class="table table-condensed" width="50">
                    <thead>
                    <th>#</th>
                    <th>Nama Lokasi</th>
                    <th>Jumlah Barang</th>
                    </thead>
                    <?php
                        $i=1;
                        foreach ($lokasi_distribusi as $key => $lokasi) {
                    ?>
                        <tr>
                            <td><?=$i;?></td>
                            <td><?=$lokasi->lokasi->nama_lokasi?></td>
                            <td><?=$lokasi->jumlahBarang." ".$model->satuan->nama;?></td>
                        </tr>
                    <?php    
                        $i++;
                        }
                    ?>
                    </table>
                <?php
                    }
                ?>
            </td>
        </tr>
        <tr height="3">
            <th class="col-md-3">Detail Kode Inventori</th>
            <td>
                <?php 
                    echo GridView::widget([
                    'dataProvider'=>$dataProvider,
                    'options'=>['class'=>'table table-bordered'],
                    'rowOptions' => function ($model, $index, $widget, $grid){
                        return ['height'=>'10'];
                    },
                    'columns'=>[
                    ['class' => 'yii\grid\SerialColumn'],
                    'kode_inventori',
                    'lokasi.nama_lokasi', 
                    ]
                ]);
                ?>
            </td>
        </tr>
    </tbody>
</table>
<?=$uiHelper->endTabContent()?>
<?= $uiHelper->endTab() ?>

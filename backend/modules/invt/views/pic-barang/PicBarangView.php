<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\LinkHelper;

use yii\helpers\Url;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PicBarang */

$this->title = "PIC Barang : ".$model->pengeluaranBarang->kode_inventori;
$this->params['breadcrumbs'][] = ['label' => 'Daftar PIC Barang', 'url' => ['pic-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="pic-barang-view">
 <div class="pull-right">
     <?=$uiHelper->renderButtonSet([
        'template' => ['edit','hapus'],
        'buttons' => [
            'edit' =>['url' => Url::to(['pic-barang/pic-barang-edit', 'id'=>$model->pic_barang_id, ]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
            'hapus'=>['url'=>Url::to(['pic-barang/pic-barang-del','id'=>$model->pic_barang_id]), 'label'=>'Hapus','icon'=>'fa fa-trash'],
        ]  
     ]) ?>
</div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th class="col-md-3">Unit</th>
                <td><?= $model->unit->nama?></td>
            </tr>
            <tr>
                <th class="col-md-3">Barang</th>
                <td><?= $model->pengeluaranBarang->barang->nama_barang?></td>
            </tr>
            <tr>
                <th class="col-md-3">Kode Inventori</th>
                <td><?= $model->pengeluaranBarang->kode_inventori?></td>
            </tr>
            <tr>
                <th class="col-md-3">Kategori Barang</th>
                <td><?= $model->pengeluaranBarang->barang->kategori->nama?></td>
            </tr>
            <tr>
                <th class="col-md-3">Lokasi</th>
                <td><?= $model->pengeluaranBarang->lokasi->nama_lokasi?></td>
            </tr>
            <tr>
                <th class="col-md-3">Tanggal Assign</th>
                <td><?=Yii::$app->formatter->asDate($model->tgl_assign, 'long')?></td>
            </tr>
            <tr>
                <th class="col-md-3">PIC</th>
                <td><?= $model->pegawai->nama?></td>
            </tr>
            <tr>
                <th class="col-md-3">Keterangan</th>
                <td><?= $model->keterangan?></td>
            </tr>
            <tr>
                <th class="col-md-3">Attachment(s)</th>
                <td>
                    <?php
                        foreach ($modelPicBarangFile as $key => $value) {?>
                        <ul>
                            <li><?= LinkHelper::renderLink(['options'=>'target = _blank','label'=>$value->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($value->kode_file)])?></li>
                        </ul>
                    <?php
                        }
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use backend\assets\JqueryTreegridAsset;

$uiHelper = Yii::$app->uiHelper;
JqueryTreegridAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\LokasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Detail Distribusi Barang';
$this->params['breadcrumbs'][] = ['label'=>'Lokasi Barang', 'url'=>['pengeluaran-barang/lokasi-barang']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = $model->nama_lokasi;
$this->params['header'] = $this->title;
?>
<table class="tree table font-smaller">
    <thead>
        <tr class="treegrid-i-<?=$model->lokasi_id?>">
            <td colspan="5"><strong><?=$model->nama_lokasi?></strong>&nbsp &nbsp 
                <i>Total: <?=$model->getJumlahBarangByLokasi($model->lokasi_id)==null?0:$model->getJumlahBarangByLokasi($model->lokasi_id)?></i>
                &nbsp &nbsp 
                    <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-table', 'tooltip' => "Detail Barang", 'url'=>Url::toRoute(['pengeluaran-barang/detail-barang-bylokasi', 'lokasi_id'=>$model->lokasi_id])]) ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="treegrid-ia-<?=$model->lokasi_id?> treegrid-parent-i-<?=$model->lokasi_id?>">
            <td class="col-md-1"><strong>No.</strong></td>
            <td class="col-md-2"><strong>Nama Barang</strong></td>
            <td class="col-md-1"><strong>Jumlah</strong></td>
            <td class="col-md-2"><strong>Kategori</strong></td>
            <td class="col-md-1"><strong>Unit</strong></td>
        </tr>
        <?php
            $_barang = $model->detailDistribusiByLokasi;
            $i=1;
            foreach ($_barang as $key => $value) {
        ?>
                    <tr class="treegrid-ia-<?=$model->lokasi_id?> treegrid-parent-i-<?=$model->lokasi_id?>">
                        <td class="col-md-1"><?=$i;?>.</td>
                        <td class="col-md-2"><?=$value->barang->nama_barang?></td>
                        <td class="col-md-1"><?=$value->jumlahBarang." ".$value->barang->satuan->nama?></td>
                        <td class="col-md-2"><?=$value->barang->kategori->nama?></td>
                        <td class="col-md-1"><?=$value->detailUnit->nama?></td>
                    </tr>
        <?php
            $i++;
            }
            if($model->getChilds()!=null){
                echo $this->render('_detailChild',['_parentId'=>$model->lokasi_id, '_childs'=>$model->getChilds()]);
            }   
        ?>
    </tbody>
</table>
<?php 
  $this->registerJs(
    "$(document).ready(function() {
        $('.tree').treegrid({
          expanderExpandedClass: 'glyphicon glyphicon-minus',
          expanderCollapsedClass: 'glyphicon glyphicon-plus',
          initialState: 'expanded'
        });
    });

  ", 
    View::POS_END);
?>

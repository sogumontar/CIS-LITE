<?php
use yii\helpers\Url;
use common\helpers\LinkHelper;


foreach ($_childs as $_child) {
?>
        <tr class="treegrid-i-<?=$_child->lokasi_id?> treegrid-parent-i-<?=$_parentId?>">
            <td colspan="5"><strong><?=$_child->nama_lokasi?></strong>&nbsp &nbsp 
                 <i>Total: <?=$_child->getJumlahBarangByLokasi($_child->lokasi_id)==null?0:$_child->getJumlahBarangByLokasi($_child->lokasi_id)?></i>

                &nbsp &nbsp 
                    <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-table', 'tooltip' => "Detail Barang", 'url'=>Url::toRoute(['pengeluaran-barang/detail-barang-bylokasi', 'lokasi_id'=>$_child->lokasi_id])]) ?>
            </td>
        </tr>
        <tr class="treegrid-ia-<?=$_child->lokasi_id?> treegrid-parent-i-<?=$_child->lokasi_id?>" >
            <td class="col-md-1"><strong>No.</strong></td>
            <td class="col-md-2"><strong>Nama Barang</strong></td>
            <td class="col-md-1"><strong>Jumlah</strong></td>
            <td class="col-md-2"><strong>Kategori</strong></td>
            <td class="col-md-1"><strong>Unit</strong></td>
        </tr>
        <?php
            $_barang = $_child->detailDistribusiByLokasi;
            $i=1;
            foreach ($_barang as $key => $value) {
        ?>
                    <tr class="treegrid-ia-<?=$_child->lokasi_id?> treegrid-parent-i-<?=$_child->lokasi_id?>">
                        <td class="col-md-1"><?=$i;?>.</td>
                        <td class="col-md-2"><?=$value->barang->nama_barang?></td>
                        <td class="col-md-1"><?=$value->jumlahBarang." ".$value->barang->satuan->nama?></td>
                        <td class="col-md-2"><?=$value->barang->kategori->nama?></td> 
                        <td class="col-md-1"><?=$value->detailUnit->nama?></td>
                    </tr>
        <?php
            $i++;
          }
        ?>
    <?php
    	// render childs again
      if ($_child->getChilds()!=null) {
      	echo $this->render('_detailChild', ['_parentId'=> $_child->lokasi_id, '_childs' => $_child->getChilds()]);
      }
    ?>
<?php } ?>
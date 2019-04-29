<?php
use yii\helpers\Url;
use common\helpers\LinkHelper;


foreach ($_childs as $_child) {
?>
		<tr class="treegrid-i-<?=$_child->lokasi_id?> treegrid-parent-i-<?=$_parentId?>">
            <td><?=$_child->nama_lokasi ?></td>
            <td><?=$_child->desc?></td>
            <td>
              <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-eye', 'tooltip' => "Detail Lokasi", 'url'=>Url::toRoute(['lokasi-view', 'lokasi_id'=>$_child->lokasi_id])]) ?>
              <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-pencil', 'tooltip' => "Edit Lokasi", 'url'=>Url::toRoute(['lokasi-edit', 'lokasi_id'=>$_child->lokasi_id])]) ?>
              <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-trash', 'tooltip' => "Hapus Lokasi", 'url'=>Url::toRoute(['lokasi-del', 'lokasi_id'=>$_child->lokasi_id])]) ?>
            </td>
    </tr>
    <?php
    	// render childs again
      if ($_child->getChilds()!=null) {
      	echo $this->render('childs', ['_parentId'=> $_child->lokasi_id, '_childs' => $_child->getChilds()]);
      }
    ?>
    <tr class="treegrid-ia-<?=$_child->lokasi_id?> treegrid-parent-i-<?=$_child->lokasi_id?>">
      <td><?= LinkHelper::renderLinkButton(['label'=> '____',
                                                  'icon'=> 'glyphicon-plus', 
                                                  'url'=>Url::toRoute(["lokasi-add",'parent_id'=>$_child->lokasi_id]), 
                                                  'class'=>'btn-success btn-xs'])?> 
        </td>
        <td class="text-grey italic">Tambah Detail Lokasi</td> 
        <td></td>
    </tr>
<?php } ?>
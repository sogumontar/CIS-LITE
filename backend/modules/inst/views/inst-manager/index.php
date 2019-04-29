<?php

use yii\helpers\Html;
use common\components\ToolsColumn;
use yii\web\View;
use common\helpers\LinkHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inst\models\search\InstansiSear */
/* @var $dataProvider yii\data\ActiveDataProvider */

$uiHelper=\Yii::$app->uiHelper;

$this->title = 'Instansi Manager';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Instansi Manager";

?>

<div id="ins-container">

    <?= $uiHelper->renderContentSubHeader('List Instansi', ['icon' => 'fa fa-list']);?>
    <?= $uiHelper->renderLine(); ?>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-instansi',]) ?>
    <?php
        echo 'Silahkan pilih <code>Nama Instansi</code> untuk melihat Struktur Organisasi.';
    ?>
    <?=$uiHelper->endSingleRowBlock()?>
    
    <table class="tree table">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Inisial</th>
          <th>Deskripsi</th>
          <th class="col-md-1">Action</th>
        </tr>
      </thead>
      <tbody>
      <?php 
          $insIsExist = false;
          foreach ($inss as $ins) {
            if(isset($insId))
              if($insId == (int)$ins->instansi_id){
                $insIsExist = true;
              }
       ?>
        <tr>
          <td><?=LinkHelper::renderLink(['label' => $ins->name, 'url'=>Url::toRoute(['strukturs', 'instansi_id' => $ins->instansi_id])]) ?></td>
          <td><?=$ins->inisial ?></td>
          <td><?=$ins->desc ?></td>
          <td>
            <?=LinkHelper::renderLinkIcon(['icon' => 'glyphicon glyphicon-pencil', 'url'=>Url::toRoute(['instansi-edit', 'id'=>$ins->instansi_id])]) ?>
            <?=LinkHelper::renderLinkIcon(['icon' => 'glyphicon glyphicon-trash', 'url'=>Url::toRoute(['instansi-del', 'id'=>$ins->instansi_id])]) ?>
          </td>
        </tr>
        <?php } ?>
        <tr>
          <td><?= LinkHelper::renderLinkButton(['label'=> '______',
                                                'icon'=> 'glyphicon-plus', 
                                                'url'=>Url::toRoute(['inst-manager/instansi-add']), 
                                                'class'=>'btn-success btn-xs'])?> 
          </td>
          <td></td>
          <td class="text-grey italic">Tambah <code>Instansi</code></td>
          <td></td>
        </tr>
      </tbody>
    </table>
</div>
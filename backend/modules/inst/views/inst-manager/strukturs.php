<?php 
use yii\helpers\Html;
use common\components\ToolsColumn;
use yii\web\View;
  use yii\helpers\Url;
  use common\helpers\LinkHelper;
  use backend\assets\JqueryTreegridAsset;
  use backend\modules\xdev\assets\XdevAsset;

use backend\modules\inst\assets\InstAsset;

InstAsset::register($this);

  $uiHelper = \Yii::$app->uiHelper;
  JqueryTreegridAsset::register($this);
  XdevAsset::register($this);

  $this->title = $instansi_name;
  $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title;
  $this->params['header'] = 'Struktur Jabatan: '.$this->title;

 ?>

<div class="callout callout-info">
  <?php
    echo 'Expand pada <b>Jabatan</b> untuk melihat Struktur di bawahnya<br />';
    echo 'Pilih  '.LinkHelper::renderLinkButton(array('url'=>Url::to('#'), 'label'=> '______', 'class'=>'btn-success btn-xs', 'icon'=> 'glyphicon-plus')).'  untuk menambah Jabatan sesuai Posisi pada Struktur<br /><br />';
    echo 'Keterangan <i>icon</i> pada <b>Status Tenant</b> <i class="ace-icon fa fa-angle-double-right"></i> &nbsp
    <i class="fa fa-user" style="color:black;"></i> Single &nbsp&nbsp
    <i class="fa fa-user" id="empty"></i> Single, belum ada Pejabat &nbsp&nbsp
    <i class="fa fa-users" style="color:black;"></i> Multi Tenant &nbsp&nbsp
    <i class="fa fa-users" id="empty"></i> Multi Tenant, belum ada Pejabat<br />';
    echo '<i>Hover</i> atau sorot pada <i>icon</i> untuk mengetahui <b>Pejabat</b> yang tengah menjabat';
  ?>
</div>

<?=$uiHelper->renderToolbar([
    'pull-right' => false,
    'groupTemplate' => ['group1'],
    'groups' => [
        'group1' => [
            'template' => ['expand', 'collapse'],
            'buttons' => [
                'expand' => ['id' =>'expand', 'url' => '#', 'label' => 'expand', 'icon' => 'fa fa-expand'],
                'collapse' => ['id' =>'collapse', 'url' => '#', 'label' => 'collapse', 'icon' => 'fa fa-compress'],
            ]
        ]
    ],
    'clientScript' => [
        'view' => $this,
        'script' => "
            $('#expand').on('click', function(event){
              $('.tree').treegrid('expandAll'); 
            })
            $('#collapse').on('click', function(event){
              $('.tree').treegrid('collapseAll');
            })
        ",
    ]
]) ?>

<table class="tree table">
  <thead>
    <tr>
      <th class="col-md-7">Jabatan</th>
      <th class="col-md-2">Unit</th>
      <th class="col-md-2">Status Tenant <?=$uiHelper->renderTooltip("Status Tenant adalah informasi apakah sebuah Jabatan hanya bisa dijabat oleh 1 Pejabat atau lebih", ['position' => 'top']) ?></th>
      <th class="col-md-2">Aksi</th>
    </tr>
  </thead>
  <tbody id="strukturs">
  <?php
        foreach($strukturs as $s)
        {
            if(isset($s->struktur_jabatan_id)){
                $pej = "";
                $count = 0;
                $pej .= "<ul style='margin-left:-25px; float:left;'>";
                foreach($s->pejabats as $p){
                  if($p->status_aktif==1){
                    $pej .= "<li>".$p->pegawai['nama']."</li>";
                    $count++;
                  }
                }
                $pej .= "</ul>";
                ?><tr id="node-<?=$s->instansi_id ?>-<?=$s->struktur_jabatan_id ?>" class="treegrid-<?=$s->struktur_jabatan_id ?> treegrid-parent-<?=$s->parent ?>" >
                  <td><?=$s->jabatan ?></td>
                  <td><?=$s->unit['name']==''?'-':$s->unit['name'] ?></td>
                  <td><?=$s->is_multi_tenant==0?($count!=0?'<i class="fa fa-user" rel="popover" title="Pejabat" data-html="true" data-content="'.$pej.'"></i>':'<i class="fa fa-user" id="empty"></i>'):($count!=0?'<i class="fa fa-users" rel="popover" title="Pejabat" data-html="true" data-content="'.$pej.'"></i>':'<i class="fa fa-users" id="empty"></i>') ?></td>
                  <td>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'glyphicon glyphicon-eye-open', 'url'=>Url::toRoute(['pejabat/pejabat-by-jabatan-view', 'jabatan_id' => $s->struktur_jabatan_id, 'otherRenderer' => 2])]) ?>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'glyphicon glyphicon-pencil', 'url'=>Url::toRoute(['struktur-jabatan/struktur-jabatan-edit', 'id'=>$s->struktur_jabatan_id, 'otherRenderer' => 2])]) ?>
                    <?=LinkHelper::renderLinkIcon(['icon' => 'glyphicon glyphicon-trash', 'url'=>Url::toRoute(['struktur-jabatan/struktur-jabatan-del', 'id'=>$s->struktur_jabatan_id, 'otherRenderer' => true])]) ?>
                  </td>
                </tr>
                <?php
            }else{
              ?>
                <tr id="treegrid-new-p<?=$s['parent'] ?>" class="treegrid-parent-<?=$s['parent'] ?> treegrid-c<?=$s['parent'] ?>">
                  <td><?=LinkHelper::renderLinkButton(["label"=> "______", "icon"=> "glyphicon-plus", 
                              "url"=>Url::toRoute(["struktur-jabatan/struktur-jabatan-add",  
                              "instansi_id"=>$instansi_id,
                              "parent"=>$s['parent'],
                              "otherRenderer" => true,
                            ]), "class"=>"btn-success btn-xs"]) ?></td>
                  <td class="text-grey italic"></td>
                  <td class="text-grey italic"></td>
                  <td></td>
                </tr>
              <?php
            }
        }
  ?>
        <tr id="treegrid-0" class="treegrid-0">
          <td><?= LinkHelper::renderLinkButton(['label'=> '______',
            'icon'=> 'glyphicon-plus', 
            'url'=>Url::toRoute(['struktur-jabatan/struktur-jabatan-add', 'instansi_id'=>$instansi_id, 'parent'=>0, 'otherRenderer' => true]), 
            'class'=>'btn-success btn-xs'])?>
          </td>
          <td class="text-grey italic"></td>
          <td class="text-grey italic"></td>
          <td></td>
        </tr>
  </tbody>
</table>

<?php 
  $this->registerJs("
    $(document).ready(function() {
        $('.tree').treegrid({
          expanderExpandedClass: 'fa fa-caret-down',
          expanderCollapsedClass: 'fa fa-caret-right',
          initialState: 'collapsed',
          saveState: 'true'
        });

        $('i.fa').popover({'trigger':'hover'});
    });
  ", 
    View::POS_END);
?>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\helpers\LinkHelper;
$uiHelper = Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['vendor-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
 <div class="pull-right">
     <?=$uiHelper->renderButtonSet([
        'template' => ['edit','hapus'],
        'buttons' => [
            'edit' =>['url' => Url::to(['vendor/vendor-edit', 'id'=>$model->vendor_id]), 'label' => 'Edit Vendor', 'icon' => 'fa fa-pencil'],
            'hapus'=>['url' => Url::to(['vendor/vendor-del', 'id'=>$model->vendor_id]), 'label' => 'Hapus Vendor', 'icon' => 'fa fa-trash'],
        ]  
     ]) ?>
 </div> 
<?=$uiHelper->beginSingleRowBlock(['id'=>'vendor-content']) ?>
<?=$uiHelper->beginTab([
	'tabs'=>[
		['id'=>'data_vendor','label'=>'Data Vendor', 'isActive'=>true],
		['id'=>'file', 'label'=>'Arsip File', 'isActive'=>false],
	],
])?>
<?=$uiHelper->beginTabContent(['id'=>'data_vendor','isActive'=>true])?>
    <?= DetailView::widget([
        'model' => $model,
        'options' =>[
            'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            'nama',
            'alamat',
            'telp',
            'email',
            'link',
            'contact_person',
            'telp_contact_person',
            'desc:ntext',
        ],
    ]) ?>
<?=$uiHelper->endTabContent()?>

<?=$uiHelper->beginTabContent(['id'=>'file','isActive'=>false])?>
    <div class="pull-right">
        <?=$uiHelper->renderButtonSet([
        'template' => ['add'],
        'buttons' => [
            'add' => ['url' => Url::to(['vendor/arsip-add','id'=>$model->vendor_id]), 'label' => 'Tambah Arsip/File', 'icon' => 'fa fa-briefcase'],
        ]
        ]) ?>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Judul Arsip</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if($arsipModel!==null){
               foreach ($arsipModel as $key => $value) {
        ?>          
            <tr>
	            <td>
	                <?=LinkHelper::renderLink(['pjax' => true, 'label' => $value->judul_arsip, 
	                                'url'=>\Yii::$app->urlManager->createUrl(['invt/vendor/arsip-view','id'=>$value->arsip_vendor_id]), 
	                                'target' =>'#arsip-container']);
	                ?>                                    
	            </td>
	            <td><?=$value->desc?></td>
	            <td>
	                <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-pencil', 'tooltip' => "Ubah Arsip",'url'=>Url::to(['vendor/arsip-edit','arsip_id'=>$value->arsip_vendor_id,'vendor_id'=>$model->vendor_id])])?>
	                <?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-trash', 'tooltip' => "Hapus Arsip",'url'=>Url::to(['vendor/arsip-del','arsip_id'=>$value->arsip_vendor_id,'vendor_id'=>$model->vendor_id])])?>
	            </td>
            </tr>
       <?php     
            }
        }
       ?>
            </tbody>
   </table>

    <div id='arsip-container'>
        <?php
        if(isset($arsip))
            {
                $this->render("ArsipView", ['arsip'=>$arsip,'arsipFile'=>$arsipFile]);
            }
        ?>
    </div>
<?=$uiHelper->endTabContent()?>
<?=$uiHelper->endTab()?>
<?=$uiHelper->endSingleRowBlock() ?>

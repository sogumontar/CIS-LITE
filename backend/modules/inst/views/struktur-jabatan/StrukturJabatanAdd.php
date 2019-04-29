<?php

use yii\helpers\Html;
use common\helpers\LinkHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\StrukturJabatan */

$this->title = 'Create Struktur Jabatan';
if($otherRenderer){
    $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['inst-manager/index']];
    $this->params['breadcrumbs'][] = ['label' => $inst->name, 'url' => ['inst-manager/strukturs?instansi_id='.$inst->instansi_id]];
}else
    $this->params['breadcrumbs'][] = ['label' => 'Struktur Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader('ATASAN: '.$parent_name)//$uiHelper->renderContentSubHeader('ATASAN: '.$parent_name.' '.LinkHelper::renderLinkIcon(['icon' => 'fa fa-download', 'tooltip' => "Export to JSON file", 'options' => 'target=new', 'url'=>Url::toRoute(['inst-manager/export-structure-to-json', 'jabatan_id'=>$parent_id, 'instansi_id'=>$instansi_id])]).'<div class="pull-right">'.LinkHelper::renderLinkButton(['label' => ' Import Inputan berbentuk Tree dalam File JSON', 'icon'=>'fa fa-upload', 'class' => 'btn-sm btn-warning', 'url' => Url::toRoute(['inst-manager/import-structure-definition', 'instansi_id' => $instansi_id, 'parent_id' => $parent_id])]).'</div>') ?>

<?=$uiHelper->renderLine(); ?>

<div class="struktur-jabatan-create">

    <?= $this->render('_form', [
        'model' => $model, 
        'parent' => $parent,
        'instansi' => $instansi,
        'inst' => $inst,
        'unit' => $unit,
        'otherRenderer' => $otherRenderer,
        'tenant' => $tenant,
        'mata_anggaran' => $mata_anggaran,
        'laporan' => $laporan,
    ]) ?>
</div>
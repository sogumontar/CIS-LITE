<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\LinkHelper;

$uiHelper = Yii::$app->uiHelper;
$this->title = 'Data Referensi';
$this->params['breadcrumbs'][] = 'Inventori';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>

<?= $uiHelper->beginSingleRowBlock(['id'=>'data-referensi'])?>
<table class="table table-striped">
    <thead>
        <th>#</th>
        <th>Data Referensi</th>
        <th>Aksi</th>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Brand</td>
            <td><?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-bars', 'tooltip' => "Detail Referensi",
                    'url'=>Url::to(['brand/brand-browse'])])?>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jenis Barang</td>
            <td><?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-bars', 'tooltip' => "Detail Referensi",
                    'url'=>Url::to(['jenis-barang/jenis-barang-browse'])])?>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>Kategori</td>
            <td><?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-bars', 'tooltip' => "Detail Referensi",
                    'url'=>Url::to(['kategori/kategori-browse'])])?>
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td>Satuan Barang</td>
            <td><?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-bars', 'tooltip' => "Detail Referensi",
                    'url'=>Url::to(['satuan/satuan-browse'])])?>
            </td>
        </tr>
        <tr>
            <td>5</td>
            <td>Vendor</td>
            <td><?=LinkHelper::renderLinkIcon(['icon' => 'fa fa-bars', 'tooltip' => "Detail Referensi",
                    'url'=>Url::to(['vendor/vendor-browse'])])?>
            </td>
        </tr>
    </tbody>
</table>
<?= $uiHelper->endSingleRowBlock();?>
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use backend\modules\askm\models\Asrama;
use common\helpers\LinkHelper;
use yii\widgets\Pjax;


$this->title = "List NIM Tidak Valid";
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 50%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
</style>
<div class="asrama-index">
    <?= $uiHelper->renderContentHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>
    <table>
        <thead>
            <tr>
                <th style="width: 20px">No</th>
                <th>NIM</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 0;
                foreach ($temp_novalid as $input) {
                    if ($input != null && $input != ''){
                    $i++;
            ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $input ?></td>
            </tr>
        <?php }} ?>
        </tbody>
    </table>
</div>

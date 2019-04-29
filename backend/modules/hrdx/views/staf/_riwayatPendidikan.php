<?php

/* @var $this yii\web\View */
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;
use common\helpers\LinkHelper;
use common\components\SwitchButton;
use yii\helpers\Url;
use yii\grid\GridView;

$uiHelper = \Yii::$app->uiHelper;

?>


<div id="app-container">
    <?= $uiHelper->renderContentSubHeader("Riwayat Pendidikan") ?>
    <table class="tree table">
      <thead>
        <tr>
          <th>Jenjang</th>
          <th>Universitas</th>
          <th>Prodi</th>
          <th>Judul TA</th>
          <th>IPK</th>
        </tr>
      </thead>
      <tbody>
      <?php 
           foreach ($pendMdl as $key => $value) {
                foreach ($value['riwayatPendidikan'] as $a) {
       ?>
        <tr>
            <td><?php echo $a->jenjang_id; ?></td>
            <td><?php echo $a->universitas; ?></td>
            <td><?php echo $a->prodi; ?></td>
            <td><?php echo $a->judul_ta; ?></td>
            <td><?php echo $a->ipk; ?></td>
        </tr>
       <?php         
                }
            }
       ?>
      </tbody>
    </table>
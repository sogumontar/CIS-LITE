<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use backend\modules\rakx\assets\RakxAsset;

//RakxAsset::register($this);

$uiHelper=\Yii::$app->uiHelper;
Pjax::begin();
?>

<!--<section class="comment-list">-->
  <?php
    foreach($review_program as $rp){
  ?>
  <article class="row">
    <div class="col-md-12 col-sm-12">
      <div class="panel panel-default arrow left">
        <div class="panel-heading right">
          <div class="comment-user"><i class="fa fa-user"></i> <?php echo $rp->pejabat->pegawai->nama.' - '.$rp->pejabat->strukturJabatan->jabatan; ?></div>
            <!--<div class="comment-user"><i class="fa fa-certificate"></i> <?= $rp->pejabat->strukturJabatan->jabatan ?></div>-->
            <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i><?php echo ' '.date("j M Y", strtotime($rp->tanggal_review)); ?></time>
        </div>
        <div class="panel-body">
          <header class="text-left">
            
          </header>
          <div class="comment-post">
            <p>
              <?= $rp->review ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </article>
  <?php } ?>
<!--</section>-->

<?php Pjax::end(); ?>
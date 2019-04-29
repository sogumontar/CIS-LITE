<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
//use backend\modules\rakx\assets\RakxAsset;

//RakxAsset::register($this);

$uiHelper=\Yii::$app->uiHelper;
Pjax::begin();
?>

<?php if($program->auth_review) { ?>

<div class="pegawai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model_review, 'review')->textarea(['rows' => 6, 'placeholder' => "Write a review...", 'style'=>'width:100%;'])->label(false) ?>

    <div class="form-group">
            <label class="control-label pull-right" for="menugroup-desc"></label>
            <div class="pull-right">
                <?= Html::submitButton('Create Review', ['class' => $model_review->isNewRecord ? 'btn btn-success pull-right' : 'btn btn-primary pull-right']) ?>
            </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<br /><br />
<?=$uiHelper->renderLine(); ?>

<?php } ?>

<!--<section class="comment-list">-->
  <?php
    foreach($review_program as $rp){
  ?>
  <article class="row">
    <div class="col-md-12 col-sm-12">
      <div class="panel panel-default arrow left">
        <div class="panel-heading right" style="background-color: white;">
          <div class="comment-user"><i class="fa fa-user"></i> <?php echo $rp->pejabat->pegawai->nama.' - '.$rp->pejabat->strukturJabatan->jabatan; ?></div>
            <!--<div class="comment-user"><i class="fa fa-certificate"></i> <?= $rp->pejabat->strukturJabatan->jabatan ?></div>-->
            <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i><?php echo ' '.date("d M Y", strtotime($rp->tanggal_review)); ?></time>
        </div>
        <div class="panel-body" style="background-color: #f5f5f5;">
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
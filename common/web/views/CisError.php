<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">
    <h1 style="font-size: 60px; font-weight: bold; color: #c4c4c4;" class="text-center"><?= Html::encode($code) ?></h1>
    <h1 style="font-size: 40px;" class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="text-center">
        <?= nl2br(Html::encode($message)) ?>
    </div>

</div>

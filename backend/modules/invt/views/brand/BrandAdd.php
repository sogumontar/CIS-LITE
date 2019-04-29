<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */

$this->title = 'Tambah Brand';
$this->params['breadcrumbs'][] = ['label' => 'Brand', 'url' => ['brand-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
<div class="brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

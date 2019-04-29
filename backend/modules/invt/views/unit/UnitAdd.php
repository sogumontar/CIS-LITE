<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Unit */

$this->title = 'Tambah Unit';
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['unit-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']= $this->title;
?>
<div class="unit-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

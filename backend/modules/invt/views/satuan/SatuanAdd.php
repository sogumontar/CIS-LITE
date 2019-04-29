<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Satuan */

$this->title = 'Tambah Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['satuan-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="satuan-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Vendor */

$this->title = 'Tambah Vendor';
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['vendor-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="vendor-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Staf */

$this->title = 'Tambah Staf';
$this->params['breadcrumbs'][] = ['label' => 'Staf', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="staf-add">

    <?= $uiHelper->renderContentSubHeader('Tambah Staff', ['icon' => 'fa fa-plus']);?>
    <?=$uiHelper->beginContentBlock(['id' => 'grid-dosen',
     	'header' => ' ',
      	'type' => 'danger'
      	])?>
    <?= $this->render('_form', [
        'model' => $model,
        'pendMdl' => $pendMdl,
        'prodi' => $prodi,
        'jenjang' => $jenjang,
        'stafRole' => $stafRole,
    ]) ?>

</div>

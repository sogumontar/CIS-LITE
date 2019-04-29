<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Pejabat */

$this->title = 'Tambah Pejabat';
$this->params['breadcrumbs'][] = ['label' => 'Manajemen Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Tambah Pejabat';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-create">

    <?=$uiHelper->renderContentSubHeader($this->title, ['icon' => 'fa fa-plus']) ?>
    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'pegawai' => $pegawai,
    ]) ?>

</div>

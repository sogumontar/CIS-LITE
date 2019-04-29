<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Pejabat */

$this->title = 'Perbaharui Kontrak Pejabat';
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Perbaharui Kontrak Pejabat';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-create">

    <?=$uiHelper->renderContentSubHeader($this->title, ['icon' => 'fa fa-plus']) ?>
    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_formPejabatExtendKontrak', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'pegawai' => $pegawai,
    ]) ?>

</div>

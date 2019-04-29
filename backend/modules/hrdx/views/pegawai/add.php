<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Pegawai */

$this->title = 'Tambah Pegawai';
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pegawai-add">

	<?=$uiHelper->renderContentSubHeader($this->title, ['icon' => 'fa fa-plus']) ?>
    <?=$uiHelper->renderLine(); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-pegawai',
            //'width' => 4,
        ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?=$uiHelper->endContentBlock()?>

</div>

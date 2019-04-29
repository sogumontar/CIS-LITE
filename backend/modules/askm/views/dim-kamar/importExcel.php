<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimKamar */

$this->title = 'Tambah Penghuni';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$asrama->name, 'url' => ['asrama/view-detail-asrama', 'id' => $asrama->asrama_id]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-kamar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-import', [
        'model' => $model,
    ]) ?>

</div>

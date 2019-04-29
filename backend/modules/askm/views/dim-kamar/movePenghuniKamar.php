<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimKamar */

$this->title = 'Pindahkan Penghuni';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$asrama->name, 'url' => ['asrama/view-detail-asrama', 'id' => $asrama->asrama_id]];
$this->params['breadcrumbs'][] = ['label' => 'Pilih Asrama', 'url' => ['pilih-asrama', 'id' => $_GET['id'], 'id_kamar' => $_GET['id_kamar'], 'id_asrama' => $_GET['id_asrama']]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-kamar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formEdit', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Kamar */

$this->title = 'Tambah Kamar';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$asrama->name, 'url' => ['asrama/view-detail-asrama', 'id' => $_GET['id_asrama']]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kamar', 'url' => ['kamar/index', 'KamarSearch[asrama_id]' => $_GET['id_asrama'], 'id_asrama' => $_GET['id_asrama']]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kamar-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_formEditKamar', [
        'model' => $model,
    ]) ?>

</div>

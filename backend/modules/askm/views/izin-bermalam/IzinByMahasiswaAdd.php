<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalam */

$this->title = 'Request Izin Bermalam';
$this->params['breadcrumbs'][] = ['label' => 'Izin Bermalam', 'url' => ['index-mahasiswa']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-bermalam-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formByMahasiswa', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\AtasanIzin */

$this->title = 'Create Atasan Izin';
$this->params['breadcrumbs'][] = ['label' => 'Atasan Izins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-izin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

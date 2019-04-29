<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPengampu */

$this->title = 'Create Izin Pengampu';
$this->params['breadcrumbs'][] = ['label' => 'Izin Pengampus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-pengampu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

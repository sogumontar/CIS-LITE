<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\MataAnggaran */

$this->title = 'Create Mata Anggaran';
$this->params['breadcrumbs'][] = ['label' => 'Mata Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mata-anggaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'standar' => $standar,
    ]) ?>

</div>

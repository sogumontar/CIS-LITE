<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\TahunAnggaran */

$this->title = 'Create Tahun Anggaran';
$this->params['breadcrumbs'][] = ['label' => 'Tahun Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tahun-anggaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

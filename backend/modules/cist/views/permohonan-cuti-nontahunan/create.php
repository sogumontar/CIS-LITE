<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = 'Create Permohonan Cuti Nontahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-cuti-nontahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

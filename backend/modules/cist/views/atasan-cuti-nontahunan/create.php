<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\AtasanCutiNontahunan */

$this->title = 'Create Atasan Cuti Nontahunan';
$this->params['breadcrumbs'][] = ['label' => 'Atasan Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-cuti-nontahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

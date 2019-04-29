<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\JenisAbsen */

$this->title = 'Tambah Cuti Bersama';
$this->params['breadcrumbs'][] = ['label' => 'Cuti Bersama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuti-bersama-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formCutiBersama', [
        'model' => $model,
    ]) ?>

</div>
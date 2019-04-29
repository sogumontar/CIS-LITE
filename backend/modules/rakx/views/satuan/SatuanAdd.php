<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Satuan */

$this->title = 'Create Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="satuan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

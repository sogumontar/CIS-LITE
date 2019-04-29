<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Instansi */

$this->title = 'Update Instansi: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="instansi-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formInstansi', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Instansi */

$this->title = 'Create Instansi';
$this->params['breadcrumbs'][] = ['label' => 'Institusi Manager', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formInstansi', [
        'model' => $model,
    ]) ?>

</div>

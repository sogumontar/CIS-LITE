<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pedoman */

$this->title = 'Buat Pedoman';
$this->params['breadcrumbs'][] = ['label' => 'Pedomen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedoman-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

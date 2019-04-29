<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Asrama */

$this->title = 'Tambah Asrama';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asrama-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

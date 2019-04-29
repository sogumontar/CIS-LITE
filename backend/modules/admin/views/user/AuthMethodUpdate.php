<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\AuthenticationMethod */

$this->title = 'Update Authentication Method: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authentication Methods', 'url' => ['auth-method']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authentication-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_authMethodForm', [
        'model' => $model,
    ]) ?>

</div>

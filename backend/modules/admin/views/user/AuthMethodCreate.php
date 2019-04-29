<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\AuthenticationMethod */

$this->title = 'Create Authentication Method';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authentication-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_authMethodForm', [
        'model' => $model,
    ]) ?>

</div>

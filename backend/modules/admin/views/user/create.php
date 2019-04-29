<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
	

?>
<div class="user-create">

    <div class="user-form">

	    <?php $form = ActiveForm::begin([
	    		'layout' => 'horizontal',
			    'id' => 'create-form',
			    'enableAjaxValidation' => true,
			    'options' => [],
	    ]); ?>
	    <?= $form->field($model, 'username')->textInput(['maxlength' => 32]) ?>
	    <?= $form->field($model, 'email')->textInput(['maxLength' => 100])?>
	    <?= $form->field($model, 'password1')->passwordInput() ?>
	    <?= $form->field($model, 'password2')->passwordInput() ?>
	    <?= $form->field($model, 'authenticationMethodId')->dropDownList($authMethodArr) ?>
	    <?= $form->field($model, 'autoActive')->checkbox() ?>
		<div class="form-group field-userform-autoactive">
			<div class="col-sm-6 col-sm-offset-3">
					<?= Html::submitButton('Add User', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>

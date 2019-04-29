<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\User */

$this->title = 'Update User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']	= "Edit User";
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
	    <?= $form->field($model, 'email',[
	    		'inputOptions' => [],
	    	])->textInput(['maxLength' => 100])?>
	    <?= $form->field($model, 'password1')->passwordInput() ?>
	    <?= $form->field($model, 'password2')->passwordInput() ?>
	    <?= $form->field($model, 'authenticationMethodId')->dropDownList($authMethodArr) ?>
	    <?= $form->field($model, 'autoActive')->checkbox() ?>
		
		<div class="form-group field-userform-password2 required">
			<label class="control-label col-sm-5" for="userform-password2"></label>
			<div class="col-sm-6">
		        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
	    <?php ActiveForm::end(); ?>

	</div>

</div>

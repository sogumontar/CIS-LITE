<?php
namespace backend\modules\dashboard\models;

class TestForm	extends \yii\base\Model {
	
	public $name;
	public $profilePicture;
	public $attachments;

	public function rules() {
		return [
			[['name'], 'required'],
		];
	}

}
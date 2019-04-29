<?php
namespace common\models\validators;

use yii\validators\Validator;

/**
 * Simple standalone email students del validator
 *
 * @author: Marojahan Sigiro <marojahan@del.ac.id>
 */
class EmailStudentsValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!$this->validate($model->$attribute, $errors)) {
            $this->addError($model, $attribute, $errors);
        }
    }

    public function validate ($email, $errors) {
    	$arrEmail = explode("@", $email);
    	if(sizeof($arrEmail > 1)){
    		$errors = "Alamat Email harus sesuai format email students IT Del";
    		return false;
    	}

    	if((string)$arrEmail[1] !== 'students.del.ac.id'){
    		$errors = "Alamat Email harus sesuai format email students IT Del";
    		return false;
    	}
    	
 		return true;

    }
}
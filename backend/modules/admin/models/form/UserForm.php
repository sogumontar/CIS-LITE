<?php
namespace backend\modules\admin\models\form;

use Yii;
use yii\base\Model;
use backend\modules\admin\models\User;

/**
 * User create model
 */

class UserForm extends Model
{
	private $userModel;

	public $username;
	public $password1;
	public $password2;
	public $email;
	public $autoActive = 0;
	public $authenticationMethodId = 1;

	public function rules(){
		return [
			[['username', 'password1', 'password2', 'email'], 'required'],
			[['username'], 'usernameIsUnique'],
			[['email'], 'emailIsUnique', 'on' => 'create'],
			[['password1'], 'passwordIsStrong'],
			[['password2'], 'passwordIsMatch'],

			[['email'], 'email'],
		];
	}
	public function scenarios(){
		return [
			'create' => ['username', 'password1', 'password2', 'email'],
			'update' => ['email'],
			'updatePassword' => ['email', 'password1', 'password2']
		];
	}
	public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password1' => 'Password',
            'password2' => 'Retype Password',
            'email'		=> 'Email Address',
            'autoActive' => 'User automatically active?',
            'authenticationMethodId' => 'Authentication Method'
        ];
    }

    /**
     * Load data from user model, for user update form
     * @param  backend\modules\admin\models\User User $user user model
     */
    public function loadAttributesFromModel($_userModel){
    	$this->userModel =  $_userModel;
    	//\Yii::$app->get('debugger')->print_array($_userModel);
    	if(!$_userModel->getIsNewRecord()){
    		$this->username = $_userModel->username;
    		$this->email = $_userModel->email;
    		$this->authenticationMethodId = $_userModel->authentication_method_id;
    		$this->autoActive = $_userModel->status;
    	}
    }

	/**
	 * validate username when creating user to have unique username
	 * 
	 * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
	 */
	public function usernameIsUnique($attribute, $params){
		if(User::findOne(['username' => $this->username])){
			$this->addError($attribute, 'Username is not available!');
		}
	}

	/**
	 * validate username when creating user to have unique username
	 * 
	 * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
	 */
	public function emailIsUnique($attribute, $params){

		if(User::findOne(['email' => $this->email])){
			$this->addError($attribute, 'Email address already registered!');
		}
	}

	public function passwordIsStrong($attribute, $params){
		if ($this->password1 == null || strlen($this->password1) < 2) {
			$this->addError($attribute, 'Password is very.. very.. weak!');
		}
	}

	/**
	 * validate $password === $password2
	 * 
	 * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
	 */
	public function passwordIsMatch($attribute, $params){
		if($this->password1 !== null && $this->password1 !== $this->password2){
			$this->addError($attribute, 'Password is not match!');
		}
	}
}

?>
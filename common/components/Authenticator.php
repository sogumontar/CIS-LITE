<?php

namespace common\components;

use yii\base\InvalidConfigException;
use backend\modules\admin\models\AuthenticationMethod;

/**
 * @author: Marojahan Sigiro <marojahan@gmail.com>
 */
class Authenticator {
	const AUTH_STRING_DB = 'DATABASE';

	public static function authenticate($user, $supliedPassword){
		$auth_status = false;
		if($user!== null){
			if(!isset($user->authenticationMethod)){
				return false;
			}

			return self::authenticateToServer($user, $supliedPassword, $user->authenticationMethod);
		}

		return $auth_status;
	}
	
	private static function authenticateToServer($user, $supliedPassword, $authServer){
		$auth_status = false;
		if ($authServer !== null) {
			switch ($authServer->authentication_string){
				case self::AUTH_STRING_DB:
					$auth_status = self::authenticateToDb($user, $supliedPassword);
					break;
				default:
					//DB authentication as default??
					break;
			}
		}

		return $auth_status;
	}

	private static function authenticateToDb($user, $password){
		return $user->validatePassword($password);
	}
}
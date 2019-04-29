<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\LoginForm;
use backend\modules\admin\models\User;
use backend\modules\admin\models\Application;
use backend\modules\admin\models\Profile;
use backend\modules\admin\models\TelkomSsoUser;
use backend\modules\admin\models\UserConfig;
use backend\modules\admin\models\form\TelkomSsoUserResetForm;

use yii\web\ForbiddenHttpException;
/**
 * Needed for AJAX
 */

use common\components\SwitchHandler;

use yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class UserController extends \yii\web\Controller
{
    
    public $menuGroup = 'user-profile';
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * display user profile
     * @return [type] [description]
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {

            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout(true);
        
        return $this->goHome();
    }

    public function actionReset()
    {
        return $this->render('reset');
    }

    public function actionChpass()
    {
        return $this->render('chpass');
    }

}

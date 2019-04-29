<?php

namespace backend\modules\xdev\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
	/*public function behaviors(){
        return [
                'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
                ],
            ];
    }*/
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}

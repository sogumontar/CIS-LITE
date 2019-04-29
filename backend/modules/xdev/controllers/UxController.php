<?php

namespace backend\modules\xdev\controllers;

class UxController extends \yii\web\Controller
{
	public $menuGroup = 'ux-guide';

    /**
	 * Default behaviour to do privilege controll based on systemx-core privilege system
	 */
    
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

    public function actionPatterns()
    {
        return $this->render('patterns');
    }

}

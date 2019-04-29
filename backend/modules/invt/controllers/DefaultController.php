<?php

namespace backend\modules\invt\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
                ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}

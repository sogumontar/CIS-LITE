<?php

namespace backend\modules\xdev\controllers;

use backend\modules\admin\models\Application;
use backend\modules\admin\models\Workgroup;

class ComponentController extends \yii\web\Controller
{
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
        \Yii::$app->messenger->addInfoFlash("message flash dibuat!!");
        \Yii::$app->messenger->addSuccessFlash("message flash berhasil dibuat!!");
        \Yii::$app->messenger->addSuccessFlash("message flash berhasil dibuat lagi !!");
        \Yii::$app->messenger->addWarningFlash("apakah message flash berhasil dibuat ??");
        \Yii::$app->messenger->addErrorFlash("message flash berhasil tidak dibuat !!");

        $model = new Application;

        $workgroup_sample = Workgroup::find()->all();

        return $this->render('index', ['model' => $model, 'sampleData' => $workgroup_sample]);
    }

}

<?php

namespace backend\modules\api\controllers;

use yii;
use yii\web\Response;

class NotificationController extends \yii\web\Controller
{
	/**
	 * Default behaviour to do privilege controll based on systemx-core privilege system
	 */
	public function behaviors(){
        return [
                'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
                ],
            ];
    }
    
    /**
     * delete single notification
     * @param  Integer $id notification Id
     * @return [type]     [description]
     */
    public function actionDel($id)
    {
        if(Yii::$app->request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Yii::$app->messenger->deleteMyNotifications([$id]);
        }
    }

    public function actionMarkread($id)
    {   
        if(Yii::$app->request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Yii::$app->messenger->markMyNotificationAsRead($id);
        }
    }

    public function actionSetallseen(){
        if(Yii::$app->request->isAjax){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Yii::$app->messenger->setAllMyNotificationsAsSeen();
        }
    }

}

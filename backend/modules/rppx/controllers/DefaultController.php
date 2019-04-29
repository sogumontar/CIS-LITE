<?php

namespace backend\modules\rppx\controllers;

use yii\web\Controller;

/**
 * Default controller for the `rppx` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('Menu_RPPX');
    }
    public function actionTest(){
    	return $this->render('index');
    }
    public function actionApp(){
    	return $this->render('ApprovalByGBK');
    }
}

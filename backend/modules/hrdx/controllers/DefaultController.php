<?php

namespace backend\modules\hrdx\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
	public $menuGroup = 'hrdx-controller';
    public function actionIndex()
    {
        return $this->render('index');
    }
}

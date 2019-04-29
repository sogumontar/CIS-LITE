<?php

namespace backend\modules\admin\controllers;

use Yii;
use backend\modules\admin\models\User;
use common\models\User as UserIdentity;
use backend\modules\admin\models\search\UserSearch;
use backend\modules\admin\models\form\UserForm;
use backend\modules\admin\models\Role;
use backend\modules\admin\models\AuthenticationMethod;
use backend\modules\admin\models\search\AuthenticationMethodSearch;


use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Needed for AJAX
 */
use yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /*controller menu group id*/
    public $menuGroup = 'umcontroller';

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new UserForm(['scenario' => 'create']);
        $authMethods = AuthenticationMethod::find()->all();
        
        $authMethodArr = [];
        foreach ($authMethods as $authMethod) {
            $authMethodArr[$authMethod->authentication_method_id] = $authMethod->name." (".$authMethod->desc.")";
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        

        if ($model->load(Yii::$app->request->post(), 'UserForm')) {
            $request = Yii::$app->request;
            $model->autoActive = $request->post('UserForm')['autoActive']? $request->post('UserForm')['autoActive']: $model->autoActive;
            $model->authenticationMethodId = $request->post('UserForm')['authenticationMethodId']? $request->post('UserForm')['authenticationMethodId']: $model->authenticationMethodId;
            
            if($model->validate()){
                $user = new User();
                $user->username = $model->username;
                $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password1);
                $user->authentication_method_id = $model->authenticationMethodId;
                $user->auth_key = Yii::$app->getSecurity()->generateRandomString();

                $sysx_key = '';                
                do {
                    $sysx_key = Yii::$app->getSecurity()->generateRandomString(32);
                } while (User::find()->where(['sysx_key' => $sysx_key])->exists());
                $user->sysx_key = $sysx_key;

                $user->email = $model->email;
                $user->status = $model->autoActive;
                // \Yii::$app->get('debugger')->print_array($user);
                
                if($user->save()){
                    //automatic add authenticated role
                    if(!isset(Yii::$app->params['authenticatedRoleName'])){
                        throw new InvalidConfigException("'authenticatedRoleName' params must be specified in params config");
                    }
                    $authenticatedRoleName = Yii::$app->params['authenticatedRoleName'];
                    $authenticatedRole = Role::find()->where('name = :name', [':name' => $authenticatedRoleName])->one();
                    if($authenticatedRole){
                        $user->link('roles', $authenticatedRole);
                    }

                    return $this->redirect(['index']);
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'authMethodArr' => $authMethodArr,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $userModel = $this->findModel($id);
        $userForm = new UserForm(['scenario' => 'update']);
        $userForm->loadAttributesFromModel($userModel);

        $authMethods = AuthenticationMethod::find()->all();

        $authMethodArr = [];
        foreach ($authMethods as $authMethod) {
            $authMethodArr[$authMethod->authentication_method_id] = $authMethod->name." (".$authMethod->desc.")";
        }


        //AJAX Validation start
        if (Yii::$app->request->isAjax && $userForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($userForm);
        }
        //AJAX Validation end
        
        if (Yii::$app->request->isPost){
            //to activate all disabled attribute in update scenario
            $userForm->scenario = 'create';
            $userForm->load(Yii::$app->request->post(), 'UserForm');
            $userForm->scenario = 'update';

            if(strlen($userForm->password1) > 0){
                $userForm->scenario = 'updatePassword';
            }

            $request = Yii::$app->request;
            $userForm->autoActive = $request->post('UserForm')['autoActive'];
            $userForm->authenticationMethodId = $request->post('UserForm')['authenticationMethodId']? $request->post('UserForm')['authenticationMethodId']: $userForm->authenticationMethodId;

            if($userForm->validate()){
                if($userForm->scenario == 'updatePassword'){
                    $userModel->password_hash = Yii::$app->getSecurity()->generatePasswordHash($userForm->password1);
                }

                $userModel->authentication_method_id = $userForm->authenticationMethodId;

                $userModel->status = $userForm->autoActive;

                if($userModel->save()){
                    return $this->redirect(['index']);
                }
            }
            
            
        }

        $userForm->scenario = 'update';
        return $this->render('update', [
            'model' => $userForm,
            'authMethodArr' => $authMethodArr,
        ]);
    
    }
    
     /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionRepairSysxKey(){
        $noKeyUsers = User::find()->where(['sysx_key' => null])->all();
        foreach ($noKeyUsers as $user) {
            $user->sysx_key = $this->_genSysxKey();
            $user->save();
        }
        return $this->redirect(['index']);
    }

    private function _genSysxKey(){
        $sysx_key = '';                
        do {
            $sysx_key = Yii::$app->getSecurity()->generateRandomString(32);
        } while (User::find()->where(['sysx_key' => $sysx_key])->exists());
        return $sysx_key;
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

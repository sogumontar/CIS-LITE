<?php

namespace backend\modules\invt\controllers;

use Yii;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\User;
use backend\modules\invt\models\search\UserSearch;
use backend\modules\invt\models\search\UnitSearch;
use backend\modules\invt\models\UnitCharged;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\SwitchHandler;

/**
 * UnitController implements the CRUD actions for Unit model.
 */
class UnitController extends Controller
{
    public $menuGroup = "m-unit";
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => ['*'],
                ],
                
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Unit models.
     * @return mixed
     */
    public function actionUnitBrowse()
    {
        $searchModel = new UnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('UnitBrowse', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Unit model.
     * @param integer $id
     * @return mixed
     */
    public function actionUnitView($id)
    {
        $modelsearch = new UserSearch();
        $query = User::find()
                        ->innerJoinWith("units")
                        ->where([Unit::tableName().".unit_id"=>$id]);
        $dataProvider = $modelsearch->search(Yii::$app->request->queryParams, $query);
        return $this->render('UnitView', [
            'model' => $this->findModel($id),
            'modelsearch'=>$modelsearch,
            'dataProvider'=>$dataProvider,
        ]);
    }

    /**
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUnitAdd()
    {
        $model = new Unit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['unit-view', 'id' => $model->unit_id]);
        } else {
            return $this->render('UnitAdd', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Unit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnitEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['unit-view', 'id' => $model->unit_id]);
        } else {
            return $this->render('UnitEdit', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Unit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnitDel($id)
    {
        $model = $this->findModel($id);
        $model->softDelete();
        return $this->redirect(['unit-browse']);
    }

    public function actionManageAdmin($unit_id)
    {
        $model = $this->findModel($unit_id);

        //switch column
        if(SwitchHandler::isSwitchRequest())
        {
            if(!isset($_POST['id']) || is_null($_POST['id']) || is_null($model) ){
                return SwitchHandler::respondWithFailed();
            }
            if(SwitchHandler::isTurningOn()){
                $this->saveAdminUnit($unit_id,$_POST['id']);
            }
            else{
                 $this->deleteAdminUnit($unit_id,$_POST['id']);
            }
            return SwitchHandler::respondWithSuccess();
        }
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $_query=null);
        return $this->render('ManageAdmin',[
            'model'=>$model,
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel,
        ]);

    }

    protected function saveAdminUnit($unit_id,$user_id)
    {
        $modelUnitCharged = new UnitCharged();
        $modelUnitCharged->unit_id = $unit_id;
        $modelUnitCharged->user_id = $user_id;
        if($modelUnitCharged->save())
        {
            return true;
        }
        return false;
    }

    protected function deleteAdminUnit($unit_id,$user_id)
    {
        $modelUnitCharged = UnitCharged::find()
                            ->where(['user_id'=>$user_id])
                            ->andWhere(['unit_id'=>$unit_id])
                            ->one();
        $modelUnitCharged->delete();
    }

    /**
     * Finds the Unit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Unit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\invt\models\UnitCharged;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\WorkgroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Admin Unit';
$this->params['header'] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['unit-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['unit-view', 'id' => $model->unit_id]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper = \Yii::$app->uiHelper;
?>

<?=$uiHelper->renderContentSubHeader("Unit: " . $model->nama) ?>
<?=$uiHelper->renderLine() ?>
<?=$uiHelper->beginSingleRowBlock(['id' => 'manage-member']) ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                'email',
                ['class' => 'common\components\SwitchColumn',
                 'header' => 'Status',
                 'flag' => function ($data) use($model){
                    $query = UnitCharged::find()
                            ->where(['unit_id'=>$model->unit_id,'user_id'=>$data->user_id])
                            ->one();
                    if(!is_null($query)){
                        return true;
                    }
                    return false;
                 },
                 'contentOptions' => ['class' => 'col-xs-1']
                ],
            ],
        ]); ?>
<?=$uiHelper->endSingleRowBlock() ?>
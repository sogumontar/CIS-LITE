<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\WorkgroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Members';
$this->params['header'] = 'Manage Members';
$this->params['breadcrumbs'][] = ['label' => 'Management Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['unit-view', 'id' => $model->unit_id]];
$this->params['breadcrumbs'][] = $this->title;

$ui = \Yii::$app->uiHelper;
?>

<?=$ui->renderContentSubHeader("Unit: " . $model->name.' - '.$instansi->name) ?>
<?=$ui->renderLine() ?>
<?=$ui->beginSingleRowBlock(['id' => 'grid-unit']) ?>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'jabatan',
                ['class' => 'common\components\SwitchColumn',
                 'header' => 'Membership',
                 'flag' => function ($data) use ($model){
                    return $data->unit_id==$model->unit_id;
                 },
                 'contentOptions' => ['class' => 'col-xs-1']
                ],
            ],
        ]); ?>
<?php Pjax::end(); ?>
<?=$ui->endSingleRowBlock() ?>
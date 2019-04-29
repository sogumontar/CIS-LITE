<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\UnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
<div class="unit-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama',
                'label'=>'Nama Unit',
                'format'=>'raw',
                'value'=>function($model)
                {
                        return "<a href='".Url::toRoute(['/invt/unit/unit-view', 'id' => $model->unit_id])."'>".$model->nama."</a>";
                },
            ],
            [
                'attribute'=>'desc',
                'label'=>'Deskripsi Unit',
            ],
            [
            'class' => 'common\components\ToolsColumn',
            'template' => '{manage} {edit} {del}',
            'buttons'=>[
                'manage'=>function($url, $model)
                {
                    return ToolsColumn::renderCustomButton($url, $model, 'Manage Admin', 'fa fa-user');
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if($action === 'edit') {
                    return Url::to(['unit-edit','id'=>$key]);
                }elseif ($action === 'del') {
                    return Url::to(['unit-del','id'=>$key]);
                }
                elseif ($action==='manage') {
                    return Url::to(["manage-admin",'unit_id'=>$key]);
                }
                },
            ],
        ],
    ]); ?>

</div>

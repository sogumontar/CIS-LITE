<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inst\models\search\PejabatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Management Unit';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Management Unit';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="unit-index">

    <?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-list']);?>
    <?=$uiHelper->renderLine(); ?>
        <!--<p>
            <div class="pull-right">
                <?=$uiHelper->renderButtonSet([
                    'template' => ['add'],
                    'buttons' => [
                        'add' => ['url' => Url::toRoute(['unit-add']), 'label' => 'Tambah Unit', 'icon' => 'fa fa-plus'],
                    ]
                ]) ?>
            </div>
        </p>-->

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'inisial',
            [
                'attribute' => 'kepala_nama',
                'label' => 'Pejabat',
                'value' => 'kepala0.jabatan',
            ],
            [
                'attribute' => 'instansi_nama',
                'label' => 'Instansi',
                'value' => 'instansi.inisial',
                'filter' => ArrayHelper::map($instansi, 'inisial', 'inisial'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'headerOptions' => ['style' => 'width:15%'],
            ],
            ['class' => 'common\components\ToolsColumn',
                 'template' => '{member} {view} {edit} {del}',
                'header' => 'Aksi',
                  'buttons' => [
                    'member' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Manage Members', 'fa fa-users');
                    }
                    ],
                 'urlCreator' => function ($action, $model, $key, $index){
                    if($action === 'member'){
                        return Url::toRoute(['unit-member-add', 'unit_id' => $key, 'instansi_id' => $model->instansi_id]);
                    }else if ($action === 'view') {
                        return Url::toRoute(['unit-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['unit-edit', 'id' => $key]);
                    }else if ($action === 'del') {
                        return Url::toRoute(['unit-del', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]);
    Pjax::end() ?>

</div>

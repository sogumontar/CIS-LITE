<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\KuotaCutiTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kuota Cuti Tahunan';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Kuota Cuti Tahunan";
$uiHelper = \Yii::$app->uiHelper;
?>
<div class="kuota-cuti-tahunan-index">

    <div class="pull-right">
    <?=$uiHelper->renderButtonSet([
        'template' => ['add'],
        'buttons' => [
            'add' => ['url' => Url::toRoute(['add']), 'label' => 'Tambah Kuota Cuti Tahunan', 'icon' => 'fa fa-plus'],
        ]
    ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lama_bekerja',
            'kuota',
            'satuan',

            ['class' => 'common\components\ToolsColumn',
             'template' => '{edit} {del}',
             'urlCreator' => function ($action, $model, $key, $index){
                if ($action === 'edit') {
                    return Url::toRoute(['edit', 'id' => $key]);
                }else if ($action === 'del') {
                    return Url::toRoute(['del', 'id' => $key]);
                }
                
              }

            ],
        ],
    ]); ?>

</div>

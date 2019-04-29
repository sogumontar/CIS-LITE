<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\JenisAbsenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Absen';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Jenis Absen";
$uiHelper = \Yii::$app->uiHelper;
?>
<div class="jenis-absen-index">
    <div class="pull-right">
    <?=$uiHelper->renderButtonSet([
        'template' => ['add'],
        'buttons' => [
            'add' => ['url' => Url::toRoute(['add']), 'label' => 'Tambah Jenis Absen', 'icon' => 'fa fa-plus'],
        ]
    ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama',
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

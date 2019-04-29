<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = "Manage Cuti Pegawai";
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Manage Cuti Pegawai';

$uiHelper=\Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-list']);?>
<?=$uiHelper->renderLine(); ?>  
<div class="pegawai-index">

     <?= $uiHelper->beginContentRow() ?>
        <?= $uiHelper->beginContentBlock(['id' => 'grid-pegawai']) ?>
    
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                        'nama', 
                    [
                         'class' => 'common\components\ToolsColumn',
                         'template' => '{view}',
                            'buttons' => [
                                    'view' => function ($url, $model){
                                    return ToolsColumn::renderCustomButton($url, $model, 'Lihat detail Cuti/Izin', 'fa fa-eye');
                                },
                            ],
                        'urlCreator' => function ($action, $model, $key, $index){
                            if ($action === 'view') {
                               return Url::to(['view','id' => $model->pegawai_id]);
                            }
                        },      
                    ],
                ]
            ]);

            // Pjax::end()
            ?>
        <?= $uiHelper->endContentBlock() ?>
    <?=$uiHelper->endContentRow() ?>
</div>

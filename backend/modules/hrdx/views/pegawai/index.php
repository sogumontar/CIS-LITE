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
$this->title = "Pegawai";
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Pegawai';

$uiHelper=\Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-list']);?>
<?=$uiHelper->renderLine(); ?>  
<div class="pegawai-index">

     <?= $uiHelper->beginContentRow() ?>
        <?= $uiHelper->beginContentBlock(['id' => 'grid-pegawai']) ?>
    
            <?php
            // Pjax::begin();
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'nip',
                    'nama',
                    'alias',
                    [
                        'attribute' => 'status_aktif_pegawai_id',
                        'value' => 'statusAktifPegawai.desc'
                    ],
                    // [
                    //     'label' => 'Role',
                    //     'attribute' => 'pegawai_id',
                        
                    // ],
                    ['class' => 'common\components\ToolsColumn',
                     'header' => ' ',
                     'template' => '{add_as_dosen} {add_as_staff} {view} {update} {delete}',
                     'buttons' => [
                            'add_as_dosen' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'Tambahkan sebagai dosen', 'fa fa-shield');
                            },
                            'add_as_staff' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'Tambahkan sebagai staff', 'fa fa-shield');
                            },
                            // 'non_aktif' => function ($url, $model){
                            //     return ToolsColumn::renderCustomButton($url, $model, 'Non-aktifkan', 'fa fa-cross');
                            // },
                            'update' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'update', 'fa fa-pencil');
                            },
                            'view' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'view', 'fa fa-eye');
                            },
                            'delete' => function ($url, $model){
                                if(\Yii::$app->privilegeControl->isHasTask('HardDeleteDB')){
                                    return "<li>".Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', $url, [
                                        'title' => Yii::t('yii', 'Delete'),
                                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'data-method' => 'post',
                                         'data-pjax' => '0',
                                    ])."</li>";
                                }
                            }
                        ],
                     'urlCreator' => function ($action, $model, $key, $index){
                        if($action === 'add_as_dosen'){
                             return Url::to(['/hrdx/dosen/add','id' => $model->pegawai_id]);
                        }elseif($action === 'add_as_staff'){
                            return Url::to(['staf/add','id' => $model->pegawai_id]);
                        }
                        // elseif($action === 'non_aktif'){
                        //     return Url::to(['nonaktif','id' => $model->pegawai_id]);
                        // }
                        else if ($action === 'view') {
                            return Url::to(['view','id' => $model->pegawai_id]);
                        }else if($action === 'update') {
                            return Url::to(['edit','id' => $model->pegawai_id]);
                        }elseif(($action === 'delete')) {
                            return  Url::to(['del','id' => $model->pegawai_id]);
                        }

                      }

                    ],
                ],
            ]);

            // Pjax::end()
            ?>
        <?= $uiHelper->endContentBlock() ?>
    <?=$uiHelper->endContentRow() ?>
</div>

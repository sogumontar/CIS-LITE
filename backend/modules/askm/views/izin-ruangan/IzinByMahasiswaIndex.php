<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use backend\modules\askm\models\StatusRequest;
use backend\modules\askm\models\Lokasi;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinTambahanJamKolaboratifSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Izin';
$this->params['breadcrumbs'][] = ['label' => 'Izin Penggunaan Ruangan', 'url' => ['index-mahasiswa']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Izin Penggunaan Ruangan';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-ruangan-index">

    <!-- <?= $uiHelper->renderContentSubHeader(' Daftar '.$this->title, ['icon' => 'fa fa-list']);?>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <p>
        <?= Html::a('Request Izin', ['izin-by-mahasiswa-add'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if($model->status_request_id == 2){
                return ['class' => 'success'];
            } else if($model->status_request_id == 3){
                return ['class' => 'danger'];
            } else if($model->status_request_id == 4) {
                return ['class' => 'warning'];
            } else {
                return ['class' => 'info'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rencana_mulai',
            'rencana_berakhir',
            [
                'attribute' => 'desc',
                'label' => 'Keterangan',
                'value' => 'desc',
            ],
            [
                'attribute'=>'lokasi_id',
                'label' => 'Ruangan',
                'filter'=>ArrayHelper::map(Lokasi::find()->andWhere('deleted!=1')->asArray()->all(), 'lokasi_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'lokasi.name',
            ],
            [
                'attribute'=>'status_request_id',
                'label' => 'Status Request',
                'filter'=>ArrayHelper::map(StatusRequest::find()->asArray()->all(), 'status_request_id', 'status_request'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'statusRequest.status_request',
            ],
            
            // 'baak_id',

            ['class' => 'common\components\ToolsColumn',
                 'template' => '{view} {edit} {cancel}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'edit' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                        }
                    },
                    'cancel' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Cancel', 'fa fa-times');
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['izin-by-mahasiswa-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['izin-by-mahasiswa-edit', 'id' => $key]);
                    }
                    else if ($action === 'cancel') {
                        return Url::toRoute(['cancel-by-mahasiswa', 'id' => $key]);
                    }
                    
                }
            ],
        ],
    ]); ?>
</div>


<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Asrama;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\KamarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asrama '.$asrama->name;
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['asrama/view-detail-asrama', 'id' => $asrama->asrama_id]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kamar'];
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kamar-index">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['addKamar', 'reset'],
                'buttons' => [
                    'addKamar' => ['url' => Url::toRoute(['add-kamar', 'id_asrama' => $_GET['id_asrama']]), 'label'=> 'Tambah Kamar', 'icon'=>'fa fa-plus'],
                    'reset' => ['url' => Url::toRoute(['reset-all-kamar', 'asrama_id' => $_GET['KamarSearch']['asrama_id']]), 'label'=> 'Reset Semua Kamar', 'icon'=>'fa fa-refresh'],
                ],
                
            ]) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $uiHelper->renderLine(); ?>

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'kamar_id',
            [
                'attribute' => 'nomor_kamar',
                'label' => 'Nomor Kamar',
                'format' => 'raw',
                'value' => function($data){
                    return LinkHelper::renderLink([
                            'label' => '<strong>Kamar '.$data['nomor_kamar'].'</strong>',
                            'url' => Url::to(['view', 'id' => $data['kamar_id']]),
                        ]);
                }
            ],
            // [
            //     'attribute' => 'asrama_id',
            //     'label' => 'Asrama',
            //     'format' => 'raw',
            //     'filter'=>ArrayHelper::map(Asrama::find()->asArray()->all(), 'asrama_id', 'name'),
            //     'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            //     'value' => function($data){
            //         return LinkHelper::renderLink([
            //                 'label' => '<strong>'.$data['asrama']->name.'</strong>',
            //                 'url' => Url::to(['asrama/view-detail-asrama', 'id' => $data['asrama_id']]),
            //             ]);
            //     }
            // ],
            // [
            //     'attribute' => 'keasramaan_nama',
            //     'label' => 'Penanggung Jawab',
            //     'format' => 'raw',
            //     'value' => 'asrama.keasramaan.nama'
            // ],
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit} {reset} {del}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    // 'addMhs' => function ($url, $model){
                    //     return ToolsColumn::renderCustomButton($url, $model, 'Tambah Penghuni', 'fa fa-users');
                    // },
                    // 'editMhs' => function ($url, $model){
                    //     return ToolsColumn::renderCustomButton($url, $model, 'Edit Penghuni', 'fa fa-wrench');
                    // },
                    'reset' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Reset Penghuni', 'fa fa-refresh');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit Kamar', 'fa fa-pencil');
                    },
                    'del'=>function ($url, $model) {
                        return ToolsColumn::renderCustomButton($url, $model, 'Hapus Kamar', 'fa fa-trash');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['edit-kamar', 'id' => $key, 'id_asrama' => $_GET['id_asrama']]);
                    }else if ($action === 'reset') {
                        return Url::to(['reset-kamar', 'id' => $key, 'id_asrama' => $_GET['id_asrama']]);
                    }else if ($action === 'addMhs') {
                        return Url::toRoute(['dim-kamar/add-penghuni-kamar', 'id' => $key]);
                    }else if ($action === 'editMhs') {
                        return Url::to(['dim-kamar/edit-penghuni-kamar', 'id' => $key]);
                    }
                    else if ($action === 'del') {
                        return Url::to(['kamar/del-kamar', 'id' => $key, 'id_asrama' => $_GET['id_asrama']]);
                    }
                }
            ],
        ],
    ]); 
    Pjax::end();
    ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimKamar */

$this->title = $model->id_dim_kamar;
$this->params['breadcrumbs'][] = ['label' => 'Dim Kamars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-kamar-view">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit', 'keasramaan', 'kamar'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['edit', 'id' => $model->asrama_id]), 'label'=> 'Edit Asrama', 'icon'=>'fa fa-pencil'],
                    'keasramaan' => ['url' => Url::toRoute(['keasramaan-pegawai/add-pengurus', 'id_asrama' => $model->asrama_id]), 'label'=> 'Edit Pengurus', 'icon'=>'fa fa-users'],
                    'kamar' => ['url' => Url::toRoute(['kamar/index', 'KamarSearch[asrama_id]' => $model->asrama_id]), 'label'=> 'Daftar Kamar', 'icon'=>'fa fa-list'],
                ],
                
            ]) ?>
    </div>

    <h1>Asrama <?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_dim_kamar',
            'status_dim_kamar',
            'dim_id',
            'kamar_id',
        ],
    ]) ?>

    <br>
    <h1>Penghuni Kamar</h1>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'pegawai_id',
                'value' => 'pegawai.nama',
            ],
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{del}',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'buttons'=>[
                    'del'=>function ($url, $model) {
                        return Html::a('<i class="fa fa-times"></i> Hapus', ['keasramaan-pegawai/del', 'id' => $model->keasramaan_id], ['class'=>'btn btn-danger']);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

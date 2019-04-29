<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Asrama */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="asrama-view">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit', 'keasramaan', 'kamar'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['edit', 'id' => $model->asrama_id]), 'label'=> 'Edit Asrama', 'icon'=>'fa fa-pencil'],
                    'keasramaan' => ['url' => Url::toRoute(['keasramaan/add-pengurus', 'id_asrama' => $model->asrama_id]), 'label'=> 'Tambah Pengurus', 'icon'=>'fa fa-users'],
                    'kamar' => ['url' => Url::toRoute(['kamar/index', 'KamarSearch[asrama_id]' => $model->asrama_id]), 'label'=> 'Daftar Kamar', 'icon'=>'fa fa-list'],
                ],
                
            ]) ?>
    </div>

    <h1>Asrama <?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'asrama_id',
            'name',
            'lokasi',
            'jumlah_mahasiswa',
            'kapasitas',
            // 'keasramaan_id',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
        ],
    ]) ?>
    <br>
    <h1>Pengurus Asrama <?= $model['name']?></h1>
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
                        return Html::a('<i class="fa fa-times"></i> Hapus', ['keasramaan/del', 'id' => $model->keasramaan_id, 'id_asrama' => $_GET['id']], ['class'=>'btn btn-danger']);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<?php

/* @var $this yii\web\View */
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\assets\AppAsset;
use common\helpers\LinkHelper;
use common\components\SwitchButton;
use yii\helpers\Url;

$uiHelper = \Yii::$app->uiHelper;
?>

<div id="app-container">

    <p>
        <?= Html::a('Update', ['edit', 'id' => $model->dosen_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['del', 'id' => $model->dosen_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'nidn',
            //'dosen_id',
            'prodi_id',
            'golongan_kepangkatan_id',
            'jabatan_akademik_id',
            'status_ikatan_kerja_dosen_id',
            'gbk_id',
            'aktif_start',
            'aktif_end',
            'role_dosen_id',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            // 'pegawai_id',
        ],
    ]) ?>
</div>
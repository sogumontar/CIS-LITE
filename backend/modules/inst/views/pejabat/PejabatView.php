<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Pejabat */

$this->title = $model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'];
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <p>
        <?= Html::a('Update', ['pejabat-edit', 'id' => $model->pejabat_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'pegawai_id',
                'value' => $model->pegawai['nama']
            ],
            [
                'attribute' => 'struktur_jabatan_id',
                'value' => $model->strukturJabatan['jabatan']
            ],
            [
                'attribute' => 'status_aktif',
                'value' => $model->status_aktif==1?'Aktif':'Tidak Aktif'
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'no_sk',
            [
                'attribute' => 'file_sk',
                'format' => 'html',
                'value' => isset($model->file_sk) && $model->file_sk!==''?LinkHelper::renderLink(['options'=>'target = _blank','label'=>$model->file_sk, 'url'=>\Yii::$app->fileManager->generateUri($model->kode_file)]):'-',
            ],
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\StrukturJabatan */

$this->title = $model->jabatan;
if($otherRenderer){
    $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['inst-manager/index']];
    $this->params['breadcrumbs'][] = ['label' => $instansi_name, 'url' => ['inst-manager/strukturs?instansi_id='.$model->instansi_id]];
}else
    $this->params['breadcrumbs'][] = ['label' => 'Struktur Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper = \Yii::$app->uiHelper;
?>

<div class="struktur-jabatan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <p>
        <?= Html::a('Update', ['struktur-jabatan-edit', 'id' => $model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['struktur-jabatan/struktur-jabatan-del', 'id'=>$model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'instansi_id',
                'label' => 'Instansi',
                'value' => $model->instansi['name']
            ],
            'jabatan',
            'inisial',
            [
                'attribute' => 'parent',
                'value' => is_null($model->parent0['jabatan'])?'-':$model->parent0['jabatan'],
            ],
            [
                'attribute' => 'is_multi_tenant',
                'value' => $model->is_multi_tenant==0?'Single':'Multi Tenant',
            ],
            [
                'attribute' => 'unit_id',
                'label' => 'Unit',
                'value' => is_null($model->unit['name'])?'-':$model->unit['name'],
            ],
        ],
    ]) ?>

</div>

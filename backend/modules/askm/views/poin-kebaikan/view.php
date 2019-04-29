<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\PoinKebaikan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['dim-penilaian/index']];
$this->params['breadcrumbs'][] = ['label' => $dim, 'url' => ['dim-penilaian/view', 'id' => $model->penilaian_id]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="poin-kebaikan-view">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-pencil"></i> Edit Kebaikan', ['edit', 'id' => $_GET['id'], 'penilaian_id' => $_GET['penilaian_id']], ['class' => 'btn btn-default btn-flat btn-set btn-md']) ?>
        </p>
    </div>

    <h1>Tindakan Kebaikan</h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'name',
                'label' => 'Nama',
                'value' => $model->name,
            ],
            [
                'attribute' => 'desc',
                'label' => 'Keterangan',
                'value' => $model->desc,
            ],

        ],
    ]) ?>

</div>

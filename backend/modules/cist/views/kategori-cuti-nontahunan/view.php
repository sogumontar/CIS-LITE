<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriCutiNontahunan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Cuti Nontahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-cuti-nontahunan-view">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit', 'id' => $model->kategori_cuti_nontahunan_id], ['class' => 'btn btn-default btn-flat btn-set btn-sm']) ?>
        </p>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'lama_pelaksanaan',
            'satuan',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriIzin */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kategori Izin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-izin-view">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit', 'id' => $model->kategori_izin_id], ['class' => 'btn btn-default btn-flat btn-set btn-sm']) ?>
        </p>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'name',
                'value' => $model->name,
            ],

        ],
    ]) ?>

</div>

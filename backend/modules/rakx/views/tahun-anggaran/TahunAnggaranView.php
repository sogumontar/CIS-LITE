<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\TahunAnggaran */

$this->title = $model->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Tahun Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tahun-anggaran-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['tahun-anggaran-edit', 'id' => $model->tahun_anggaran_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tahun',
            'desc:ntext',
        ],
    ]) ?>

</div>

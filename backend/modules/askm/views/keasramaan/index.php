<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\KeasramaanPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pembina Asrama '. $asrama->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Penghuni Asrama', 'url' => ['asrama/index-all']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="keasramaan-pegawai-index">

    <?= $uiHelper->renderContentSubHeader($this->title);?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'nama_keasramaan',
                'label' => 'Nama',
                'value' => 'pegawai.nama',
            ],
            [
                'attribute' => 'no_hp',
                'label' => 'No. HP',
                'value' => 'no_hp',
            ],
            [
                'attribute' => 'telepon_keasramaan',
                'label' => 'Telepon',
                'value' => 'pegawai.telepon',
            ],
            [
                'attribute' => 'email',
                'label' => 'Email',
                'value' => 'email',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                // 'contentOptions' => ['style' => 'width: 8.7%'],
                'buttons'=>[
                    'view'=>function ($url, $model, $key) {
                        return Html::a('Lihat', ['view-only', 'id' => $key], [
                            'class' => 'btn btn-primary',]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>

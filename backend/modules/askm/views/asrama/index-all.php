<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Kamar;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Keasramaan;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\AsramaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Penghuni Asrama';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="asrama-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'dim_nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            [
                'attribute'=>'dim_prodi',
                'label' => 'Prodi',
                'filter'=>ArrayHelper::map(Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
                    return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
                }])->all(), 'ref_kbk_id', function($data){
                    if (is_null($data->jenjang_id)) {
                        return '';
                    } else{
                        return $data->kbk_ind;
                    }

                }, 'jenjangId.nama'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value'=> function($model){
                    return $model->dim->kbkId==null?null:$model->dim->kbkId->jenjangId->nama." ".$model->dim->kbkId->kbk_ind;
                },
            ],
            [
                'attribute' => 'dim_angkatan',
                'label' => 'Angkatan',
                'headerOptions' => ['style' => 'width:20px'],
                'format' => 'raw',
                'value' => 'dim.thn_masuk',
                'filter'=>ArrayHelper::map($angkatan, 'thn_masuk', 'thn_masuk'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            // [
            //     'attribute' => 'dim_dosen',
            //     'label' => 'Dosen Wali',
            //     'format' => 'raw',
            //     'value' => 'dim.registrasis.dosenWali.nama',
            // ],
            [
                'attribute'=>'dim_asrama',
                'label' => 'Asrama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'filter'=>ArrayHelper::map(Asrama::find()->andWhere('deleted!=1')->asArray()->all(), 'asrama_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'dim.dimAsrama.kamar.asrama.name',
            ],
            [
                'attribute'=>'nomor_kamar',
                'label' => 'Kamar',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'value' => 'dim.dimAsrama.kamar.nomor_kamar',
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Pembina Asrama', 'fa fa-eye');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['/askm/keasramaan/index', 'id_asrama' => $model->kamar->asrama_id]);
                    }
                }
            ],
        ],
    ]); ?>

</div>

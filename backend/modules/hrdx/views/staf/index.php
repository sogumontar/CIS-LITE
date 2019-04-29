<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;

use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\StafSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staf';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Staf';
$uiHelper=\Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-dosen',
            //'width' => 4,
        ]); ?>

    <?= $uiHelper->renderContentSubHeader('List '. $this->title, ['icon' => 'fa fa-list']);?>
    <?=$uiHelper->renderLine(); ?>
    <?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'NIP',
                'attribute' =>  'nip',
                'value' => 'pegawai.nip'
            ],
            [
                'label' => 'Nama',
                'format'=>'raw',
                'attribute' =>  'pegawai_id',
                'value'     => function ($data) {
                            return LinkHelper::renderLink(['label'=> $data->pegawai->nama, 'url'=>Url::toRoute(['view','id' => $data->staf_id])]);
                        },
            ],

            [
                'label' => 'Prodi',
                'attribute' =>  'prodi_id',
                'value' => function($data){ return isset($data->prodi->kbk_ind)?($data->prodi->jenjang->nama.'-'.$data->prodi->kbk_ind):''; }
            ],
            [
                'label' => 'Staff Role',
                'attribute' => 'staf_role_id',
                'value' => 'stafRole.nama',
                'filter' => ArrayHelper::map($staf_role, 'staf_role_id', 'nama'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            'aktif_start',
            'aktif_end',

            [
              'class' => 'common\components\ToolsColumn',
              'template' =>'{assign_as_ta} {view} {edit}',
              'buttons' => [
                    'assign_as_ta' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Tambahkan sebagai TA', 'fa fa-shield');
                    },
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'view', 'fa fa-shield');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'edit', 'fa fa-shield');
                    },
                ],

              'urlCreator' => function ($action, $model, $key, $index){
                if ($action === 'assign_as_ta') {
                    return Url::to(['assign','id' => $model->staf_id]);
                }else if ($action === 'view') {
                    return Url::to(['view','id' => $model->staf_id]);
                }else if($action === 'edit') {
                    return Url::to(['edit','id' => $model->staf_id]);
                }
              }
            ],
        ],
    ]); ?>
     <?= $uiHelper->endContentBlock(); ?>

<?= $uiHelper->endContentRow() ?>
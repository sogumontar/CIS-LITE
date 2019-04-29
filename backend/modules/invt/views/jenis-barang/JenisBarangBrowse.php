<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Barang';
$this->params['breadcrumbs'][] = ['label'=>'Data Referensi', 'url'=>Url::to(['/invt/#'])];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="jenis-barang-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Jenis Barang',
                'attribute'=>'nama',
                'format'=>'raw',
                'value'=>function ($model){
                            return "<a href='".Url::toRoute(['/invt/jenis-barang/jenis-barang-view', 'id' => $model->jenis_barang_id])."'>".$model->nama."</a>";
                    }
            ],
            [
                'label'=>'Deskripsi',
                'attribute'=>'desc',
            ],
            [
                'class' => 'common\components\ToolsColumn',
                'template' => '{edit} {del}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action === 'edit') {
                        return Url::to(['jenis-barang-edit','id'=>$key]);
                    }elseif ($action === 'del') {
                        return Url::to(['jenis-barang-del','id'=>$key]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>

</div>

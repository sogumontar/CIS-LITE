<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategori';
$this->params['breadcrumbs'][] = ['label'=>'Data Referensi', 'url'=>Url::to(['/invt/#'])];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="kategori-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Kategori',
                'attribute'=>'nama',
                'format'=>'raw',
                'value'=>function ($model){
                            return "<a href='".Url::toRoute(['/invt/kategori/kategori-view', 'id' => $model->kategori_id])."'>".$model->nama."</a>";
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
                        return Url::to(['kategori-edit','id'=>$key]);
                    }elseif ($action === 'del') {
                        return Url::to(['kategori-del','id'=>$key]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>

</div>

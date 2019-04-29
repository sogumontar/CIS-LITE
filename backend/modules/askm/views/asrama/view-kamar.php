<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use backend\modules\askm\models\Asrama;
use common\helpers\LinkHelper;
use yii\widgets\Pjax;


$this->title = "List Kamar Asrama ".$model->asrama['name'];
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;

?>

<?php Pjax::begin(); ?>
<?=GridView::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        // Simple columns defined by the data contained in $dataProvider.
        // Data from the model's column will be used.
		[
                'attribute' => 'nomor_kamar',
                'label' => 'Nomor Kamar',
                'format' => 'raw',
                'value' => function($data){
                    return LinkHelper::renderLink([
                            'label' => '<p>Kamar '.$data['nomor_kamar'].'</p>',
                            'url' => Url::to(['view', 'id' => $data['kamar_id']]),
                        ]);
                }
            ],
            [
                'attribute' => 'asrama_id',
                'label' => 'Asrama',
                'format' => 'raw',
                'filter'=>ArrayHelper::map(Asrama::find()->asArray()->all(), 'asrama_id', 'name'),
                'value' => function($data){
                    return LinkHelper::renderLink([
                            'label' => '<p>'.$data['asrama']->name.'</p>',
                            'url' => Url::to(['asrama/view', 'id' => $data['asrama_id']]),
                        ]);
                }
            ],
			
			
			
        // More complex one.
        // [
            // 'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            // 'value' => function ($data) {
                // return $data->name; // $data['name'] for array data, e.g. using SqlDataProvider.
            // },
        // ],
    ],
]);
?>
<?php  Pjax::end(); ?>


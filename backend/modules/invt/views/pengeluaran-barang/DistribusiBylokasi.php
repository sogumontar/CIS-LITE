<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use common\components\ToolsColumn;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\Lokasi;
use yii\widgets\Pjax;

$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Inventori';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = 'Distribusi by Lokasi';
$this->params['header'] = "Distribusi barang berdasarkan lokasi";
?>
<?= $uiHelper->beginSingleRowBlock(['id' => 'distribusi-bylokasi',]) ?>
    <div class="table-detail">
        <table width="50" class="table table-bordered">
            <tbody>
                <tr>
                    <th class="col-md-2">Unit</th>
                    <td><?php echo Unit::getNamaUnit($_GET['unit_id'])?></td>
                </tr>
                <tr>
                    <th class="col-md-2">Lokasi Tujuan</th>
                    <td><?php echo Lokasi::getNamaLokasi($_GET['lokasi_id'])?></td>
                </tr>
            </tbody>
        </table>
    </div>
<p>Silahkan isi kode inventori dan jumlah barang yang akan didistribusikan serta tanggal pendistribusian.</p>
<?=$uiHelper->renderLine()?>

<?=$this->render('_search',['searchModel'=>$searchModel]);?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-sm-4',
            'wrapper' => 'col-sm-5',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel'=>$searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama_barang',
                'label'=>'Nama Barang',
                'format'=>'raw',
                'value'=>function ($model){
                        return "<a href='".Url::toRoute(['/invt/barang/barang-view', 'barang_id' => $model->barang_id])."'>".$model->nama_barang."</a>";
                },
                'contentOptions'=>['class'=>'col-xs-4',],
            ],
            [
                'label'=>'Total Jumlah',
                'attribute'=>'jumlah',
                'value'=>'jumlah',
                'contentOptions'=>['class'=>'col-xs-1']
            ],
            [
                'label'=>'Jumlah Gudang',
                'value'=>function($data){
                    return $data->summaryJumlah==null?0:$data->summaryJumlah->jumlah_gudang;
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
            [
                'label'=>"Jumlah",
                'filter'=>false,
                'format'=>'raw',
                'value'=>function($data){
                        return Html::textInput('jumlah['.$data->barang_id.']',null,['style'=>"width: 4em",'type'=>'number','min'=>1,'max'=>$data->summaryJumlah==null?$data->jumlah:$data->summaryJumlah->jumlah_gudang]); //validasi dibagin ini jika summary jumlah sudah ada namun yng tidak terisi jumlah gudang
                }
            ],
            [
                'label'=>'Prefiks Kode Inventori',
                'filter'=>false,
                'format'=>'raw',
                'value'=>function($data){
                        return Html::textInput('kode_inventori['.$data->barang_id.']',null,['size'=>60,]);
                }
            ],
        ],
    ]); 
    ?>
    <?= $form->field($modelBarangKeluarForm, 'tgl_keluar',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-3',],
            'inputTemplate' => '<div class="input-group"><span class="input-group-addon">
                                <span class="   glyphicon glyphicon-calendar"></span>
                                </span>{input}</div>',
                ])->widget(DatePicker::className(),
                [
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-25:date('Y')",
                        ],
                    'options'=>['size'=>27,'changeMonth'=>'true','class'=>'form-control']
    ])->label('Tanggal Distribusi')
    ?>
    
    <?= $form->field($modelBarangKeluarForm, 'keterangan',[
            'horizontalCssClasses'=>['wrapper'=>'col-sm-6'],
    ])->textarea(['rows'=>6])
    ?>

    <br>
    <?=$uiHelper->beginContentBlock(['id'=>'grid-system1', 'width'=>12]) ?>
        <div class="form-group">
            <div class='col-sm-offset-6 col-sm-4'></div>
                <?= Html::submitButton('Distribusi', ['class' => 'btn btn-success']) ?>
        </div>
    <?=$uiHelper->endContentBlock() ?>
<?php ActiveForm::end(); ?>
<?=$uiHelper->endSingleRowBlock()?>
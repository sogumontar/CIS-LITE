<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
 $uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Brand */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Cek Pengembalian Barang';
$this->params['breadcrumbs'][] = ['label'=>'Peminjaman Barang', 'url'=>'pinjam-barang-browse-byadmin'];
$this->params['breadcrumbs'][] = ['label'=>'Detail Peminjaman Barang', 'url'=>['pinjam-barang-view','id'=>$model->peminjaman_barang_id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="kembali-barang-form">
<table class="table table-bordered">
            <tbody>
                <tr>
                    <th class="col-md-3">Nama Peminjam </th>
                    <td><?= $model->getDetailNama($model->oleh) ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Unit Inventori</th>
                    <td><?php echo $model->unit->nama ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Pinjam</th>
                    <td><?=Yii::$app->formatter->asDate($model->tgl_pinjam, 'long')?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Tanggal Kembali</th>
                    <td><?=Yii::$app->formatter->asDate($model->tgl_kembali, 'long')?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Status Approval</th>
                    <td><?= $_statusPinjam; ?></td>
                </tr>
                <tr>
                    <th class="col-md-3">Detail Barang</th>
                    <td>
                        <table class="table table-condensed" width="50">
                        <thead>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Jumlah Pinjam</th>
                        </thead>
                        <?php
                            $i=1;
                            foreach ($listBarang as $key => $barang) {
                        ?>
                                <tr>
                                    <td><?=$i;?></td>
                                    <td><?=$barang->barang->nama_barang;?></td>
                                    <td><?=$barang->barang->jenisBarang->nama?></td>
                                    <td><?=$barang->jumlah;?></td>
                                </tr>
                        <?php    
                                $i++;
                            }
                        ?>
                        </table>
                    </td>
                </tr>
            </tbody>
</table>
<p>Silahkan isi tanggal realisasi pengembalian barang dan pengecekan status barang kembali.</p><br>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label'=>'col-md-3',
            'offset' => 'col-md-offset-10',
            // 'wrapper' => 'col-md-1',
            'error' => '',
            'hint' => '',
        ],
    ],
]); ?>
    
    <?= $form->field($modelKembali, 'tgl_realisasi_kembali',[
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
    ])->label('Tanggal Realisasi Kembali')
    ?>
    
    <?= $form->field($modelKembali,'cek_status_barang')
            ->label(true)
            ->inline()
            ->radioList($_statusBarang)
            ->label("Status Barang Kembali");
    ?>
    <!-- Cek Barang -->
    <div class="cek" style="display:none">
    <?=$uiHelper->beginContentBlock(['id'=>'cek-barang',
            'width'=>11,
        ]) ?>
    <?=GridView::widget([
               'dataProvider'=>$dataProvider,
                'rowOptions' => function ($model, $index, $widget, $grid){
                    return ['height'=>'5'];
                },
                'columns'=>[
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'=>'Nama Barang',
                        'format'=>'raw',
                        'value'=>function($model){
                            return "<a href='".Url::toRoute(['/invt/barang/barang-view','barang_id'=>$model->barang_id])."'>".$model->barang->nama_barang."</a>";
                            }
                    ],
                    'barang.jenisBarang.nama',
                    [
                        'label'=>'Jumlah Pinjam',
                        'attribute'=>'jumlah',
                    ],
                    [
                        'label'=>"Cek Jumlah Rusak",
                        'filter'=>false,
                        'format'=>'raw',
                        'value'=>function($model){
                                return Html::textInput('cek_jumlah_rusak['.$model->barang_id.']',null,['style'=>'width:5em','type'=>'number','min'=>0,'max'=>$model->jumlah]);
                        }
                    ],
                ]
        ]);
    ?>
    <?=$uiHelper->endContentBlock() ?>
    </div>

<?=$uiHelper->beginContentRow() ?>
    <?=$uiHelper->beginContentBlock(['id'=>'grid-system2',
            'width'=>10,
    ]) ?>
    <div class="form-group">
        <div class='col-md-offset-4 col-sm-1'></div>
            <?= Html::submitButton('Kembali', ['class' =>'btn btn-primary']) ?>
    </div>
    <?=$uiHelper->endContentBlock() ?>
<?=$uiHelper->endContentRow() ?>

<!-- Javascript -->
<?php
    $this->registerJs(
        "$('input').on('ifChecked', function(event){
            if($(this).attr('value')=='rusak'){
                $('.cek').show();
            }
            if($(this).attr('value')=='bagus'){
                $('.cek').hide();
            }
        });
        ",
        View::POS_END);
?>

<?php ActiveForm::end(); ?>

</div>

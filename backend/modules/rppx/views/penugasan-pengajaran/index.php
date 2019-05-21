
<?php

use backend\modules\rppx\models\Kuliah;
use backend\modules\rppx\models\HrdxPegawai;
use backend\modules\rppx\models\Staf;
use backend\modules\rppx\models\PenugasanPengajaran;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\rppx\assets\AppAsset;
AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rppx\models\search\PenugasanPengajaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Penugasan Pengajaran';
$this->params['breadcrumbs'][] = $this->title;
$this->params['layout'] = 'full';
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<div class="penugasan-pengajaran-index">
    <!-- <script type="text/javascript" src="chartjs/Chart.js"></script> -->

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Penugasan Pengajaran', ['menu'], ['class' => 'btn btn-success']) ?>
    </p>
     <p  align="right">Convert Data ke<a href="penugasan-pengajaran/convert"> Excel</a></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           

            // 'penugasan_pengajaran_id',
            'pengajaran_id',
            'pegawai_id',
            'role_pengajar_id',
            // 'is_fulltime:datetime',
            // 'start_date',
            // 'end_date',
            // 'deleted',
            // 'deleted_by',
            // 'deleted_at',
            // 'created_at',
            // 'created_by',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<div>
</div>
    <div style="width: 800px;margin: 0px auto;">
        <p></p>
        <?php echo "asd"; ?>
        <canvas id="myChart"></canvas>
    </div>
    <?php 
        $dataBar=HrdxPegawai::find()->all();
    ?>
    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            <?php $a=array('asd','asd'); ?>
            data: {
                labels: [<?php foreach ($dataBar as $key) { echo $key['pegawai_id'];?>,<?php }?>],
                datasets: [{
                    label: '',
                    data: [
                    <?php foreach($dataBar as $dat){ ?>
                        <?php 
                        $jumlah_laki = 5;
                        $query = (new \yii\db\Query())->from('rppx_penugasan_pengajaran');
                        $sum = $query->sum('pegawai_id');
                        // echo $sum;
                        echo ($dat['ref_kbk_id']);
                        ?>,
                        
                    <?php } ?>
                    ],
                    backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(524, 12, 235, 0.2)',
                    'rgba(534, 20, 235, 0.2)',
                    'rgba(554, 192, 235, 0.2)',
                    'rgba(154, 62, 235, 0.2)',
                    'rgba(5, 12, 42, 0.2)',
                    'rgba(74, 162, 235, 0.2)',
                    'rgba(23, 162, 235, 0.2)',
                    'rgba(123, 162, 235, 0.2)',
                    'rgba(672, 162, 235, 0.2)',
                    'rgba(313, 162, 235, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(101, 162, 235, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(72, 162, 235, 0.2)',
                    'rgba(12, 162, 235, 0.2)',
                    'rgba(442, 162, 235, 0.2)',
                    'rgba(541, 162, 235, 0.2)',
                    'rgba(122, 162, 235, 0.2)',
                    'rgba(421, 162, 235, 0.2)',
                    'rgba(223, 162, 235, 0.2)',
                    'rgba(122, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(155, 55, 235, 0.2)'
                    ],
                    borderColor: [
                'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(524, 12, 235, 0.2)',
                    'rgba(534, 20, 235, 0.2)',
                    'rgba(554, 192, 235, 0.2)',
                    'rgba(154, 62, 235, 0.2)',
                    'rgba(5, 12, 42, 0.2)',
                    'rgba(74, 162, 235, 0.2)',
                    'rgba(23, 162, 235, 0.2)',
                    'rgba(123, 162, 235, 0.2)',
                    'rgba(672, 162, 235, 0.2)',
                    'rgba(313, 162, 235, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(101, 162, 235, 0.2)',
                    'rgba(90, 162, 235, 0.2)',
                    'rgba(72, 162, 235, 0.2)',
                    'rgba(12, 162, 235, 0.2)',
                    'rgba(442, 162, 235, 0.2)',
                    'rgba(541, 162, 235, 0.2)',
                    'rgba(122, 162, 235, 0.2)',
                    'rgba(421, 162, 235, 0.2)',
                    'rgba(223, 162, 235, 0.2)',
                    'rgba(122, 162, 235, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(155, 55, 235, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
    
   
</div>

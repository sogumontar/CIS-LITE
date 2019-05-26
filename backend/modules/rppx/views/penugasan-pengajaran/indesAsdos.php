
<?php
use yii\helpers\Url;
use backend\modules\rppx\models\Kuliah;
use backend\modules\rppx\models\HrdxPegawai;
use backend\modules\rppx\models\Staf;
use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\PenugasanPengajaranAsdos;
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
        <?= Html::a('New', ['menuasdos'], ['class' => 'btn btn-success']) ?>
    </p>
     <p  align="right">Convert Data ke<a href="penugasan-pengajaran/convert"> Excel</a></p>
     <a href="index" class="fa fa-female"><button class="btn btn-primary"> Dosen</button></a>
    <table>
        <b><hr></b>
        <tr >
            <th style="width: 100px;">Mata Kuliah</th>
            <th  style="width: 150px; align-self: center;">Nama Dosen</th>
            <th style="width: 50px;">Load</th>
            <th  style="width: 150px; align-self: center;">Dosen 2</th>
            <th  style="width: 50px; ">Load </th>
            <th  style="width: 150px; align-self: center;">Dosen 3</th>
            <th style="width: 50px; ">Load </th>
            <th style="width: 100px; ">Tanggal Request </th>
        </tr>
        
            <?php
            $id_login=1;
            $variable=PenugasanPengajaranAsdos::find()->where('request_by='.$id_login    )->all();
                foreach ($variable as $key ) {
                    echo $key['asdos2'];
                    $var=Staf::find()->where('staf_id='.$key['asdos1'])->all();
                    $varDos2=Staf::find()->where('staf_id='.$key['asdos2'])->all();
                    $varDos3=Staf::find()->where('staf_id='.$key['asdos3'])->all();
                    $varMatkul=Kuliah::find()->where('kuliah_id='.$key['pengajaran_id'])->all();
                    foreach ($varMatkul as $ket) {
                     ?>
                     <tr>
                        <td><p><?php echo $ket['nama_kul_ind']; ?></p></td>
                     <?php    
                    }
                    foreach ($var as $ket) {
                        $nama=HrdxPegawai::find()->where('pegawai_id='.$ket['pegawai_id'])->all();
                        foreach ($nama as $keys ) {
                            
                        
                     ?>
                    <td><p><?php echo $keys['nama']; ?></p></td>
                     <?php 
                        }
                    } ?>
                     <td><p><?php echo $key['load1']; ?></p></td>
                    <?php 
                    foreach ($varDos2 as $ket) {
                        $nama=HrdxPegawai::find()->where('pegawai_id='.$ket['pegawai_id'])->all();
                        foreach ($nama as $keys) {
                            
                        
                     ?>
                    <td><p><?php echo $keys['nama']; ?></p></td>
                     <?php } }?>
                    <td><p><?php echo $key['load2']; ?></p></td>
                    <?php
                    foreach ($varDos3 as $ket) {
                        $nama=HrdxPegawai::find()->where('pegawai_id='.$ket['pegawai_id'])->all();
                        foreach ($nama as $keys) {
                            
                        
                     ?>
                    <td><p><?php echo $keys['nama']; ?></p></td>
                     <?php } } ?>
                     <td><p><?php echo $key['load3']; ?></p></td>
                     <td><p><?php echo $key['created_at']; ?></p></td>
                     <td><a class="fa fa-book" href="<?=Url::toRoute(['update','id'=>$key['penugasan_pengajaran_id']]) ?>" ></a></td>
                     <td><a onclick="return confirm('Anda Yakin Ingin Menghapus Request?');" href="<?=Url::toRoute(['insret','id'=>$key['penugasan_pengajaran_id']]) ?>"  class="fa fa-close"></a></td>
                     <?php 

                }
             ?>
    </table>

<div>
</div>
    <div style="width: 800px;margin: 0px auto;">
        <p></p>
        <?php echo "asd"; ?>
        <canvas id="myChart"></canvas>
    </div>
    <?php 
        $dataBar=Staf::find()->all();
    ?>
</div>

<script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
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
                        echo ($dat['pegawai_id']);
                        ?>,
                        
                    <?php } ?>
                    ],
                    backgroundColor: [
                    'rgba(1, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(524, 12, 235, 0.8)',
                    'rgba(534, 20, 235, 0.8)',
                    'rgba(554, 192, 235, 0.8)',
                    'rgba(154, 62, 235, 0.8)',
                    'rgba(5, 12, 42, 0.8)',
                    'rgba(74, 162, 235, 0.8)',
                    'rgba(23, 162, 235, 0.8)',
                    'rgba(123, 162, 235, 0.8)',
                    'rgba(672, 162, 235, 0.8)',
                    'rgba(313, 162, 235, 0.8)',
                    'rgba(90, 162, 235, 0.8)',
                    'rgba(101, 162, 235, 0.8)',
                    'rgba(90, 162, 235, 0.8)',
                    'rgba(72, 162, 235, 0.8)',
                    'rgba(12, 162, 235, 0.8)',
                    'rgba(442, 162, 235, 0.8)',
                    'rgba(541, 162, 235, 0.8)',
                    'rgba(122, 162, 235, 0.8)',
                    'rgba(421, 162, 235, 0.8)',
                    'rgba(223, 162, 235, 0.8)',
                    'rgba(122, 162, 235, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(155, 55, 235, 0.8)'
                    ],
                    borderColor: [
                'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(524, 12, 235, 0.8)',
                    'rgba(534, 20, 235, 0.8)',
                    'rgba(554, 192, 235, 0.8)',
                    'rgba(154, 62, 235, 0.8)',
                    'rgba(5, 12, 42, 0.8)',
                    'rgba(74, 162, 235, 0.8)',
                    'rgba(23, 162, 235, 0.8)',
                    'rgba(123, 162, 235, 0.8)',
                    'rgba(672, 162, 235, 0.8)',
                    'rgba(313, 162, 235, 0.8)',
                    'rgba(90, 162, 235, 0.8)',
                    'rgba(101, 162, 235, 0.8)',
                    'rgba(90, 162, 235, 0.8)',
                    'rgba(72, 162, 235, 0.8)',
                    'rgba(12, 162, 235, 0.8)',
                    'rgba(442, 162, 235, 0.8)',
                    'rgba(541, 162, 235, 0.8)',
                    'rgba(122, 162, 235, 0.8)',
                    'rgba(421, 162, 235, 0.8)',
                    'rgba(223, 162, 235, 0.8)',
                    'rgba(122, 162, 235, 0.8)',
                    'rgba(54, 162, 235, 0.8 )',
                    'rgba(155, 55, 235, 0.8)'
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
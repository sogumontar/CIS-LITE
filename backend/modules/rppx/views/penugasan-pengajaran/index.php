
<?php
use yii\helpers\Url;
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
<script type="text/javascript">

    function getLast(){
        alert("asd");
        document.write("asd");
        var year=today.getFullYear();
        <?php 
            // echo "id";
            // $PenugasanTahunLalu=PenugasanPengajaran::find()->where('pengajaran_id=1')->all();
            // $gg=0;
            // foreach ($PenugasanTahunLalu as $key ) {
                // if(substr($key['created_at'],0,4)==".'year'."){
                    // echo $key['penugasan_pengajaran_id'];
                    // echo "<br>";
                // }
            // }
            

        ?>
    }
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<div class="penugasan-pengajaran-index">
    <!-- <script type="text/javascript" src="chartjs/Chart.js"></script> -->

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Penugasan Pengajaran', ['kelas'], ['class' => 'btn btn-success']) ?>
    </p>
     <p  align="right">Convert Data ke<a href="penugasan-pengajaran/convert" class="btn btn-success"> Excel</a></p>
     <!-- <a onclick='getLast()'><button class="btn btn-primary">Assisten Dosen</button></a> -->
     <!-- <a href="indexasdos" ><button class="btn btn-primary">Assisten Dosen</button></a> -->
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
            $variable=PenugasanPengajaran::find()->where('request_by='.$id_login    )->all();
                foreach ($variable as $key ) {
                    $var=HrdxPegawai::find()->where('pegawai_id='.$key['pegawai_id'])->all();
                    $varDos2=HrdxPegawai::find()->where('pegawai_id='.$key['role_pengajar_id'])->all();
                    $varDos3=HrdxPegawai::find()->where('pegawai_id='.$key['role_pengajar_id3'])->all();
                    $varMatkul=Kuliah::find()->where('kuliah_id='.$key['pengajaran_id'])->all();
                    foreach ($varMatkul as $ket) {
                     ?>
                     <tr>
                        <td><p><?php echo $ket['nama_kul_ind']; ?></p></td>
                    
                     <?php    

                        
                    }

                    foreach ($var as $ket) {
                     ?>
                    <td><p><?php echo $ket['nama']; ?></p></td>
                     <?php  } ?>
                     <td><p><?php echo $key['load']; ?></p></td>
                    <?php 
                    foreach ($varDos2 as $ket) {
                     ?>
                    <td><p><?php echo $ket['nama']; ?></p></td>
                     <?php }?>
                    <td><p><?php echo $key['load2']; ?></p></td>
                    <?php
                    foreach ($varDos3 as $ket) {
                     ?>
                    <td><p><?php echo $ket['nama']; ?></p></td>
                     <?php } ?>
                     <td><p><?php echo $key['load3']; ?></p></td>
                     <td><p><?php echo $key['created_at']; ?></p></td>
                     <td><a class="fa fa-book" href="<?=Url::toRoute(['update','id'=>$key['penugasan_pengajaran_id']]) ?>" ></a></td>
                     <td><a onclick="return confirm('Anda Yakin Ingin Menghapus Request?');" href="<?=Url::toRoute(['insret','id'=>$key['penugasan_pengajaran_id']]) ?>"  class="fa fa-close"></a>
                     </td>
                 </tr>
                     <?php 

                }
             ?>
             <hr>
    </table>

<div>
    </div>
      <div style="width: 800px;margin: 0px auto;">
        <p></p>
        <canvas id="myChart"></canvas>
    </div>
    <?php 
        $dataBar=HrdxPegawai::find()->all();
    ?>
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
                        $query = PenugasanPengajaran::find()->where('pegawai_id = '.$dat['pegawai_id'])->all();
                       
                        $sumload = 0;
                        foreach($query as $lo){
                            $sumload = $sumload + $lo['load']+$lo['load2']+$lo['load3'];
                        }

                        echo $sumload;
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
    <div class="row">
        <div class="col" style="padding-left: 15px;">
            <center>
            <table border="1" style="margin-top: 50px; width: 700px;">
                <tr><?php $i=0;  
                    if($i<12){ ?>
                    <td>
                        <table style="border-right: 1;border-color: #000;margin-left: 30px;">
                            <?php foreach($dataBar as $datas){
                                $i++;
                                $pen = PenugasanPengajaran::find()->where('pegawai_id = '.$datas['pegawai_id'])->all();
                            ?>
                            <tr>
                                <td></td>
                                <td><p style="margin-left: 5px;margin-top: 10px;margin-bottom: 10px;margin-right: 5px;"><?php echo $datas['pegawai_id'];?>: <?= $datas['nama']; ?></p></td>
                                <td>( <?php $sumloads=0 ;foreach($pen as $pen){$sumloads=$sumloads+$pen['load']+$pen['load2']+$pen['load3'];} echo $sumloads; ?> )</td>
                                <?php if($i==12){break;} ?>
                            </tr>
                        <?php } ?>
            </table>            
                    </td>
                <?php }

                 ?>
                 <td>
                     <table style="border-right: 1;border-color: #000;margin-top: 37px;margin-left: 30px;">
                            <?php foreach($dataBar as $datas){
                                $i++;
                                if($datas['pegawai_id']>11){
                                $pen = PenugasanPengajaran::find()->where('pegawai_id = '.$datas['pegawai_id'])->all();
                            ?>
                            <tr>
                                <td></td>
                                <td><p style="margin-left: 5px;margin-top: 10px;margin-bottom: 10px;margin-right: 5px;"><?php echo $datas['pegawai_id'];?>: <?= $datas['nama']; ?></p></td>
                                <td>( <?php $sumloads=0 ;foreach($pen as $pen){$sumloads=$sumloads+$pen['load']+$pen['load2']+$pen['load3'];} echo $sumloads; ?> )</td>
                            </tr>
                        <?php }} ?>
            </table>
                 </td>
                </tr>
            </table>
            </center>
        </div>
    </div>
</div>

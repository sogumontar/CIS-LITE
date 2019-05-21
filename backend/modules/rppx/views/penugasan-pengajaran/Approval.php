<?php 
    use backend\modules\rppx\models\Kuliah;
    use backend\modules\rppx\models\HrdxPegawai;
    use backend\modules\rppx\models\Staf;
    use backend\modules\rppx\models\PenugasanPengajaran;
    use yii\helpers\Html;
    use yii\grid\GridView;
?>
<div class="rppx-default-index">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br>"; ?></p>
    <div class="container">
        <div class="col-md-7">
            <h2>Request From ...............</h2>
        </div>
        <div class="col-md-2" >
            <br>
            Date:....<br><br><br>
        </div>
        <
    </div>

    <div class="container">
        <div class="col-md-12">
            <table >
                <tr>
                    <td width="150px" align="center">Nama Mata Kuliah  <br><br>  </td>
                    <td width="150px" align="center">Jumlah Kelas Riil <br><br></td>
                    <td width="150px" align="center">Jumlah Tatap Muka <br><br></td>
                    <td width="150px" align="center">Dosen 1 <br><br></td>
                    <td width="150px" align="center">Load <br><br></td>
                    <td width="150px" align="center">Dosen 2 <br><br></td>
                    <td width="150px" align="center">Load <br><br></td>
                    <td width="150px" align="center">Dosen s3 <br><br></td>
                    <td width="150px" align="center">Load <br><br></td>
                </tr>
                <?php
                    $data=PenugasanPengajaran::find()->where('approved=1')->all();
                    foreach ($data as $test) {
                        echo $test['pengajaran_id'];
                        $dat=Kuliah::find()->where('kuliah_id='.$test['pengajaran_id'])->all();
                        
                        ?>
                   
                <tr><?php
                    foreach ($dat as $key) {
                            
                        
                    ?>
                    <td width="150px" align="center"><?php echo $key['nama_kul_ind']; ?></td>
                    <?php }?>
                    <td width="150px" align="center"><?php echo $test['jumlah_kelas_riil']; ?></td>
                    <td width="150px" align="center"><?php echo $test['kelas_tatap_muka']; ?></td>
                    <?php 
                        $pengajar=HrdxPegawai::find()->where('pegawai_id='.$test['pegawai_id'])->all();
                     foreach ($pengajar as $key ) {?>
                        <td width="150px" align="center"><?php echo $key['nama']; ?></td>
                    <?php } ?>
                    <td width="150px" align="center"><?php echo $test['load']; ?></td>
                    <?php 
                    echo $test['role_pengajar_id'];
                     $pengajar=HrdxPegawai::find()->where('pegawai_id='.$test['role_pengajar_id'])->all();
                       
                     foreach ($pengajar as $key ) {?>
                        <td width="150px" align="center"><?php echo $key['nama']; ?></td>
                    <?php } ?>
                    <td width="150px" align="center"><?php echo $test['load2']; ?></td>
                </tr>
                <tr>
                    <td width="150px" align="right" style="margin-left: 250px;" colspan="3"></td>
                    <td align="center"><br> <button class="btn btn-primary">Approve</button></td>
                </tr>
                 <?php 
                }
                ?>
            </table>
        </div>
    </div>
</div>

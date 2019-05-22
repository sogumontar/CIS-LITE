        <?php 
            use backend\modules\rppx\models\Kuliah;
            use backend\modules\rppx\models\HrdxPegawai;
            use backend\modules\rppx\models\Staf;
            use backend\modules\rppx\models\PenugasanPengajaran;
            use yii\helpers\Html;
            use yii\grid\GridView;
            use yii\helpers\Url;
        ?>
        <div class="rppx-default-index">
            <?php 
            $d=date("D , d  M  Y");
                // $date=date("D/M/Y");
            ?>
            <p><?php echo $d."<br><br>"; ?></p>
            <div class="container">
                <div class="col-md-7">
                    <h2>Request Penugasan Pengajaran</h2>
                </div>
                
            </div>

            <div class="container">
                <div class="col-md-9">
                    <table >
                        <tr>
                            <td width="150px" align="center">Nama Mata Kuliah  <br><br>  </td>
                            <td width="150px" align="center">Jumlah Kelas Riil <br><br></td>
                            <td width="150px" align="center">Jumlah Tatap Muka <br><br></td>
                            <td width="150px" align="center">Dosen 1 <br><br></td>
                            <td width="150px" align="center">Load <br><br></td>
                            <td width="150px" align="center">Dosen 2 <br><br></td>
                            <td width="150px" align="center">Load <br><br></td>
                            <td width="150px" align="center">Dosen 3 <br><br></td>
                            <td width="150px" align="center">Load <br><br></td>
                            <td width="150px" align="center">Request Oleh <br><br></td>
                        </tr>
                        <br> <br>
                        <?php
                            $data=PenugasanPengajaran::find()->where('approved=0 AND gbk_approved=1')->all();
                            foreach ($data as $test) {
                                $dat=Kuliah::find()->where('kuliah_id='.$test['pengajaran_id'])->all();
                                
                                ?>
                           
                        <tr><?php
                            foreach ($dat as $key) {
                                    
                                
                            ?>
                            <td width="150px"  align="center"><?php echo $key['nama_kul_ind']; ?></td>
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
                             $pengajar=HrdxPegawai::find()->where('pegawai_id='.$test['role_pengajar_id'])->all();
                               
                             foreach ($pengajar as $key ) {?>
                                <td width="150px" align="center"><?php echo $key['nama']; ?></td>
                            <?php } ?>
                            <td width="150px" align="center"><?php echo $test['load2']; ?></td>
                            <?php 
                             $pengajar=HrdxPegawai::find()->where('pegawai_id='.$test['role_pengajar_id3'])->all();
                               
                             foreach ($pengajar as $key ) {?>
                                <td width="150px" align="center"><?php echo $key['nama']; ?></td>
                            <?php } ?>
                            <td width="150px" align="center"><?php echo $test['load3']; ?></td>
                             <?php 
                             $pengajar=HrdxPegawai::find()->where('pegawai_id='.$test['request_by'])->all();
                               
                             foreach ($pengajar as $key ) {?>
                                <td width="150px" align="center"><?php echo $key['nama']; ?></td>
                            <?php } ?>
                            <td align="center"><br><a href="<?=Url::toRoute(['approver','idAkun'=>$test['penugasan_pengajaran_id']]) ?>" class="fa fa-check"></a></td>
                            <td align="center"><br><a href="" class="fa fa-close"></a></td>
                        </tr>
                        <tr>
                            <td width="150px" align="right" style="margin-left: 250px;" colspan="3"></td>
                            
                        </tr>
                         <?php 
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

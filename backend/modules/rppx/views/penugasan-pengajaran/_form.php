<?php

    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;

    use backend\modules\rppx\models\Kuliah;
    use backend\modules\rppx\models\HrdxPegawai;
    use backend\modules\rppx\models\Staf;

$ind = 0;
$que = HrdxPegawai::find()->all();

use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\AdakPengajaran;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */
/* @var $form yii\widgets\ActiveForm */
$view = ArrayHelper::map(Kuliah::find()->all(), 'kode_mk', 'nama_kul_ind');
$vieww = ArrayHelper::map(AdakPengajaran::find()->all(), 'pengajaran_id', 'kuliah_id');
$pega=HrdxPegawai::find() ->all();
$viewPengajar = ArrayHelper::map(HrdxPegawai::find()->where('pegawai_id!=0')->all(), 'pegawai_id', 'nama');
$viewPengajar2 = ArrayHelper::map(Kuliah::find()->where('stat_created=0')->all(), 'kuliah_id', 'nama_kul_ind');
$viewPengajarr = ArrayHelper::map(HrdxPegawai::find()->all(), 'pegawai_id', 'nama');
$viewAsDos = ArrayHelper::map(Staf::find()->all(), 'pegawai_id', 'pegawai_id');


?>
<script type="text/javascript">

    function getLast(val){
        // document.write(val);
        document.getElementById('binatang').value=2;
        var year=today.getFullYear();
      
    }
</script>
<style type="text/css">
    .scroll {
        width: 540px;
        background: white;
        padding: 10px;
        overflow: scroll;
        height: 350px;


    }

    .scroll2 {
        width: 285px;
        background: white;
        overflow: scroll;
        height: 350px;
    }
</style>
 
<?php
echo $namakuliah;
$staf=Staf::find()->select('pegawai_id')->asArray()->all();
$sta = ArrayHelper::map(HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all(), 'pegawai_id', 'nama');
$dosen1="";$load1=0;$jlhttp=0;
$dosen2="-";$load2="-";$jslkelas=0;
$dosen3="-";$load3="-";
$hasil=PenugasanPengajaran::find()->where('pengajaran_id='.$kuliah)->all();
if($hasil){
    foreach ($hasil as $key ) {

        if(substr($key['created_at'],0,4)<=date('Y')){

            $ss=HrdxPegawai::find()->where('pegawai_id='.$key['pegawai_id'])->all();
            foreach ($ss as $keyy) {
                $dosen1=$keyy['nama'];
            }
            $loald1=$key['load'];
            $dosen2=$key['role_pengajar_id'];
            if($dosen2==0){
                $dosen2="-";
            }else{
                $ss=HrdxPegawai::find()->where('pegawai_id='.$key['role_pengajar_id'])->all();
                foreach ($ss as $keyy) {
                $dosen2=$keyy['nama'];
                }
            }
            $loald2=$key['load2'];
            $dosen3=$key['role_pengajar_id3'];
            if($dosen3==0){
                $dosen3="-";
            }else{
                $ss=HrdxPegawai::find()->where('pegawai_id='.$key['role_pengajar_id3'])->all();
                foreach ($ss as $keyy) {
                    $dosen3=$keyy['nama'];
                }
            }
            $loald3=$key['load3'];
        }
    }
}
?>
<?php $form = ActiveForm::begin();
                    $jj = 0;
                    $ronaldo=0;;
                    $jlhsks=array();
                    ?>
<?php if($dosen1!=""){?>
    <div class="tree table">
        <div class="form-groups" style="background-color:#668fd6 ; ">
                <hr>
                <h2 >Dosen Pengampu Tahun Ajaran Sebelumnya </h2>
                <hr>
        </div>
        <div>
            <table class="table" border="2">
                <th>Judul</th>
                <th>Value</th>
                <tr>
                    <th>Jumlah Tatap Muka</th>
                    <th><?php echo $jlhttp; ?></th>
                </tr>
                <tr>
                    <th>Kelas Riil</th>
                    <th><?php echo $jslkelas; ?></th>
                </tr>
                <tr>
                    <th>Nama Dosen 1</th>
                    <th><?php echo $dosen1; ?></th>
                </tr>
                <tr>
                    <th>Load</th>
                    <th><?php echo $load1; ?></th>
                </tr>
                  <tr>
                    <th>Nama Dosen 2</th>
                    <th><?php echo $dosen2; ?></th>
                </tr>
                <tr>
                    <th>Load</th>
                    <th><?php echo $load2; ?></th>
                </tr>
                  <tr>
                    <th>Nama Dosen 3</th>
                    <th><?php echo $dosen3; ?></th>
                </tr>
                <tr>
                    <th>Load3</th>
                    <th><?php echo $load3; ?></th>
                </tr>
           
            </table>
        </div>
    </div>
<?php 
}?>
<div class="col-md-8">
    <h2>Penugasan Dosen</h2>
    <div class="scroll">
        <h3>Semester <?php echo $semester; ?></h3>
        <div class="col">
            <table class="tree table">
                <thead>
                    <tr>
                        <td id="matakuliah" style="min-width: 150px;"> Mata Kuliah </td>
                        <td id="jumlahsks" > Jumlah SKS </td>

                             <td id="jumlahsks" style="min-width: 150px;"> Jumlah Tatap Muka </td>
                                                    <td></td>
                              <td colspan="2"> </td>
                              <td></td>
                            <td id="loaddosen1" style="min-width: 62px;"></td>

                        </div>
                        <td id="asdos1" style="min-width: 150px;"></td>
                        <td></td>
                        <td id="loadasdos1" style="min-width: 62px;"></td>
                    </tr>
                </thead>
                <tbody>
                  
                        <tr id="tambahRow" class="bodydosen">
                           <td style="padding-top: 0px;" id="test">
                            <br>
                               <?=$form->field($model , 'pengajaran_id')->textInput([
                                 'type' => 'text',
                                 'value'=> $namakuliah,
                                 'disabled'=>'true',
                                 'onchange'=>'tatapM(value)'
                            ])->label(false)?>
                            <td style="max-width: 150px;padding-top: 25px;">
                              <input type="text" id="jumlahs" name="" value="<?php echo $skstot; ?>" disabled="">
                              
                                <script type="text/javascript">
                                    function so(value){

                                        <?php 
                                            PenugasanPengajaran::find();
                                        ?>
                                        document.getElementById('binatang').value=2;
                                        document.getElementById('jumlahs').value=value;
                                    }
                                </script>
                            </td>
                             </td>
                           
                            <td  style="max-width: 130px;padding-top: 20px;">
                                <?=$form->field($model , 'kelas_tatap_muka')->textInput([
                                 'type' => 'number',
                                 'min'=> '0',
                                 'onchange'=>'tatapM(value)'
                            ])->label(false)?>
                            </td>
                        </tr>
                        <tr>
                        <td id="jumlahsks" style="min-width: 150px;"> Jumlah Kelas Riil </td>

                            <td id="dosen<?= $jlhDosen ?>" style="min-width: 150px;"> Dosen 1 </td>
                            <td colspan="2" style="min-width: 150px;"> Load</td>
                           
                        </tr>
                        <tr>
                            <td  style="max-width: 130px;padding-top: 20px;">
                                <?=$form->field($model, 'jumlah_kelas_riil')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'rils(value)'
                            ])->label(false)?>

                            </td>
                            <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'pegawai_id')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos1(this);','id'=>'binatang'])->label('') ?>
                            </td>
                            <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input1(value,"+echo $matkul->sks;+")'
                            ])->label(false)?>
                            </td>
                        </tr>
                        <tr>
                              <td id="dosen<?= $jlhDosen ?>" style="min-width: 150px;"> Dosen 2 </td>
                            <td colspan="2" style="min-width: 150px;"> Load</td>
                        </tr>
                        <tr>
                              <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'role_pengajar_id')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos2(this);'])->label('') ?>
                            </td>

                              <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load2')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input2(value,"+echo $matkul->sks;+")'
                            ])->label(false)?>
                            </td>
                        </tr>
                        <tr>
                              <td id="dosen<?= $jlhDosen ?>" style="min-width: 150px;"> Dosen 3 </td>
                            <td colspan="2" style="min-width: 150px;"> Load</td>
                        </tr>
                        <tr>
                              <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'role_pengajar_id3')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos3(this);'])->label('') ?>
                            </td>
                             <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load3')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input3(value,"+echo $matkul->sks;+")'
                            ])->label(false)?>
                            </td>
                            </tr>

                            <tr>
                                <td>
                              <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>  
                            </td>

                        </tr>
                    </tbody>
            </table>
        </div>
    </div>

</div><!-- 
    <div colspans="col-md-4"> -->
        <br><br><br>
        <div class="scroll2">
            <table>
                <tr>
                    <td>
                        <label>Nama Dosen</label>
                    </td>

                    <td>
                        <label>Load Dosen</label>
                    </td>
                </tr>
                    <?php $indikator = 0;
                    $i=0;


                    $i=0;
                    foreach ($que as $key) {

                        $i++;
                    }
                                // print_r($load);
                    $indikatorr=0;
                    $ii=PenugasanPengajaran::find()->all();
                    $penanda2=0;
                    $ppp=array();
                    foreach ($ii as $key ) {
                        $ppp[$penanda2]=$key['pegawai_id'];
                        $penanda2++;

                    }
                    $ttg=1;
                    foreach ($que as $q) {
                        $penugasan = PenugasanPengajaran::find()->where('pegawai_id = '.$q['pegawai_id'])->all();
                        $load=0;
                        foreach($penugasan as $penpe){ $load=$load+$penpe['load'];}
                        ?>

                        <tr bgcolor="">
                            <td>
                                <?php if($q['nama']!='-') {?>
                                <input <?php if($load>15){ ?> style="background-color: #FFA3A3;" <?php }elseif($load>4){ ?> style="background-color: #FFD64D;" <?php }else{?>style="background-color: #78E142;"<?php }?>type="text" value="<?= $q['nama'] ?>" disabled="" name="<?= $q['pegawai_id'] ?>"></td>

                            <td><input <?php if($load>15){ ?> style="background-color: #FFA3A3;" <?php }elseif($load>4){ ?> style="background-color: #FFD64D;" <?php }else{?>style="background-color: #78E142;"<?php }?> id="<?php echo $q['pegawai_id']; ?>"class="<?php echo $q['pegawai_id']; ?>" type="text" value="<?= $load ?>" disabled="" name=""></td>
                            <?php } ?>
                        </tr>
                        <?php $indikator++;
                        if($q['pegawai_id'] == $ppp[$penanda2-1]){
                            foreach($modelPengajaran as $tt){?>
                                <script type="text/javascript">
                                    var t=<?php echo  $ppp[$penanda2-1] ;?>;
                                    var skss= <?php echo  $tt->sks ;?>;
                                    var tatapMuka2=document.getElementById(tatapMuka);
                                    document.getElementById(t).value=skss+tatapMuka2;
                                </script>

                    <?php   }
                        }
                    }?>
                        
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function Dosa(baris){
            <?php
             ?>
            if(<?php echo $load1; ?>===0){
                 var ss=baris.options[baris.selectedIndex].value;
                alert('baris');
            }else{
                alert('del');
            }
            Dos(baris);
        }
    </script>
   

                <?php
                $this->registerJs(
                    "

                                    function dosen(baris,sks){
                                        var a=baris;
                                         $.ajax({
                                            url: '" . \Yii::$app->urlManager->createUrl(['rppx/penugasan-pengajaran/pegawais']) . "',
                                            type: 'POST',
                                            success: function(data){
                                                data = jQuery.parseJSON(data);
                                                pegawais = '<option>--Pengajar--</option>';

                                                for(var i = 1; i < data.length; i++){
                                                    pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';

                                                }

                                                add(baris,sks);
                                            }
                                        });
                                    }
                                    var penanda=0;
                                    function add(baris,sks){
                                        var baris=baris;
                                        
                                        var skss=sks;
                                        $('#pos'+baris).before('<td><div style=\"margin-top:11.5px;\" class=\"form-group\"><div class=\"col-sm-4\"><select  style=\"width:140px;\" onChange=\"Dos(this)\" id=\"id_pegawai\" class=\"form-control\">'+pegawais+'</select></div><div class=\"col-sm-5\"></div><input style=\" width:70px; margin-left:170px;\" onChange=\"input2(value,'+sks+')\" type=\"number\" step=\"1\" class=\"form-control\" width=\"50px\"></td>');
                                            penanda++;

                                    }
                                    var indi1=0;
                                    var indi2=0;
                                    var indi3=0;
                                    var tunjuk1;
                                    var tunjuk2;
                                    var tunjuk3;
                                    var vtunjuk1;
                                    var vtunjuk2;
                                    var vtunjuk3;
                                    var ttp=0;
                                    var ril=0;
                                    var global=100;
                                    var globe=0;
                                    var globs=0;
                                    var globt=0;
                                    function Dos1(baris){
                                        var ss=baris.options[baris.selectedIndex].value;
                                        if(indi1!=1){
                                            indi1=1;

                                            tunjuk1=ss;
                                            vtunjuk1=document.getElementById(tunjuk1).value;                                             
                                           }else{
                                            if(tunjuk1!=ss){
                                                document.getElementById(tunjuk1).value=vtunjuk1;
                                                tunjuk1= ss;
                                                vtunjuk1 = document.getElementById(tunjuk1).value;
                                            }
                                           }
                                    }
                                    function Dos2(baris){
                                        var ss=baris.options[baris.selectedIndex].value;
                                        if(indi2!=1){
                                            indi2=1;

                                            tunjuk2 = ss;
                                            vtunjuk2=document.getElementById(tunjuk2).value;
                                        }else{
                                            if(tunjuk2!=ss){
                                                document.getElementById(tunjuk2).value=vtunjuk2;
                                                tunjuk2=ss;
                                                vtunjuk2 = document.getElementById(tunjuk2).value;
                                            }
                                        }
                                    }
                                    function Dos3(baris){
                                        var ss=baris.options[baris.selectedIndex].value;
                                        if(indi3!=1){
                                            indi3=1;

                                            tunjuk3 = ss;
                                            vtunjuk3=document.getElementById(tunjuk3).value;
                                        }else{
                                            if(tunjuk3!=ss){
                                                document.getElementById(tunjuk3).value=vtunjuk3;
                                                tunjuk3=ss;
                                                vtunjuk3 = document.getElementById(tunjuk3).value;
                                            }
                                        }
                                    }
                                    function tatapM(value){
                                        ttp=value;
                                    }
                                    function rils(value){
                                        ril=value;
                                    }
                                    function input1(val,sks){
                                        
                                        if(indi1 != 1){
                                            alert('Pilih Dosen');
                                        }else if(ttp===0){
                                            alert('Entry Jumlah Tatap Muka');
                                        }else if(ril === 0 ){
                                            alert('Entry Jumlah kelas Rill');
                                        }else{
                                            globe=global-val;
                                            var yy= sks+ ttp*sks +sks*ril ;
                                            var y=yy/3;
                                            var hhhj=vtunjuk1;
                                            var hhhsil = parseFloat(hhhj)+((3+ ttp*3 +3*ril)/3)*(val/100);
                                            document.getElementById(tunjuk1).value=hhhsil;

                                        }
                                    }
                                    function input2(val,sks){
                                        
                                        if(indi2 != 1){
                                            alert('Pilih Dosen');
                                        }else if(ttp===0){
                                            alert('Entry Jumlah Tatap Muka');
                                        }else if(ril === 0 ){
                                            alert('Entry Jumlah kelas Rill');
                                        }else{
                                            globs=globe-val;
                                            var yy= sks+ ttp*sks +sks*ril ;
                                            var y=yy/3;
                                            var hhhj=vtunjuk2;
                                            var hhhsil = parseFloat(hhhj)+((3+ ttp*3 +3*ril)/3)*(val/100);
                                            document.getElementById(tunjuk2).value=hhhsil;
                                            if(globs<=0){
                                                alert('Batas Load adalah 100 %');
                                            }
                                        }
                                    }
                                    function input3(val,sks){
                                        
                                        if(indi3 != 1){
                                            alert('Pilih Dosen');
                                        }else if(ttp===0){
                                            alert('Entry Jumlah Tatap Muka');
                                        }else if(ril === 0 ){
                                            alert('Entry Jumlah kelas Rill');
                                        }else{
                                            globt=globs-val;
                                            var yy= sks+ ttp*sks +sks*ril ;
                                            var y=yy/3;
                                            var hhhj=vtunjuk3;
                                            var hhhsil = parseFloat(hhhj)+((3+ ttp*3 +3*ril)/3)*(val/100);
                                            document.getElementById(tunjuk3).value=hhhsil;
                                            if(globt<=0){
                                                alert('Batas Load adalah 100 %');
                                            }
                                        }
                                    }
                                    function asdosen(baris,sks){
                                        $.ajax({
                                            url: '" . \Yii::$app->urlManager->createUrl(['rppx/penugasan-pengajaran/pegawai']) . "',
                                            type: 'POST',
                                            success: function(data){
                                            data = jQuery.parseJSON(data);
                                            pegawais = '<option >--Assisten Dosen--</option>';

                                            var t=0;
                                            for(var i =0; i < data.length; i++){
                                            // document.write(data[i]['nama']);
                                                pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                                                asd=data[i]['nama'];
                                                t++;

                                            }
                                            tambah(baris,sks);
                                            // asDos(baris);
                                        }
                                        });
                                    }
                                    var asdoss=0;
                                    function tambah(baris,sks){
                                        $('#posAsdos'+baris).before('<td><div style=\"margin-top:11.5px;\" class=\"form-group\"><div class=\"col-sm-4\"><select onChange=\"asDos(this)\" style=\"width:140px;\" value=\"7\" id=\"baris\" class=\"form-control\" >'+pegawais+'</select></div><div class=\"col-sm-5\"></div><input style=\" width:70px; margin-left:170px;\" hidden=\"\" type=\"number\" min=\"0\" onChange=\"input(value,'+sks+')\" class=\"form-control\" step=\"1\" width=\"70px\" value=\"'+asdoss+'\"></td>');
                                        asdoss++;
                                        document.getElementById();
                                    }
                                    var indikat=0;
                                    var penunjuk;
                                    function asDos(baris){
                                        indikat=1;
                                        var ss=baris.options[baris.selectedIndex].value;
                                        penunjuk=ss;
                                    }
                                    function input(val,sks){
                                      if(indikat != 1){
                                        alert('Pilih Assisten Dosen');
                                        }else if(ttp===0){
                                            alert('Entry Jumlah Tatap Muka');
                                        }else if(ril === 0 ){
                                            alert('Entry Jumlah kelas Rill');
                                        }else{
                                            var yy= sks+ ttp*sks +sks*ril ;
                                            var y=yy/3;
                                            document.getElementById(penunjuk).value+=((sks+ ttp*sks +sks*ril)/3)*(val/100);
                                        }    
                                    }",
    $this::POS_END);
?>
<div class="form-group" style="margin-left: 490px; ">
    <!-- <button class="btn btn-success">Create</button> -->
   <!--    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>   -->
     
</div>
</form>
<?php $form = ActiveForm::end(); ?>

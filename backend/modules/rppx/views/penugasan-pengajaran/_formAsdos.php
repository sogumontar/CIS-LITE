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
$staf=Staf::find()->select('pegawai_id')->asArray()->all();
$viewPengajar = ArrayHelper::map(HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all(), 'pegawai_id', 'nama');

$viewPengajar2 = ArrayHelper::map(Kuliah::find()->where('stat_asdos_created=0')->all(), 'kuliah_id', 'nama_kul_ind');
$viewPengajarr = ArrayHelper::map(HrdxPegawai::find()->all(), 'pegawai_id', 'nama');
$viewAsDos = ArrayHelper::map(Staf::find()->all(), 'pegawai_id', 'pegawai_id');


?>
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
$staf=Staf::find()->select('pegawai_id')->asArray()->all();
$dataStaf=HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all();
$sta = ArrayHelper::map(HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->asArray()->all(), 'pegawai_id', 'nama');
?>
<?php $form = ActiveForm::begin();
                    $jj = 0;
                    $ronaldo=0;;
                    $jlhsks=array();
                    ?>
<div class="col-md-8">
    <h2>Penugasan Assisten Dosen</h2>
    <div class="scroll">
        <h3>Semester <?php echo $semester; ?></h3>
        <div class="col">
            <table class="tree table">
                <thead>
                    <tr>
                        <td id="matakuliah" style="min-width: 150px;"> Mata Kuliah </td>
                        <!-- <td id="jumlahsks"  > Jumlah SKS </td> -->
                        <td id="jumlahsks" style="min-width: 150px;"> Jumlah Tatap Muka </td>
                        <td id="jumlahsks" style="min-width: 150px;"> Jumlah Kelas Riil </td>
                        <div id="headdosens">

                            <td id="dosen<?= $jlhDosen ?>" style="min-width: 150px;"> Nama Assisten Dosen </td>
                            <td colspan="2" style="min-width: 150px;"> %Assisten Dosen 1</td>
                            <td style="min-width: 150px;">%Assisten Dosen 2</td>
                             <td colspan="2" style="min-width: 150px;">%Asissten Dosen 3</td>
                             <td></td>
                              <td colspan="2"> </td>
                              <td></td>
                            <td id="loaddosen1" style="min-width: 62px;"></td>

                      
                </thead>
                <tbody>
                  
                        <tr id="tambahRow" class="bodydosen">
                           <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'pengajaran_id')->dropDownList($viewPengajar2, ['prompt' => '--Mata Kuliah--','onChange'=>'js:Dos(this);'])->label('') ?>
                           
                          
                            <td  style="max-width: 130px;padding-top: 20px;">
                                <?=$form->field($model , 'kelas_tatap_muka')->textInput([
                                 'type' => 'number',
                                 'min'=> '0',
                                 'onchange'=>'tatapM(value)'
                            ])->label(false)?>
                            </td>
                            <td  style="max-width: 130px;padding-top: 20px;">
                                <?=$form->field($model, 'jumlah_kelas_riil')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'rils(value)'
                            ])->label(false)?>

                            </td>
                            <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'asdos1')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos(this);'])->label('') ?>
                            </td>
                            <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load1')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input2(value,"+echo $matkul->sks;+")'
                            ])->label(false)?>
                            </td>
                              <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'asdos2')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos(this);'])->label('') ?>
                            </td>

                              <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load2')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input2(value,"+echo $matkul->sks;+")'
                            ])->label(false)?>
                            </td>
                              <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'asdos3')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos(this);'])->label('') ?>
                            </td>
                             <td style="padding-top: 0px;" id="test2" width="200px"><br>
                               <?=$form->field($model, 'load3')->textInput([
                                 'type' => 'number',
                                 'id'=>'riil',
                                 'min'=>'0',
                                 'onchange'=>'input2(value)'
                            ])->label(false)?>
                            </td>
                            <td>
                                <br>
                              <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>  
                            </td>
                        </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div><!-- 
    <div class="col-md-4"> -->
        <div class="scroll2">
            <table>
                <tr>
                    <td>
                        <label>Nama Asdos</label>
                    </td>

                    <td>
                        <label>Load </label>
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
                    $ii=PenugasanPengajaran::find()->where('')->all();
                    $penanda2=0;
                    $ppp=array();
                    foreach ($ii as $key ) {
                        $ppp[$penanda2]=$key['pegawai_id'];
                        $penanda2++;

                    }
                    $ttg=1;
                    foreach ($dataStaf as $q) {

                        $jlhL=PenugasanPengajaran::find()->where('pegawai_id='.$q['pegawai_id'])->all();
                        foreach ($jlhL as $key ) {
                            // echo $key['load'];
                        }
                        ?>

                        <tr bgcolor="">
                            <td><input type="text" value="<?= $q['nama'] ?>" disabled="" name="<?= $q['pegawai_id'] ?>"></td>
                            <td><input id="<?php echo $q['pegawai_id']; ?>"class="<?php echo $q['pegawai_id']; ?>" type="text" value="<?php echo "5"; ?>" disabled="" name=""></td>
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
                                    var indi=0;
                                    var tunjuk;
                                    var ttp=0;
                                    var ril=0;
                                    function Dos(baris){
                                        indi=1;
                                        var ss=baris.options[baris.selectedIndex].value;

                                        tunjuk=ss;
                                    }
                                    function tatapM(value){
                                        ttp=value;
                                    }
                                    function rils(value){
                                        ril=value;
                                    }
                                    function input2(val,sks){
                                        
                                        if(indi != 1){
                                            alert('Pilih Dosen');
                                        }else if(ttp===0){
                                            alert('Entry Jumlah Tatap Muka');
                                        }else if(ril === 0 ){
                                            alert('Entry Jumlah kelas Rill');
                                        }else{
                                            var yy= sks+ ttp*sks +sks*ril ;
                                            var y=yy/3;
                                            var hhhj=document.getElementById(tunjuk).value;
                                            document.getElementById(tunjuk).value=((3+ ttp*3 +3*ril)/3)*(val/100);
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

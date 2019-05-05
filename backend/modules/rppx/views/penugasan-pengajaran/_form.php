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
$viewPengajar = ArrayHelper::map(HrdxPegawai::find()->all(), 'pegawai_id', 'nama');
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
$sta = ArrayHelper::map(HrdxPegawai::find()->select('*')->where(['pegawai_id' => $staf])->all(), 'pegawai_id', 'nama');
?>
<div class="col-md-8">
    <div class="scroll">
        <h3>Semester <?php echo $semester; ?></h3>
        <div class="col">
            <table class="tree table">
                <thead>
                    <tr>
                        <td id="matakuliah">Mata Kuliah</td>
                        <td id="jumlahsks">Jumlah SKS</td>
                        <td id="jumlahsks">Jumlah Tatap Muka</td>
                        <td id="jumlahsks">Jumlah Kelas Riil</td>
                        <div id="headdosens">
                            <td id="dosen<?= $jlhDosen ?>" style="min-width: 150px;">Nama Dosen</td>
                            <td>%Dosen</td>
                            <td id="loaddosen1" style="min-width: 62px;"></td>

                        </div>
                        <td id="asdos1" style="min-width: 150px;"></td>
                        <td></td>
                        <td id="loadasdos1" style="min-width: 62px;"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php $form = ActiveForm::begin();
                    $jj = 0;
                    ?>
                    <?php foreach ($modelPengajaran as $key => $matkul) {

                        ?>
                        <tr id="tambahRow" class="bodydosen">
                            <td style="max-width: 150px;padding-top: 25px;">
                                <?php echo $matkul->nama_kul_ind; ?>
                            </td>
                            <td style="max-width: 150px;padding-top: 25px;">
                                <?= $matkul->sks; ?>
                            </td>
                            <td>
                                <input type="number" value="0" min="0" onchange="tatapM()"  id="tatapMuka" style="margin-top: 12px; width: 70px;" class="form-control" name="">
                            </td>
                            <td>
                                <input type="number" style="margin-top: 12px; width: 70px;" id="riil" onchange="rils()" value="0" min="0" class="form-control" name="">
                            </td>
                            <td style="padding-top: 0px;" id="test">
                                <?= $form->field($model, 'pegawai_id')->dropDownList($viewPengajar, ['prompt' => '--Pengajar--','onChange'=>'js:Dos(this);'])->label('') ?>
                            </td>
                            <td style="padding-top: 0px;" id="test2" width="200px"><br>
                                <input type="number" step=".1" onchange="input2(value)" class="form-control" name="" min="0" style="width: 70px;" >
                            </td>


                            <td style="max-width: 150px;padding-top: 20px;" id="pos<?php echo $jj; ?>">
                                <a class="btn btn-primary" onclick="dosen(<?php echo $jj; ?>);"><i
                                    class="fa fa-plus"> </i></a>
                                </td>
                                <td><br>
                                    <p>Dosen</p>
                                </td>

                            </tr>
                            <tr>
                                <div class="bodydosen1">
                                    <td colspan="2">

                                    </td>
                                    <td>
                                        <!-- <input type="number" value="0" min="0" id="jlhasdos" class="form-control" style="margin-top: 12px;" name=""> -->
                                    </td>
                                    <td></td>
                                    <td style="padding-top: 0px;" >

                                        <?= $form->field($model, 'pegawai_id')->dropDownList($sta, ['prompt' => '--Assisten Dosen--' , 'onChange'=>'js:asDos(this);'])->label('') ?>
                                    </td>

                            <!-- <td>
                                            <?= $form->field($model, 'role_pengajar_id')->dropDownList($view, ['prompt' => '---mata kuliah-']) ?>
                                        </td> -->
                                        <td style="padding-top: 0px;">
                                            <!-- <?= $form->field($model, 'load')->textInput(['', 'contoh'])->label(''); ?> --><br>
                                            <input type="number" step=".1" onchange="input(value)" class="form-control" min="0" id="loadAsdos" name="">

                                        </td>
                                        <td style="max-width: 150px;padding-top: 20px;" id="posAsdos<?php echo $jj ;?>">
                                            <a class="btn btn-primary"onclick="asdosen(<?php echo $jj; ?>);"><i class="fa fa-plus"> </i></a>
                                        </td>
                            <!-- </td>
                                            <td>
                                                <?= $form->field($model, 'is_fulltime')->textInput(['value' => "asd", 'readonly' => true]) ?>
                                            </td><td>
                                                <?= $form->field($model, 'start_date')->textInput() ?>
                                            </td><td>
                                                <?= $form->field($model, 'end_date')->textInput() ?>
                                            </td><td>
                                                <?= $form->field($model, 'deleted')->textInput() ?>
                                            </td><td>
                                                <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>
                                            </td><td>
                                                <?= $form->field($model, 'deleted_at')->textInput() ?>
                                            </td><td>
                                                <?= $form->field($model, 'created_at')->textInput() ?>
                                            </td><td>
                                                <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>
                                            </td><td>
                                                <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>
                                            </td><td>
                                                <?= $form->field($model, 'updated_at')->textInput() ?>
                                            </td> -->
                                            <td><br>
                                                <p>Asisten Dosen</p>
                                            </td>

                                        </tr>
                                        <?php $jj++;
                                    } ?>


                                </tbody>

                            </table>
                        </div>


                    </div>

                </div>

                <div class="col-md-4">
                    <div class="scroll2">
                        <table>
                            <tr>
                                <td><label>Nama Dosen</label></td>

                                <td><label>Load Dosen</label></td>
                            </tr>
                            <?php $indikator = 0;
                            $i=0;

                            $load[$i] = ArrayHelper::map(PenugasanPengajaran::find()->select('*')->where(['pegawai_id' => $key['pegawai_id']])->all(), 'pegawai_id', 'load');

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
                            
                            ?>

                           <tr bgcolor="">
                            <td><input type="text" value="<?= $q['nama'] ?>" disabled="" name="<?= $q['pegawai_id'] ?>"></td>
                            <td><input id="<?php echo $q['pegawai_id']; ?>"class="<?php echo $q['pegawai_id']; ?>" type="text" value="" disabled="" name=""></td>
                        </tr>
                        <?php $indikator++;
                        if($q['pegawai_id'] == $ppp[$penanda2-1]){?>
                                
                                <script type="text/javascript">
                                    var t=<?php echo  $ppp[$penanda2-1] ;?>;
                                    
                                    document.getElementById(t).value=5;
                                </script>
                               
                            <?php }
                    } ?>
                </table>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php $form = ActiveForm::end(); ?>


        <?php
        $this->registerJs(
            "

            function dosen(baris){
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
                        add(baris);
                    }
                    });
                }
                var penanda=0;
                function add(baris){
                    $('#pos'+baris).before('<td><div style=\"margin-top:11.5px;\" class=\"form-group\"><div class=\"col-sm-4\"><select  style=\"width:140px;\" onChange=\"Dos(this)\" id=\"id_pegawai\" class=\"form-control\">'+pegawais+'</select></div><div class=\"col-sm-5\"></div><input style=\" width:70px; margin-left:170px;\" onChange=\"input2(value)\" type=\"number\" step=\".1\" class=\"form-control\" width=\"50px\"></td>');
                    penanda++;

                }
                var indi=0;
                var tunjuk;
                var ttp=0;
                var ril=0;
                function Dos(baris){
                    indi=1;
                    var ss=baris.options[baris.selectedIndex].value;

                    document.getElementById(ss).value=0;
                    tunjuk=ss;
                }
                function tatapM(){
                    ttp=1;

                }
                function rils(){
                    ril=1;

                }
                function input2(val){


                  if(indi != 1){
                    alert('Pilih Dosen');
                    }else if(ttp===0){
                        alert('Entry Jumlah Tatap Muka');
                        }else if(ril === 0 ){
                            alert('Entry Jumlah kelas Rill');
                            }else{
                               document.getElementById(tunjuk).value=val;
                           }

                       }
                       function asdosen(baris){
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
                                tambah(baris);
                                        // asDos(baris);
                            }
                            });
                        }
                        var asdoss=0;
                        function tambah(baris){
                            $('#posAsdos'+baris).before('<td><div style=\"margin-top:11.5px;\" class=\"form-group\"><div class=\"col-sm-4\"><select onChange=\"asDos(this)\" style=\"width:140px;\" value=\"7\" id=\"baris\" class=\"form-control\" >'+pegawais+'</select></div><div class=\"col-sm-5\"></div><input style=\" width:70px; margin-left:170px;\" hidden=\"\" type=\"number\" min=\"0\" onChange=\"input(value)\" class=\"form-control\" step=\".1\" width=\"70px\" value=\"'+asdoss+'\"></td>');
                            asdoss++;
                            document.getElementById();
                        }
                        var indikat=0;
                        var penunjuk;
                        function asDos(baris){
                            indikat=1;
                            var ss=baris.options[baris.selectedIndex].value;

                            alert(ss);
                            document.getElementById(ss).value=0;
                            penunjuk=ss;
                        }
                        function input(val){
                            if(indikat===1){
                                document.getElementById(penunjuk).value=val;
                                }else{
                                    alert('Pilih Asisten Dosen Dahulu');
                                }
                            }


                            ",
                            $this::POS_END);
                            ?>


<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\rppx\models\Kuliah;
use backend\modules\rppx\models\HrdxPegawai;
use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\AdakPengajaran;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */
/* @var $form yii\widgets\ActiveForm */
$view=ArrayHelper::map(Kuliah::find()->all(),'kode_mk','nama_kul_ind');
$vieww=ArrayHelper::map(AdakPengajaran::find()->all(),'pengajaran_id','kuliah_id');
$viewPengajar=ArrayHelper::map(HrdxPegawai::find()->all(),'pegawai_id','nama');
$viewPengajarr=ArrayHelper::map(HrdxPegawai::find()->all(),'pegawai_id','nama');
$ind=0;
$que=HrdxPegawai::find()->all();
?>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col-8">
                        <table>
                            <?php $form = ActiveForm::begin(); ?>
                            <?php foreach ($modelPengajaran as $key => $matkul) {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo $matkul->nama_kul_ind; ?>
                                        </td>
                                        <td>
                                            
                                        </td>
                                        <td>
                                            <?= $form->field($model, 'pengajaran_id')->dropDownList($vieww,['prompt'=>'--Id Pengajar--']) ?>
                                        </td>

                                        <td>
                                            <?= $form->field($model, 'pegawai_id')->dropDownList($viewPengajar,['prompt'=>'--Pengajar--']) ?>
                                        </td>
                                        <!-- <td>
                                            <?= $form->field($model, 'role_pengajar_id')->dropDownList($view,['prompt'=>'---mata kuliah-']) ?>
                                        </td> -->
                                         <td >
                                            <?= $form->field($model, 'load')->textInput()?>
                                        </td>
                            <!-- </td>
                            <td>
                                <?= $form->field($model, 'is_fulltime')->textInput(['value'=>"asd",'readonly'=>true]) ?>
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
                            <td>
                                <?php $form-> ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php $form = ActiveForm::end(); ?>
                    </table>
                    
            </div>
            <div class="col-3">
                
            </div>    
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-5">
    <div class="scroll">
                <h3>Semester <?php echo $semester; ?></h3>
                    <div class="col"></div>
                
            
        </div>
                    
    </div>    
</div>

<div class="col-md-2">

</div>
<div class="col-md-5"></div>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
      $("#btn2").click(function(){
        $("ol").append("<li>Appended item</li>");
      });
    });
</script>


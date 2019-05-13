<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use backend\modules\rppx\models\PenugasanPengajaran;
use backend\modules\rppx\models\Kelas;
use backend\modules\rppx\models\Kuliah;
use yii\widgets\ActiveForm;

$viewPengajar = PenugasanPengajaran::find()->all();
$sta = PenugasanPengajaran::find()->select('*')->where(['approved' != 1])->asArray();
$jlhMatkul=Kuliah::find()->select('*')->where(['approved' != 1])->asArray();

$rows = (new \yii\db\Query())
->select(['*'])
->from('rppx_penugasan_pengajaran')
->where(['approved' => 1])
->all();?>

<div class="">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br>"; ?></p>
    
</div>
<div class="form-group">
    <div class="col-md-3">
        <label><b>Kelas</b></label>
    </div>
    <div class="col-md-3">
        <label><b>Total Load</b></label>
    </div>
    <div class="col-md-4">
        <label><b>Jumlah Mata Kuliah</b></label>
    </div>
    <div class="col-md-2">
        <label><b>Action</b></label>
    </div>
</div>
<?php foreach ($viewPengajar as $key) {
    $kelass = Kelas::find()->select('*')->where(['kelas_id' => $key->kelas])->all();
    
    ?>
    <div class="form-group">
        <div class="col-md-3">
            <?php foreach ($kelass as $test) {
                # code...
            ?>
            <p class="form-control"><?php echo $test->nama ;?></p>
        <?php } ?>
        </div>
        <div class="col-md-3">
            <p class="form-control">asd</p>
        </div>
        <div class="col-md-4">
            <p class="form-control">asd</p>
        </div>
        <div class="col-md-2">
            <a class="fa fa-book"></a>
            <a class="fa fa-check"></a>
            <a class="fa fa-close"></a>
        </div>
    </div>
<br>  &nbsp;<br>
<?php }
?>


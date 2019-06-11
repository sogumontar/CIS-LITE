<?php
    use yii\helpers\Url;
    use backend\modules\rppx\models\AdakPengajaran;
    use backend\modules\rppx\models\Kelas; 
    use backend\modules\rppx\models\Prodi;

$this->title = 'Kelas';
$this->params['breadcrumbs'][] = $this->title;
$this->params['layout'] = 'full';
?>

<div class="penugasan-pengajarann-menu">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br>"; 

    $prod;
    ?>
        
    </p>

    <h1>Kelas</h1>
    <hr>    
    <br>    <br>    
    <?php 

    ?>
    <?php
    $idLog=1;
     // $prod=Prodi::find()->where('kepala_id='.$idLog)->all();
     // $prodiId;
     // foreach ($prod as $key) {
     //      $prodiId= $key['ref_kbk_id'];
     // }
     $kelas=Kelas::find()->where('kaprodi_id='.$idLog)->orderBy('nama')->all();
     $prodi="";
     $i=1;
        if(true){
            foreach ($kelas as $key ) {
                // echo $i ;
                ?>
                <div class="container">
                    <div class="col-md-5">
                        <h3><?php echo $key['nama'];?></h3>
                    </div>
                    <div class="col-md-3" align="right">
                        <a href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/menu','kelas'=>$key['nama']]) ?>" class="btn btn-primary">Edit</a>
                        
                    </div>
                </div>
                <hr>
                <?php 

                $i+=2;
            }
        }

    ?>
    
</div>

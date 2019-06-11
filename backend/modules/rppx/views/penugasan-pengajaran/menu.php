<?php
    use yii\helpers\Url;
    use backend\modules\rppx\models\AdakPengajaran;
    use backend\modules\rppx\models\Kuliah;
    use backend\modules\rppx\models\Kelas;
    use backend\modules\rppx\models\Prodi;

    $this->title = 'Menu';
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['layout'] = 'full';
    $kel=$_GET['kelas'];
    $tkt=Kelas::find()->where(['nama' => $kel])->all();
    $tt;
    foreach ($tkt as $key) {
        $tt=$key['ta'];
        if($key['nama']==$_GET['kelas']){
             $key['nama'];
        }
    }
    $idLogin=1;
    $ket;
    $kaprod=kelas::find()->where('kaprodi_id='.$idLogin)->all();
    foreach ($kaprod as $key) {
        if($key['nama']==$_GET['kelas']){
            $ket=$key['kelas_id'];
        }
    }

        $matkul=Kuliah::find()->where( "kelas_id='$ket' AND stat_created=0")->all();

?>

<div class="penugasan-pengajarann-menu">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br>"; 

    $prod;
    $semPendek;
    $rre;
    $kel=$_GET['kelas'];
    $Sp=Kelas::find()->where(['nama'=>$kel])->all();
    foreach ($Sp as $key ) {
        $semPendek=$key['prodi_id'];
    }
    $seP=Prodi::find()->where('ref_kbk_id='.$semPendek)->all();
    foreach ($seP as $key ) {
        $rre=$key['nama_kopertis_ind'];
    }
    ?></p>

      
    <?php
    if(date('m')>5){
        ?>
          <button class="btn btn-primary" disabled="">Semester Ganjil</button>
          <button class="btn btn-primary">Semester Genap</button>
          <?php if($rre=='Sarjana'){ ?>

            <a class="btn btn-primary" href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/sems','kelas'=>$_GET['kelas']]) ?>" class="btn btn-primary">Semester Pendek</a>
          <?php }else{?>
            <button class="btn btn-primary" disabled="">Semester Pendek</button> 
          <?php } ?>
        <br><br><br>
        <?php 
        foreach ($matkul as $key ) {
            if($key['sem']%2==0){
                ?>
                  <div class="container">
                    <div class="col-md-5">

                         <h3><b><?php echo $key['nama_kul_ind'];?> <i>(<?php echo $key['kode_mk']; ?>)</i></h3>
                    </div>
                    <div class="col-md-3" align="right">
                        <a href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/create','semester'=>$key['sem'],'kelas'=>$_GET['kelas'],'kuliah'=>$key['kuliah_id']]) ?>" class="btn btn-primary">Edit</a>
                        
                    </div>
                </div>
                <hr>
                <?php  
            }
            
        }
    }else{
          ?>
          <button class="btn btn-primary" >Semester Ganjil</button>
          <button class="btn btn-primary" disabled="">Semester Genap</button>
           
            <button class="btn btn-primary" disabled="">Semester Pendek</button> 
        
        <br><br><br>
        <?php 
        foreach ($matkul as $key ) {
            if($key['sem']%2!=0){
              ?>
                  <div class="container">
                    <div class="col-md-5">

                        <h3><?php echo $key['nama_kul_ind'];?></h3>
                    </div>
                    <div class="col-md-3" align="right">
                        <a href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/create','semester'=>$key['sem'],'kelas'=>$_GET['kelas']]) ?>" class="btn btn-primary">Edit</a>
                        
                    </div>
                </div>
                <hr>
                <?php  
            }
            
        }
    }
   
    ?>
    
</div>

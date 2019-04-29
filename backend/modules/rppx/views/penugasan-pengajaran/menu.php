<?php
    use yii\helpers\Url;

    $this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
$this->params['layout'] = 'full';
?>
<div class="penugasan-pengajarann-menu">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br><br><br>"; ?></p>
    <?php

     $prodi="";
     $i=1;
        if(true){
            while($i!=9){
                // echo $i ;
                ?>
                <div class="container">
                    <div class="col-md-5">
                        <h3>Semester    <?php echo $i;?></h3>
                    </div>
                    <div class="col-md-3" align="right">
                        <a href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/create','semester'=>$i]) ?>" class="btn btn-primary">Edit</a>
                        
                    </div>
                </div>
                <hr>
                <?php 

                $i+=2;
            }
        }

    ?>
    
</div>

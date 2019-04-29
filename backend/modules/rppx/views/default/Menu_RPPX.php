<div class="rppx-default-index">
    <?php 
    $d=date("D , d  M  Y");
        // $date=date("D/M/Y");
    ?>
    <p><?php echo $d."<br><br><br><br>"; ?></p>
    <?php

     $prodi="";
     $i=2;
        if(true){
            while($i!=8){
                // echo $i ;
                ?>
                <div class="container">
                    <div class="col-md-5">
                        <h3>Semester    <?php echo $i;?></h3>
                    </div>
                    <div class="col-md-3" align="right">
                        <button class="btn btn-primary">
                            &nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;
                        </button>
                    </div>
                </div>
                <hr>
                <?php 

                $i+=2;
            }
        }

    ?>
    
</div>

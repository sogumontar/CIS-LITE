<?php

use yii\helpers\Html;
use yii\db\Query;


/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */

$this->title = 'Create Penugasan Pengajaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Pengajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-pengajaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    	'jlhDosen'=>$jlhDosen,
        'jlhAsdos'=>$jlhAsdos,
    	'model' => $model,
    	'baris'=>$baris,
    	'colom'=>$colom,
    	'modelPengajaran' => $modelPengajaran,
    	'semester'=> $semester,
    ]) ?>

</div>
<!-- <br>
<label>Nama Dosen :</label>
<?php
$database =new mysqli('127.0.0.1','root','','cis_production');
$que = 'SELECT * FROM hrdx_pegawai ';
$kuliah='SELECT * FROM krkm_kuliah';

$results = mysqli_query($database, $que);
$res=mysqli_query($database,$kuliah);
?>
<div >
	<table>

			<tr class="filters">
				<th><input type="text" class="form-control" placeholder="Nama Matakuliah" disabled></th>
				<th><input type="text" class="form-control" placeholder="Jumlah SKS" disabled></th> 
				<th><input type="text" class="form-control" placeholder="Kode MK" disabled></th>

			</tr>
			<tr>
				<td>
				<select class="form-control">
					<?php 
					while ($rows = mysqli_fetch_assoc($results)) { ?>
						<option>
							<?php 
							echo $rows['nama'] ;

							?>
						</option>
						<?php 

					}
					?>
				</select>
			</td>
			<td>
				<select class="form-control">
					<?php 
						while($rows=mysqli_fetch_assoc($res)){
						?>
							<option>
								<?php 
									echo $rows['nama_kul_ind'];
								?>
							</option>
						<?php 
						}
					?>
				</select>
			</td>
			</tr>
		
			
		
	</table>
</div>
 -->
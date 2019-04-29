<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Dosen */

$this->title = 'Tambah Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// var_dump($model);die;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dosen-create">

	<?= $uiHelper->renderContentSubHeader('Tambah Dosen', ['icon' => 'fa fa-plus']);?>

	<?=$uiHelper->beginContentBlock(['id' => 'grid-dosen',
     	'header' => 'Data Dosen',
      	'type' => 'danger'
      	])?>

      	<?php 
        // var_dump($model);die;
        echo $this->render('_form', [
            'model' => $model,
            'pendMdl' => $pendMdl,
      			'pangkat' =>$pangkat,
            'jabatan' => $jabatan,
            'ikatanKerja' => $ikatanKerja,
            'jenjang' => $jenjang,
            'prodi' => $prodi,
            'gbk' => $gbk
            ]) ;
                ?>

    <?=$uiHelper->endContentBlock()?>

</div>

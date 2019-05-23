<?php

use yii\helpers\Html;
use yii\db\Query;


use yii\helpers\Url;
use backend\modules\rppx\models\AdakPengajaran;
/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */

$this->title = 'Create Penugasan Pengajaran';
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Pengajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-pengajaran-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formAsdos', [
    	'jlhDosen'=>$jlhDosen,
        'jlhAsdos'=>$jlhAsdos,
    	'model' => $model,
    	// 'baris'=>$baris,
    	// 'colom'=>$colom,
    	'modelPengajaran' => $modelPengajaran,
    	'semester'=> $semester,
    ]) ?>
    <br>
    <!-- <a style="margin-left: 745px;" href="<?=Url::toRoute(['/rppx/penugasan-pengajaran/create','semester'=>3]) ?>"><button class="btn btn-primary">Asissten Dosen</button></a> -->

</div>
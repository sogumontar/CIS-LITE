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
    	// 'baris'=>$baris,
    	// 'colom'=>$colom,
    	'modelPengajaran' => $modelPengajaran,
    	'semester'=> $semester,
    ]) ?>

</div>
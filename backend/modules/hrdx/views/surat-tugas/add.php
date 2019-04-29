<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\SuratTugas */

$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Tambah Surat Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

?>
<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>


    <?= $this->render('_form', [
        'model' => $model,
        'pemberi_tugas' => $pemberi_tugas,
        'penerima_tugas_1' =>$penerima_tugas_1,
        'penerima_tugas_2' =>$penerima_tugas_2,
    ]) ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>

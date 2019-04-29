<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Asrama;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\AsramaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pilih Asrama';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$asrama->name, 'url' => ['asrama/view-detail-asrama', 'id' => $asrama->asrama_id]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$asramaCount = Asrama::find()->andWhere('deleted != 1')->all();
?>
<div class="asrama-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $uiHelper->renderLine(); ?>

    <?=$uiHelper->beginContentRow() ?>

        <?php
            $i = 0;
            foreach ($asramaCount as $row) {
                $i++;
        ?>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">

                    <h1><?php echo $row->name; ?></h1>

                <div class="icon">
                    <h1 style="color: #fff;">
                        <?php echo $row->jumlah_mahasiswa; ?>/<?php echo $row->kapasitas; ?>
                    </h1>
                </div>

                    <p><?php echo $row->lokasi; ?></p>
                </div>
                <a href="<?= Url::to(['move-penghuni-kamar', 'id' => $_GET['id'], 'id_kamar' => $_GET['id_kamar'], 'id_asrama' => $row->asrama_id]) ?>" class="small-box-footer">Pilih <i class="fa fa-mouse-pointer"></i></a>
            </div>
        </div>

    <?php
        }
    ?>


    <?=$uiHelper->endContentRow() ?>

</div>

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

$this->title = 'Asrama';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$asramaCount = Asrama::find()->andWhere('deleted != 1')->all();
?>
<div class="asrama-index">

    <div class="pull-right">
        Pengaturan
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-flat btn-set btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span style="font-size: 18px;" class="fa fa-gear"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li>
                    <a href="<?= Url::to(['asrama/add']) ?>"><i class="fa fa-plus"></i>Tambah Asrama</a>
                </li>
                <li>
                    <a href="<?= Url::to(['asrama/import-excel']) ?>"><i class="fa fa-upload"></i>Import Mahasiswa</a>
                </li>
                <li>
                    <a href="<?= Url::to(['asrama/template-excel']) ?>"><i class="fa fa-file"></i>Download Template</a>
                </li>
            </ul>
        </div>
    </div>

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
                <a href="<?= Url::to(['asrama/view-detail-asrama', 'id' => $row->asrama_id]) ?>" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    <?php
        }
    ?>


    <?=$uiHelper->endContentRow() ?>

</div>

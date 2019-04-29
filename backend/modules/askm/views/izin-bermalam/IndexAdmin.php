<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\ToolsColumn;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Pedoman;
use backend\modules\askm\models\IzinBermalam;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinBermalamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Izin Bermalam';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$asramaCount = Asrama::find()->andWhere('deleted != 1')->all();
$requestCount = IzinBermalam::find()->where('status_request_id = 1')->andWhere('deleted != 1')->all();
$pedoman = Pedoman::find()->where('deleted!=1')->andWhere(['jenis_izin' => 1])->one();
?>
<div class="izin-bermalam-index">

    <?= $uiHelper->renderContentHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>

    <?php

        $toolbarItemMenu =
            "
            <a href='".Url::to(['izin-bermalam/izin-by-admin-index'])."' class='btn btn-info'><i class='fa fa-history'></i><span class='toolbar-label'>Daftar Request</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-admin-add'])."' class='btn btn-success'><i class='fa fa-book'></i><span class='toolbar-label'>Request IB</span></a>
            <a href='".Url::to(['izin-bermalam/excel'])."' class='btn btn-warning'><i class='fa fa-archive'></i><span class='toolbar-label'>Rekapitulasi IB</span></a>
            <!-- <a href='".Url::to(['pedoman/edit'])."' class='btn btn-primary'><i class='fa fa-pencil'></i><span class='toolbar-label'> Edit Pedoman</span></a> -->
            "
            ;

    ?>

    <?=Yii::$app->uiHelper->renderToolbar([
    'pull-left' => true,
    'groupTemplate' => ['groupStatusExpired'],
    'groups' => [
        'groupStatusExpired' => [
            'template' => ['filterStatus'],
            'buttons' => [
                'filterStatus' => $toolbarItemMenu,
            ]
        ],
    ],
    ]) ?>

    <br>

    <?=$uiHelper->beginContentRow() ?>

        <?php
            $i = 0;
            foreach ($asramaCount as $row) {
                $i++;
        ?>

        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">

                    <h4><?php echo $row->name; ?></h4>

                <div class="icon" style="color: #fff; font-size: 46px; padding-top: 37px;">
                    <i>
                        <?php

                        $count = 0;
                        foreach($requestCount as $c){
                            if($c->status_request_id == 1 && isset($c->dim->dimKamar)){
                                foreach($c->dim->dimKamar as $k){
                                    if($k->kamar->asrama_id == $row->asrama_id){
                                        $count++;
                                    }
                                    break;
                                }
                            }
                        }

                        echo $count;

                        ?>
                    </i>
                </div>

                    <p>Request Masuk :</p>
                </div>
                <a href="izin-by-admin-index?IzinBermalamSearch%5Bdim_asrama%5D=<?php echo $i; ?>&IzinBermalamSearch%5Bstatus_request_id%5D=1" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    <?php
        }
    ?>


    <?=$uiHelper->endContentRow() ?>

    <?=$uiHelper->beginContentRow() ?>

        <?=$uiHelper->beginContentBlock(['id' => 'grid-system2',
            'header' => $pedoman->judul,
            'type' => 'danger',
            'width' => 12,
        ]); ?>

        <?= $pedoman->isi ?>

        <?= $uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

</div>

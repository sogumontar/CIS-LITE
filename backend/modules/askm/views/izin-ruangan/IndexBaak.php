<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use backend\modules\askm\models\StatusRequest;
use backend\modules\askm\models\Pedoman;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinPenggunaanRuanganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Izin Penggunaan Ruangan';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$pedoman = Pedoman::find()->where('deleted!=1')->andWhere(['jenis_izin' => 3])->one();
?>
<div class="izin-ruangan-index">

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('List request', ['izin-by-baak-index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
        'options' => [
            'id' => 'calendar',
            'lang' => 'id',
        ],
        'clientOptions' => [
            'timeFormat' => 'H:mm',
        ],
        'events' => $events,
        // 'ajaxEvents' => Url::to(['/timetrack/default/jsoncalendar'])
    )); ?>

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

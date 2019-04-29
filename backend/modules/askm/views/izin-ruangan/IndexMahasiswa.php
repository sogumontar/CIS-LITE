<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use backend\modules\askm\models\StatusRequest;
use backend\modules\askm\models\Pedoman;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinPenggunaanRuanganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Izin Penggunaan Ruangan';
$this->params['breadcrumbs'][] = $this->title;
$pedoman = Pedoman::find()->where('deleted!=1')->andWhere(['jenis_izin' => 3])->one();

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-ruangan-index">

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <p>
        <?= Html::a('Request', ['izin-by-mahasiswa-add'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Izin Saya', ['izin-by-mahasiswa-index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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

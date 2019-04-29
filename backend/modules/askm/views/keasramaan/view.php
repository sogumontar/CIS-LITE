<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Keasramaan;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\KeasramaanPegawai */

$this->title = 'Data Diri Keasramaan';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
$keasramaan = Keasramaan::find()->where('deleted!=1')->andWhere(['pegawai_id' => $pegawai->pegawai_id])->one();
?>
<div class="keasramaan-pegawai-view">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit'],
                'buttons' => [
                    'edit' => ['url' => Url::to(['edit', 'id'=> $keasramaan->keasramaan_id]), 'label'=> 'Edit Data Diri', 'icon'=>'fa fa-pencil'],
                ],
            ]) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'nama_keasramaan',
                'label' => 'Nama',
                'value' => function($model){
                        return $model->pegawai->nama;
                    }
            ],
            [
                'attribute' => 'telepon_keasramaan',
                'label' => 'Telepon',
                'value' => function($model){
                        return $model->pegawai->telepon;
                    }
            ],
            'no_hp',
            'email',

        ],
    ]) ?>

</div>

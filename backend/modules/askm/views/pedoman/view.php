<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pedoman */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Pedoman', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pedoman-view">

    <div class="pull-right">
        Pengaturan
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-flat btn-set btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><span style="font-size: 18px;" class="fa fa-gear"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                <li>
                    <a href="<?= Url::to(['pedoman/edit', 'id' => $model->pedoman_id]) ?>"><i class="fa fa-pencil"></i>Edit</a>
                </li>
            </ul>
        </div>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $uiHelper->renderLine(); ?>

    <?=$uiHelper->beginContentRow() ?>
        
        <?=$uiHelper->beginContentBlock(['id' => 'grid-system2',
            'width' => 12,
            ]); ?>
          <?= $model->isi ?>

        <?= $uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

</div>

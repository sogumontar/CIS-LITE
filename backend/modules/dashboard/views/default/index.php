<?php
use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\PuroMessengerApiClient;


$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
// $this->params['header'] = "Dashboard";


$ui = \Yii::$app->uiHelper;
?>
<?=$ui->beginContentRow() ?>

    <?= $ui->beginContentBlock(['id' => 'left-block',
            'width' => 6,
            // 'header' => 'left'
        ]) ?>
        
        <?=$ui->beginSingleRowBlock(['id' => 'courses-block', 'header' => 'Panel 1']) ?>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        <?=$ui->endSingleRowBlock() ?>

        <?=$ui->beginSingleRowBlock(['id' => 'links-block', 'header' => 'Panel 2']) ?>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        <?=$ui->endSingleRowBlock() ?>
    <?=$ui->endContentBlock()?>
       
    <?=$ui->beginContentBlock(['id' => 'right-block',
      'width' => 6,
      // 'header' => 'right'
        ]) ?>
        
        <?=$ui->beginSingleRowBlock(['id' => 'pengumuman-block', 'header' => 'Pengumuman']) ?>
        panel 3
        <?=$ui->endSingleRowBlock() ?>

        <?=$ui->beginSingleRowBlock(['id' => 'charts-block', 'header' => 'Progress']) ?>
        panenl 4
        <?=$ui->endSingleRowBlock() ?>

    <?=$ui->endContentBlock()?>

<?=$ui->endContentRow() ?>

<?=$ui->beginSingleRowBlock(['id' => 'courses-block', 'header' => 'Full Width Panel']) ?>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
<?=$ui->endSingleRowBlock() ?>


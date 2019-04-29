<?php
    use yii\widgets\DetailView;
    use yii\helpers\Html;
  	use common\helpers\LinkHelper;

    $uiHelper = \Yii::$app->uiHelper;
?>
	<?=$uiHelper->renderContentSubHeader($arsip->judul_arsip) ?>
	<?=$uiHelper->renderLine(); ?>
<div class="arsip-view">
	<div class="container-fluid">

	<?= $uiHelper->beginContentRow()?>
		<?=$uiHelper->beginContentBlock(['id'=>'grid-system1',
			'width'=>12])?>
				<?= $arsip->desc?>
		<?= $uiHelper->endContentBlock()?>	

		<?=$uiHelper->beginContentBlock(['id'=>'grid-system1',
			'width'=>12,
			'type'=>'danger'])?>
		        <?php foreach($arsipFile as $key=>$value){ ?>
		        <ul>
		            <li><?= LinkHelper::renderLink(['options'=>'target = _blank','label'=>$value->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($value->kode_file)])?></li>
		        </ul>
		        <?php } ?>
		<?=$uiHelper->endContentBlock()?>
	<?= $uiHelper->endContentRow()?>
	</div>
</div>

<?php
use backend\modules\hrdx\assets\HrdxAsset;
$uiHelper = \Yii::$app->uiHelper;
HrdxAsset::register($this);
?>

<style type="text/css">
	.wrapper{
		font-family: "Times New Roman", Times, serif;
		font-size: 14px;
		text-align: justify;
		text-justify: inter-word;
	}

	.icon{
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 50px;
		margin-bottom: 10px;
	}

	.judul{
		text-align: center;
		margin-bottom: 30px;
	}

	.table-modify{
	    width: 100%;
	    border-spacing: 0px;
	    border-collapse: collapse;
	}

	.table-modify th{
	    border: 1px solid #b8b8b8;
	    text-align: center;
	    padding-top: 5px;
	    padding-bottom: 5px;
	}

	.table-modify td{
	    border: 1px solid #b8b8b8;
	    padding-top: 3px;
	    padding-bottom: 3px;
	    padding-left: 5px;
	}
</style>

<?= $uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'basic-grid','width' => 12,]) ?>


      <?= $uiHelper->endContentBlock() ?>
<?= $uiHelper->endContentRow() ?>
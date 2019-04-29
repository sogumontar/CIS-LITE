<?php
namespace backend\modules\rakx\assets;

use yii\web\AssetBundle;

class RakxAsset extends AssetBundle {
	public $sourcePath = '@backend/modules/rakx/assets/web';

	public $css = ['css/reviewProgram.css'];

	public $js = [];

	public $depends = [
		'backend\themes\v2\assets\V2Asset'
	];

	public $publishOptions = [
    	'forceCopy' => true
	];
}
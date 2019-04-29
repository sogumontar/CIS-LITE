<?php
namespace backend\modules\askm\assets;

use yii\web\AssetBundle;

class AskmAsset extends AssetBundle {
	public $sourcePath = '@backend/modules/askm/assets/web';

	public $css = [];

	public $js = [];

	public $depends = [
		'backend\themes\v2\assets\V2Asset'
	];

	public $publishOptions = [
    	'forceCopy' => true
	];
}
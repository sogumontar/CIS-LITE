<?php
namespace backend\modules\inst\assets;

use yii\web\AssetBundle;

class InstAsset extends AssetBundle {
	public $sourcePath = '@backend/modules/inst/assets/web';

	public $css = ['Treant/Treant.css', 'css/treeView.css', 'css/strukturs.css', 'css/pejabat.css'];

	public $js = ['Treant/Treant.js', 'Treant/vendor/raphael.js'];

	public $depends = [
		'backend\themes\v2\assets\V2Asset'
	];

	public $publishOptions = [
    	'forceCopy' => true
	];
}
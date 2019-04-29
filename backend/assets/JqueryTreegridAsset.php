<?php
namespace backend\assets;

use yii\web\AssetBundle;

class JqueryTreegridAsset extends AssetBundle {
	// public $basePath = '@webroot';
	// public $baseUrl = '@web';

	public $sourcePath = '@backend/assets/web';

	public $css = ['css/jquery-treegrid/jquery.treegrid.css'];

	public $js = ['js/jquery-treegrid/jquery.treegrid.js',
				  'js/jquery-treegrid/jquery.cookie.js',
				];

	public $depends = [
			'common\assets\MainAsset',
	];


}
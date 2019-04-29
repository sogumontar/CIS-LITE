<?php
namespace backend\themes\v2\assets;

use yii\web\AssetBundle;

/**
 * asset class for v2 theme/layout
 *
 * testing gitlab
 */
class V2Asset extends AssetBundle {
	public $sourcePath = '@backend/themes/v2/assets/web';

	public $css = [
				   	'plugins/iCheck/minimal/blue.css',
				   	'css/font-awesome.min.css',
				   	'css/ionicons.min.css',
					'css/default.min.css',
				   	'css/skins/_all-skins.min.css',
				   	'plugins/nprogress/nprogress.css',
				   	'css/v2.css',
				   ];

	public $js = [
				  	'plugins/iCheck/icheck.min.js',
				  	'plugins/slimScroll/jquery.slimscroll.min.js',
				  	'plugins/fastclick/fastclick.min.js',
				  	'plugins/nprogress/nprogress.js',
				  	'plugins/bootbox/bootbox.min.js',
					'js/app.js',
				  	'js/v2.js',
				];

	public $depends = [
			'backend\assets\AppAsset',
	];

	public $publishOptions = [
    	'forceCopy' => false
	];
}
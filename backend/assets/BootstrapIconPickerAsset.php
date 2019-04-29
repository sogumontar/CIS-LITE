<?php
namespace backend\assets;

use yii\web\AssetBundle;

class BootstrapIconPickerAsset extends AssetBundle {
	// public $basePath = '@webroot';
	// public $baseUrl = '@web';
	public $sourcePath = '@backend/assets/web';

	public $css = ['css/bootstrap-iconpicker/bootstrap-iconpicker.min.css'];

	public $js = ['js/bootstrap-iconpicker/iconset/iconset-fontawesome-4.2.0.min.js',
				  'js/bootstrap-iconpicker/bootstrap-iconpicker.min.js',
				];

	public $depends = [
			'common\assets\MainAsset',
	];

}
<?php
namespace common\assets;

use yii\web\AssetBundle;

/**
 * asset bundle for bootstrap-notify js plugin
 */
class BootstrapNotifyAsset extends AssetBundle {
	public $sourcePath = '@common/assets/web';


	public $css = [];

	public $js = ['js/bootstrap-notify/bootstrap-notify.min.js'];

	public $depends = [
			'common\assets\MainAsset',
	];


}
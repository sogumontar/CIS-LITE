<?php
namespace common\assets;

use yii\web\AssetBundle;

/**
 * asset bundle for bootstrap-notify js plugin
 */
class EModalAsset extends AssetBundle {
	public $sourcePath = '@common/assets/web';


	public $css = [];

	public $js = ['js/emodal/eModal.min.js'];

	public $depends = [
			'common\assets\MainAsset',
	];


}
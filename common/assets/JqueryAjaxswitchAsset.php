<?php
namespace common\assets;

use yii\web\AssetBundle;

class JqueryAjaxswitchAsset extends AssetBundle {
	public $sourcePath = '@common/assets/web';


	public $css = [];

	public $js = ['js/jquery.ajaxswitch.js'];

	public $depends = [
			'common\assets\MainAsset',
			'common\assets\EModalAsset',
	];


}
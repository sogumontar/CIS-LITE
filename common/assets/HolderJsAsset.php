<?php
namespace common\assets;

use yii\web\AssetBundle;

class HolderJsAsset extends AssetBundle {
	public $sourcePath = '@common/assets/web';


	public $css = [];

	public $js = ['js/holder.min.js'];

	public $depends = [
			'common\assets\MainAsset',
	];


}
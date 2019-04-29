<?php
namespace backend\modules\hrdx\assets;

use yii\web\AssetBundle;

class HrdxAsset extends AssetBundle {
	public $sourcePath = '@backend/modules/hrdx/assets/web';


	public $css = ['css/select2-bootstrap.css','css/select2.css'];

	public $js = ['js/select2.min.js','js/select2.js'];

	public $depends = [
			'backend\assets\AppAsset',
	];


}
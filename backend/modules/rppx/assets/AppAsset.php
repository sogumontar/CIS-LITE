<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\modules\rppx\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/modules/rppx/assets/web';

	public $css = [];

	public $js = [];

    public $depends = [
        'common\assets\MainAsset',
    ];

    public $publishOptions = [
    	'forceCopy' => true
	];
}

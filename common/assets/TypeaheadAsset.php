<?php
namespace common\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Asset managers for Twitter Typeahead and bloodhound (bundled)
 * 
 * @author Marojahan Sigiro <marojahan@gmail.com>
 * @since 0.1
 */
class TypeaheadAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/web';
    public $js = [
        'js/typeahead/typeahead.bundle.min-0.10.5.js',
        'js/typeahead/handlebars-v3.0.3.js'
    ];
    public $css = [
    	'css/typeahead/typeahead.css'
    ];

    public $depends = [
        'common\assets\MainAsset',
    ];
}

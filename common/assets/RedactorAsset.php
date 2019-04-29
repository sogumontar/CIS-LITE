<?php
namespace common\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Asset managers for Imperavi Redactor WYSIWYG editor
 * http://imperavi.com/redactor/ 
 * 
 * @author Marojahan Sigiro <marojahan@gmail.com>
 * @since 0.1
 */
class RedactorAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/web';
    public $js = [
        'js/redactor/redactor.min.js',
        'js/redactor/plugins/table/table.js',
        'js/redactor/plugins/fontcolor/fontcolor.js'
    ];
    public $css = [
        'css/redactor/redactor.css'
    ];
    public $depends = [
        'common\assets\MainAsset',
    ];

    public function init() {

        $appLanguage = strtolower(substr(Yii::$app->language , 0, 2)); //First 2 letters

        if($appLanguage != 'en')
            $this->js[] = 'js/redactor/lang/' . $appLanguage . '.js';

        parent::init();
    }
}

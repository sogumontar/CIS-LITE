<?php
namespace common\widgets;

use Yii;
use common\assets\RedactorAsset;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Imperavi Redactor Widget For Yii2 class file.
 *
 * Refactored for systemx-core by:
 * 
 * @author Marojahan Sigiro <marojahan@gmail.com>
 *
 * code based on
 * 
 * @property array $plugins
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Alexander Yaremchuk <alwex10@gmail.com>
 *
 * @version 2.0
 *
 * @link https://github.com/asofter/yii2-imperavi-redactor
 * @link http://imperavi.com/redactor
 * @license https://github.com/asofter/yii2-imperavi-redactor/blob/master/LICENSE.md
 */
class Redactor extends \yii\base\Widget
{
    /**
     * @var array the options for the Imperavi Redactor.
     * Please refer to the corresponding [Imperavi Web page](http://imperavi.com/redactor/docs/)  for possible options.
     */
    public $options = [];

    /**
     * @var array the html options.
     */
    public $htmlOptions = [];

    /**
     * @var array plugins that you want to use
     */
    public $plugins = [];

    /*
     * @var object model for active text area
     */
    public $model = null;

    /*
     * @var string selector for init js scripts
     */
    protected $selector = null;

    /*
     * @var string name of textarea tag or name of attribute
     */
    public $attribute = null;

    /*
     * @var string value for text area (without model)
     */
    public $value = '';

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->selector = '#' . $this->htmlOptions['id'];

        if (!is_null($this->model)) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->htmlOptions);
        } else {
            echo Html::textarea($this->attribute, $this->value, $this->htmlOptions);
        }

        RedactorAsset::register($this->getView());
        $this->registerClientScript();
    }

    /**
     * Registers Imperavi Redactor JS
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

        if (!isset($this->options['lang']) || empty($this->options['lang'])) {
            $this->options['lang'] = strtolower(substr(Yii::$app->language, 0, 2));
        }

        //temporary disabled dynamic plugin register
        /*// Insert plugins in options
        if (!empty($this->plugins)) {
            $this->options['plugins'] = $this->plugins;

            foreach ($this->options['plugins'] as $plugin) {
                $this->registerPlugin($plugin);
            }
        }*/
        //temporary all plugin assets must be loaded in asset file
        //'table' plugin enabled as default
        $this->options['plugins'] = ['table', 'fontcolor'];
        $options = empty($this->options) ? '' : Json::encode($this->options);
        $js = "jQuery('" . $this->selector . "').redactor($options);";
        $view->registerJs($js);
    }

    /**
     * Registers a specific Imperavi plugin and the related events
     * @param string $name the name of the Imperavi plugin
     */
    /*protected function registerPlugin($name)
    {
        $asset = "yii\\imperavi\\" . ucfirst($name) . "ImperaviRedactorPluginAsset";
        // check exists file before register (it may be custom plugin with not standard file placement)
        $sourcePath = Yii::$app->vendorPath . '/asofter/yii2-imperavi-redactor/' . str_replace('\\', '/', $asset) . '.php';
        if (is_file($sourcePath)) {
            $asset::register($this->getView());
        }
    }*/
}

<?php
namespace common\widgets;

use Yii;
use common\assets\TypeaheadAsset;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Twitter Typeahead Widget For Yii2 class file.
 *
 * 
 * @author Marojahan Sigiro <marojahan@gmail.com>
 */
class Typeahead extends \yii\base\Widget
{
    /**
     * data source name
     * @var null
     */
    public $sourceName = null;

    /**
     * Typeahead autocomplete data source
     * the url will be suffixed with '?query=query_string'
     * @var Remote API that provide JSON data
     */
    public $sourceApiBaseUrl = null;

    /**
     * Typeahead autocomplete data source
     * @var array
     */
    public $sourceData = [];

    public $selector = null;
    public $htmlOptions = [];
    public $htmlOptionsReal = [];
    public $options = [];

    public $withSubmitButton = false;

    public $model = null;

    /*
     * @var string name of input tag or name of attribute
     */
    public $attribute = null;
    
    /*
     * @var string value for text area (without model)
     */
    public $value = '';

    public $template = null;
    public $defaultTemplate = "<p style='padding:4px'>{{data}} </p>";

    public function init()
    {
        parent::init();
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
        $this->htmlOptionsReal['id'] = $this->htmlOptions['id']."-real";

        if($this->template === null){
            $this->template = $this->defaultTemplate;
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->selector = '#' . $this->htmlOptions['id'];

        if (!is_null($this->model)) {
            echo Html::activeHiddenInput($this->model, $this->attribute, $this->htmlOptionsReal);
        } else {
            echo Html::hiddenInput($this->attribute, $this->value, $this->htmlOptionsReal);
        }

        echo Html::textInput($this->attribute.'-fake', $this->value, $this->htmlOptions);
        if($this->withSubmitButton){
                echo "<span class=typeahead-button>";
                echo Html::submitButton('<i class="fa fa-search"></i>');
                echo "</span>";
        }   
        TypeaheadAsset::register($this->getView());
        $this->registerClientScript();
    }

    /**
     * Registers Typeahead JS
     */
    protected function registerClientScript()
    {
        $view = $this->getView();

        $bloodhoundSource = $this->getTypeheadSource();
        
        $options = empty($this->options) ? '' : Json::encode($this->options);
        $js = '';

        if (!$this->sourceApiBaseUrl) {
            $js = "jQuery('" . $this->selector . "').typeahead($options, {name : '".$bloodhoundSource['name']."', source: ".$bloodhoundSource['source']."});";
        }else{
            //remote data
            $js = $bloodhoundSource['source'].".clearPrefetchCache();".
                  $bloodhoundSource['source'].".clearRemoteCache();".
                  $bloodhoundSource['source'].".initialize();
                  jQuery('" . $this->selector . "').typeahead($options, 
                                                              {name : '".$bloodhoundSource['name']."',
                                                               displayKey: 'data', 
                                                               async: true,
                                                               source: ".$bloodhoundSource['source'].".ttAdapter(),
                                                               templates: {
                                                                    suggestion: Handlebars.compile(\"".$this->template."\"),
                                                                    empty: Handlebars.compile(\"<b>No data found for '{{query}}'</b>\")
                                                                }
                                                               }).on('typeahead:selected', onSelected);
                function onSelected(e, datum) {
                    $('#' + e.target.id + '-real').val(datum.value);
                }                                              ";
        }

        $view->registerJs($js);
    }

    protected function getTypeheadSource(){
        $view = $this->getView();
        $typeaheadSource = [];
        $typeaheadSource['source'] = $this->sourceName ? $this->sourceName : "data";
        $typeaheadSource['name'] = $typeaheadSource['source'];
        $js = '';
        if (!$this->sourceApiBaseUrl) {
            //local data source
            $js = "var ".$typeaheadSource['name']."Data = ".Json::encode($this->sourceData).";

                    var ".$typeaheadSource['name']." = new Bloodhound({
                      datumTokenizer: Bloodhound.tokenizers.whitespace,
                      queryTokenizer: Bloodhound.tokenizers.whitespace,
                      local: ".$typeaheadSource['name']."Data
                    });";
        }else{
            $js = "var ".$typeaheadSource['name']." = new Bloodhound({
                  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('data'),
                  queryTokenizer: Bloodhound.tokenizers.whitespace,
                  remote: {
                    url: '".$this->sourceApiBaseUrl."?query=%QUERY',
                    wildcard: '%QUERY'
                  }
                });";
        }
        $view->registerJs($js);

        return $typeaheadSource;
    }
}

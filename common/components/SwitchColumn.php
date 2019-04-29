<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;
use common\assets\EModalAsset;

use yii\grid\Column;
use Closure;

/**
 * SwitchColumn displays a column of row numbers (1-based).
 *
 * To add a Active On Off Switch Column to the [[GridView]], add it to the [[GridView::columns|columns]] configuration as follows:
 *
 * ```php
 * 'columns' => [
 *     // ...
 *     [
 *         'class' => 'common\components\SwitchColumn',
 *         // you may configure additional properties here
 *     ],
 * ]
 * ```
 *
 * @author Marojahan Sigiro
 */
class SwitchColumn extends Column
{
    public $header = '#';

    public $getVarOn = "switch=1";
    
    public $getVarOff = 'switch=0';

    public $className = "btn btn-default";
    public $url = '';

    /*attribut yang dijadikan flag, boolen, untuk menentukan nilai swith, on atau off
      bisa di set menggunakan anonymous function (closure) yang me-return boolean 
    */
    public $flag = 'status';

    public function init()
    {
        parent::init();
        EModalAsset::register($this->grid->getView());
        //register javascipt here to handle click and switch
        $this->grid->getView()->registerJs("jQuery('.switch-slider-column').click(function(){
            var btn = $(this);

            if(btn.hasClass('switch-container-error')){
                return;
            }

            btn.children(':first').removeClass('switch-toggle-on');
            btn.children(':first').addClass('switch-toggle-progress');
            var url = window.location.href;
            var btn_url = btn.attr('href');
            if(typeof btn_url !== typeof undefined && btn_url !== false){
                url = btn_url;
            }

            if(btn.hasClass('switch-container-on')){
                //fire off request
                console.log('fire switch-off request');
                $.post(url, {id : btn.attr('data-id'), switchcolumnreq: 0}, function (response){
                    if(response.SUCCESS){
                        btn.removeClass('switch-container-on');
                        btn.children(':first').removeClass('switch-toggle-progress');
                    }else if (response.FAILED) {
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                        eModal.alert('<div class=\"alert alert-danger\">'+response.message+'<div>', 'Failed');
                        console.log('Turning swith failed with message:'+ response.message);
                    }else{
                        btn.removeClass('switch-container-on');
                        btn.addClass('switch-container-error');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                        btn.children(':first').addClass('switch-toggle-error');
                        eModal.alert('<div class=alert alert-danger>XHR Request Failed, Request is not handled properly on backend system !!</div>','Error');
                    }
                })
                .fail(function(){
                    btn.removeClass('switch-container-on');
                    btn.addClass('switch-container-error');
                    btn.children(':first').removeClass('switch-toggle-progress');
                    btn.children(':first').addClass('switch-toggle-on');
                    btn.children(':first').addClass('switch-toggle-error');
                    eModal.alert('<div class=alert alert-danger>XHR Request Failed</div>','Error');
                    console.log('ajax request failed');
                });

            }else {
                //fire on request
                console.log('fire switch-on request');
                $.post(url, {id : btn.attr('data-id'), switchcolumnreq: 1}, function (response){
                    if(response.SUCCESS){
                        btn.addClass('switch-container-on');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-on');
                    }else if (response.FAILED) {
                        btn.children(':first').removeClass('switch-toggle-progress');
                        eModal.alert(response.message, 'Failed');
                        console.log('Turning swith failed with message:'+ response.message);
                    }else{
                        btn.addClass('switch-container-error');
                        btn.children(':first').removeClass('switch-toggle-progress');
                        btn.children(':first').addClass('switch-toggle-error');
                        eModal.alert('<div class=alert alert-danger>XHR Request Failed, Request is not handled properly on backend system !!</div>','Error');
                    }
                })
                .fail(function(){
                    btn.addClass('switch-container-error');
                    btn.children(':first').removeClass('switch-toggle-progress');
                    btn.children(':first').addClass('switch-toggle-error');
                    eModal.alert('<div class=alert alert-danger>XHR Request Failed</div>','Error');
                    console.log('ajax request failed');
                });
            }
        });");
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $isOn = false;
        $href = '';
        if($this->url !== ''){
            $href = 'href="'.$this->url.'"';
        }

        if ($this->flag instanceof Closure) {
            $isOn = call_user_func($this->flag, $model, $key, $index, $this);
        } else {
            $isOn = ($model->{$this->flag})? true:false;
        }

        if($isOn){
            return '<div '.$href.' data-id="'.$key.'" class="switch-slider-column switch-container switch-container-on">'.
                    '<div class="switch-toggle switch-toggle-on"></div>'.
                   '</div>';
        }else{
             return '<div '.$href.' data-id="'.$key.'" class="switch-slider-column switch-container">'.
                    '<div class="switch-toggle"></div>'.
                   '</div>';;
        }
    }
}

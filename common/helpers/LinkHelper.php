<?php
namespace common\helpers;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * Class to render link button/text with various options (ajax, icon, etc)
 *
 * @author Marojahan Sigiro <marojahan@del.ac.id>
 * @since 0.1
 */
class LinkHelper {
	/**
	 * render single pjax link with option
	 * [
	 * 	'label' => 'label',
	 * 	'url' => 'url',
	 * 	'icon' => 'glyphicon or fa icon, full class must be supplied',
	 * 	'target' => 'target conteiner div id'
	 * 	'pjax'	=> true|false
	 * 	'pushstate' => true|false, default true //push browser history or not?
	 * 	'class' => custom button class for button link
	 * 	'tooltip' => custom tooltip or alt text
	 * 	'options' => additional elemen options e.g: "data-toggle='tab'"
	 * ]
	 * @param  array $link link options
	 * @return link element
	 */
	public static function renderLink($link){
		$pjaxOption = '';
		$tooltipOption = '';

		if(!is_array($link)){
			throw new InvalidConfigException("Parameter harus berupa array!!");
		}

		if(ArrayHelper::getValue($link, 'pjax', false)){
			if(!ArrayHelper::getValue($link, 'target', false)){
				throw new InvalidConfigException("Target container id harus dispesifikasikan!!");
			}

			if(!ArrayHelper::getValue($link, 'pushstate', true)){
				$pjaxOption = " nopush-pjax='".$link['target']."'";
			}else{
				$pjaxOption = " data-pjax='".$link['target']."'";
			}

		}

		if(ArrayHelper::getValue($link, 'tooltip', false)){
			$tooltipOption = " data-toggle='tooltip' data-placement='bottom' title='".$link['tooltip']."'";
		}

		if(!ArrayHelper::getValue($link, 'label', false)){
			throw new InvalidConfigException("Link harus memiliki label!!");
		}

		$htmlOptions =  ArrayHelper::getValue($link, 'options', '');

		if(ArrayHelper::getValue($link, 'icon', false)){
			$link['label'] = "<span class='".$link['icon']."' style='margin-right: 2px;'></span>".$link['label'];
		}

		$element = "<a href='".$link['url']."'".$pjaxOption." ".$tooltipOption." ".$htmlOptions.">".$link['label']."</a>";

		return $element;
	}
	/**
	 * see renderLink($link)
	 */
	public static function renderLinkButton($link){
		$class = (ArrayHelper::getValue($link, 'class', false))? $link['class']:"btn-success btn-sm";
		$pjaxOption = '';
		$tooltipOption = '';

		if(!is_array($link)){
			throw new InvalidConfigException("Parameter harus berupa array!!");
		}

		if(ArrayHelper::getValue($link, 'pjax', false)){
			if(!ArrayHelper::getValue($link, 'target', false)){
				throw new InvalidConfigException("Target container id harus dispesifikasikan!!");
			}
			
			if(!ArrayHelper::getValue($link, 'pushstate', true)){
				$pjaxOption = " nopush-pjax='".$link['target']."'";
			}else{
				$pjaxOption = " data-pjax='".$link['target']."'";
			}
		}

		if(ArrayHelper::getValue($link, 'tooltip', false)){
			$tooltipOption = " data-toggle='tooltip' data-placement='bottom' title='".$link['tooltip']."'";
		}

		if(!ArrayHelper::getValue($link, 'label', false)){
			throw new InvalidConfigException("Link harus memiliki label!!");
		}
		if(ArrayHelper::getValue($link, 'icon', false)){
			$link['label']= "<span class='glyphicon ".$link['icon']."' style='margin-right: 2px;'></span>".$link['label'];
		}
		
		$htmlOptions =  ArrayHelper::getValue($link, 'options', '');

		$element = "<a class='btn ".$class."' href='".$link['url']."'".$pjaxOption." ".$tooltipOption." ".$htmlOptions.">".$link['label']."</a>";

		return $element;
	}

	/**
	 * see renderLink($link), without pjax
	 */
	public static function renderLinkIcon($link){
		$pjaxOption = '';
		$tooltipOption = '';
		

		if(!is_array($link)){
			throw new InvalidConfigException("Parameter harus berupa array!!");
		}

		if(ArrayHelper::getValue($link, 'pjax', false)){
			if(!ArrayHelper::getValue($link, 'target', false)){
				throw new InvalidConfigException("Target container id harus dispesifikasikan!!");
			}

			if(!ArrayHelper::getValue($link, 'pushstate', true)){
				$pjaxOption = " nopush-pjax='".$link['target']."'";
			}else{
				$pjaxOption = " data-pjax='".$link['target']."'";
			}
		}

		if(ArrayHelper::getValue($link, 'tooltip', false)){
			$tooltipOption = " data-toggle='tooltip' data-placement='bottom' title='".$link['tooltip']."'";
		}

		if(!ArrayHelper::getValue($link, 'icon', false)){
			throw new InvalidConfigException("Link harus memiliki icon!!");
		}
		
		$link['label']= "<span class='".$link['icon']."'></span>";
		
		$htmlOptions =  ArrayHelper::getValue($link, 'options', '');

		$element = "<a href='".$link['url']."'".$pjaxOption." ".$tooltipOption." ".$htmlOptions.">".$link['label']."</a>";

		return $element;
	}

	/**
	 * traditional pjax request detector (pjax link created manually or with createPjaxLink(..) function)
	 * differ with yii2 pjax widget
	 * @return boolean true if pjax
	 */
	public static function isPjaxRequest(){
		return \Yii::$app->request->headers->has('X-PJAX');
	}
	/**
	 * register javascript yang akan dieksekusi pada saat request pjax mulai/berakhir
	 * @param  string $on     start (pjax start)/end (end of pjax request)
	 * @param  string $target pjax target selector
	 * @param  string $js     javascript to be executed
	 * @param  yii\web\View $view   caller view
	 */
	public static function registerPjaxJs($on, $target, $js, $view){
		if($on !== 'start' && $on !== 'end'){
			throw new InvalidConfigException("Parameter 'on' harus bernilai 'start' atau 'end'!!");
		}

		$view->registerJs("
			$('".$target."').on('pjax:".$on."', function() { 
				".$js."
		       
		    });"
			,View::POS_END);
	}
}
?>
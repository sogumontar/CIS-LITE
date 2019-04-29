<?php
namespace backend\themes\v2\helpers;

use yii\web\View;
use common\abstracts\UiHelperAbstract;
use common\helpers\LinkHelper;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
/**
 * @author: Marojahan Sigiro <marojahan@gmail.com>
 */
class UiHelper extends UiHelperAbstract {

	/**
	 * inherit docs
	 */
	public function renderConfirmDelete($urlContinue, $urlCancel, $message=null){
		?>
		<div class="box box-solid">
		 	<div class="box-header">
		 		<i class="fa fa-warning"></i>
		 		<h3 class="box-title">Warning</h3>
		 	</div>
		 	<div class="box-body">
		 		<div class="alert alert-danger">
		 			<i class="fa fa-ban"></i>
		 			 <?php if ($message == null): ?>
		 			 	<p>Delete bersifat cascade delete, semua komponen child akan ikut terhapus</p>
		 				<p>Apakah anda ingin melanjutkan?</p>
		 			 <?php else: ?>
		 			 	<?=$message ?>
		 			 <?php endif ?>
		 		</div>
			 	<div class="text-center">
			 		<a href="<?=$urlCancel ?>" class="btn btn-sm btn-warning">Cancel</a>
			 		<a href="<?=$urlContinue ?>" class="btn btn-sm btn-danger">Confirm Delete</a>
			 	</div>
		 	</div>
		</div>
		<?php
	}

	/**
	 * inherit docs
	 */
	
	public function renderContentHeader($string, $options=[]){
		?>
		<div class="content-header">
			<h1><?= $string ?></h1>
		</div>
		<?php
	}
	
	/**
	 * render string as header with predefined style/class
	 * H2
	 * @param  string $string header text
	 * @return mixed
	 */
	public function renderContentSubHeader($string, $options=[]){
		$idAttribute = '';
		$iconTag = '';
		if(isset($options['icon'])){
			$iconTag = '<i class="'.$options['icon'].'"></i>';
		}

		if(isset($options['id'])){
			$idAttribute = 'id="'.$options['id'].'"';
		}
		?>
		<div class="content-sub-header">
			<h2 <?=$idAttribute?>><?=$iconTag?><?= $string ?></h2>
		</div>
		<?php
	}

	/**
	 * draw line as page separator
	 * @return mixed
	 */
	public function renderLine(){
		?>
		<div class="page-line"></div>
		<?php
	}

	/**
	 * render ? button that will show help text or tooltip message
	 * option
	 * ~~~
	 * [
	 * 		'title' => 'tooltip title',
	 * 		'position' => 'left|top|right|bottom'
	 * 		'return' => false //the rendered element is returned if set true
	 * ]
	 * @param  string $mesage  message to be shown
	 * @param  array $options Tooltip options
	 * @return mixed
	 */
	public function renderTooltip($message, $options=[]){
		$titleOption='';
		$return = false;

		if(isset($options['title'])){
			$titleOption = 'title="'.$options['title'].'"';
		}

		$positionOption = 'data-placement="right"';
		if(isset($options['position'])){
			$positionOption = 'data-placement="'.$options['position'].'"';
		}
		if(isset($options['return'])){
			$return = $options['return'];
		}
		if($return){
			return "<a tabindex='0' class='' role='button' data-toggle='popover' data-trigger='focus' $titleOption $positionOption data-content=\"$message\"><i class='fa fa-question-circle'></i></a>";
		} 
		?>
		<a tabindex="0" class="" role="button" data-toggle="popover" data-trigger="focus" <?=$titleOption?> <?=$positionOption?> data-content="<?=$message?>">
			<i class="fa fa-question-circle"></i>
		</a>
		<?php
	}

	/**
	 * render conten block container with array option with the following format
	 * ~~~
	 * [
	 *  'id' => "element id",
	 * 	'header' => "block header, no header if emptied",
	 * 	'icon' => "icon full class, if any", //fontawesome or glyphicon e.g: fa fa-dashboard
	 * 	'type' => "default|success|alert|danger",
	 * 	'border' => "true|false",
	 * 	'closeable' => "true|false",
	 * 	'collapseable' => "true|false",
	 * 	'width' => "1|2|3|4|5|6|7|8|9|10|11|12", //comply bootstrap grid rule
	 * 	'background' => "#FFF",
	 * 	'isCondensed' => true|false; //reduce bottom margin to 5px
	 * ]
	 * ~~~
	 * @param  array $options box options.. 
	 * @return mixed
	 */
	public function beginContentBlock($options=[]){
		$blockWidth = (isset($options['width']))? "col-sm-".$options['width'] : "col-sm-12";

		$blockType = "box-solid";
		if(isset($options['type'])){
			switch ($options['type']) {
				case 'success':
					$blockType = "box-success";
					break;
				case 'alert':
					$blockType = "box-alert";
					break;
				case 'danger':
					$blockType = "box-danger";
					break;
			}
		}

		$blockCondensed = '';
		if(isset($options['isCondensed']) && $options['isCondensed'] == true){
			$blockCondensed = 'box-condensed';
		}

		?>
		<div id="<?=$options['id'] ?>" class="<?=$blockWidth ?>">
			<div class="box <?=$blockType ?> <?=$blockCondensed ?>">
				<?php if (isset($options['header'])): ?>
					<div class="box-header with-border">
						<?php if (isset($options['icon'])): ?>
							<i class="<?=$options['icon']?>"></i>
						<?php endif ?>
	                    <h3 class="box-title"><?=$options['header']?></h3>
	                </div>
                <?php endif ?>
				<div id="<?=$options['id']?>-body" class="box-body">
				

			<!-- ended in endContentBlock 
				</div>
			</div>
		</div> -->
		<?php
	}

	/**
	 * render conten block container with array option with the following format
	 * ~~~
	 * [
	 * 	'id' => "element id",
	 * 	'header' => "block header, no header if emptied",
	 * 	'icon' => "icon full class, if any", //fontawesome or glyphicon
	 * 	'type' => "default|success|alert|danger",
	 * 	'border' => "true|false",
	 * 	'closeable' => "true|false",
	 * 	'collapseable' => "true|false",
	 * 	'width' => "fluid|1|2|3|4|5|6|7|8|9|10|11|12", //comply bootstrap grid rule
	 * 	'background' => "#FFF",
	 * 	'url' => "url",
	 * 	'autoload' => "true|false", //default = false
	 * ]
	 * ~~~
	 * @param  array $options box options.. 
	 * @return mixed
	 */
	public function beginAjaxContentBlock($options=[]){
		$this->beginContentBlock($options);
		//TODO: process passive pjax here

	}

	/**
	 * shortcut to create row with single block (full width)
	 * Shortcut for:
	 * ~~~
	 * Yii::$app->uiHelper->beginContentRow();
	 * 		Yii::$app->uiHelper->beginContentBlock(['id' => 'someId', 'width'=>12])
	 *   		//Content here
	 *    	Yii::$app->uiHelper->endContentBlock();
	 * Yii::$app->uiHelper->endContentRow();
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	public function beginSingleRowBlock($options=[]){
		$this->beginContentRow();
		$options['width'] = 12;
		$this->beginContentBlock($options);
	}

	/**
	 * call block end and row end function
	 * @return [type] [description]
	 */
	public function endSingleRowBlock(){
		$this->endContentBlock();
		$this->endContentRow();
	}	

	/**
	 * render tab container, fungsi ini akan me-render tab header, content masing-masing tab
	 * harus di buat dengan fungsi beginTabContent([..]);
	 * format options
	 * ~~~
	 * [
	 * 	'header' => "Optional header tabs",
	 * 	'icon' => "Optionan header icon",
	 * 	'tabs' => [
	 * 		[
	 * 			'id' => "tab_id_1", //akan menjadi id target tab
	 * 			'label' => "tab label",
	 * 			'icon' => 'optional icon class',
	 * 			'isActive' => true|false,
	 * 		],
	 * 		[
	 * 			'id' => "tab_id_2", //akan menjadi id target tab
	 * 			'label' => "tab label",
	 * 			'icon' => 'optional icon class',
	 * 			'isActive' => true|false,
	 * 		],
	 * 		//...
	 *
	 * 	]
	 * ]
	 * ~~~
	 * @param  array $options tab options
	 * @return mixed
	 */
	public function beginTab($options=[]){
	?>
		<div class="nav-tabs-custom">
	        <ul class="nav nav-tabs">
				<?php $class = ''; ?>
				<?php foreach ($options['tabs'] as $tab): ?>
					<?php if (isset($tab['isActive']) && $tab['isActive']): ?>
						<?php $class = 'active'; ?>
					<?php else: ?>
						<?php $class = ''; ?>
					<?php endif ?>
					<li class="<?=$class ?>"><?=LinkHelper::renderLink(['label' => $tab['label'], 'url'=>'#'.$tab['id'], 'options' => 'data-toggle="tab"']) ?></a></li>		
				<?php endforeach ?>

	            <?php 
	            	$iconTag = "";
	            	if(isset($options['icon'])){
	            		$iconTag = '<i class="'.$options['icon'].'"></i>';
	            	}
	            	$headerTag = "";
	            	if(isset($options['header'])){
	            		$headerTag = '<li class="pull-right header">'.$iconTag. $options['header'] .'</li>';
	            	}

	            ?>
	            <?=$headerTag ?>
	        </ul>
	        <div class="tab-content">
	<?php
	}

	/**
	 * render tab content container
	 * menjadi container untuk tab  yang telah didefinisikan menggunakan fungsi ```beginTab([...]);```
	 * tab content,
	 * ```
	 * beginTabContent([...]);
	 * //content
	 * endTabContent();
	 * ```
	 *
	 * harus dibuat untuk setiap element tabs di fungsi ```beginTab([...., 'tabs' => [[...],[...]]]);```
	 *
	 *
	 * format options
	 * ~~~
	 * [
	 * 	'id' => 'id_tab_1', //harus sama dengan id_tab di beginTab();
	 * 	'isActive' => true|false
	 * ]
	 * ~~~
	 * @param  array $options Tab content options array
	 * @return mixed
	 */
	public function beginTabContent($options=[]){
	?>
		<?php $class = ''; ?>
		<?php if (isset($options['isActive']) && $options['isActive']): ?>
			<?php $class = 'active' ?>
		<?php endif ?>
		<div class="tab-pane <?=$class?>" id="<?=$options['id']?>">
	<?php
	}

	/**
	 * tab tag close
	 * @return mixed
	 */
	public function endTab(){
	?>
			</div><!-- /.tab-content -->
		</div>
	<?php
	}

	/**
	 * content tab tag close
	 * @return mixed
	 */
	public function endTabContent(){
	?>
		</div>
	<?php
	}

	/**
	 * Render image holder based on [holderjs script](http://holderjs.com)
	 * NOTE: holder assets have to be registered manually in the view.. 
	 * ~~~
	 * use common\assets\HolderJsAsset;
	 * //...
	 * //...
	 * HolderJsAsset::register($this);
	 * ~~~
	 * Options:
	 * ~~~
	 * [
	 * 	'text' => 'optional text',
	 * 	'height' => 100,
	 * 	'width' => 100,
	 * 	'background' => '#eee' //-> default
	 * 	'foreground' => '#000' //-> default
	 * 	'font-size' => 'font optional size',
	 * 	
	 * ]
	 * ~~~
	 * @param  array $options Image options
	 * @return mixed
	 */
	public function renderImageHolder($options=[]){
		$src = 'holder.js';
		$text = "text:!";

		if(!isset($options['width']) || !isset($options['height'])){
			throw new InvalidConfigException("Image holder harus memiliki property 'width' dan 'height'");
		}
		
		$src .= '/'.$options['width'].'x'.$options['height'];

		if(isset($options['text'])){
			$text = "text:".$options['text'];
		}

		$src .= '/'.$text;

		?>
		<img data-src="<?=$src ?>">
		<?php
	}

	/**
	 * fungsi untuk me render button set default maupun custom untuk record detail view maupun menu lainnya
	 * options:
	 * ~~~
	 * [
	 * 	'template' => ['edit', 'del', 'xxx'],
	 * 	'buttons' => [ 
	 * 		'edit' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 		'del' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 	 	'xxx' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 	 ],
	 * 	 'displayAsToolbar' => true|false //default false
	 * ]
	 * ~~~
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	public function renderButtonSet($options=[]){
		$content = '';
		if(!isset($options['template'])){
			throw new InvalidConfigException("Element 'template' harus di set untuk menentukan buttons yang akan di render");
		}

		foreach ($options['template'] as $buttonName) {
			if(!isset($options['buttons'][$buttonName])){
				throw new InvalidConfigException("Konfigurasi button harus di set untuk setiap button yang ada di template");
			}
			$icon = "<i class='".$options['buttons'][$buttonName]['icon']."'></i>";
			
			$delConfirmOptions = ($buttonName == 'del')? ['data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                    		  							  'data-method' => 'post']:[];
			$content .= "<li>".Html::a($icon.$options['buttons'][$buttonName]['label'], $options['buttons'][$buttonName]['url'],$delConfirmOptions)."</li>";
		}

		$wrapper = "<div class='btn-group'>".
                    "<button type='button' class='btn btn-default btn-flat btn-set btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>".
                     "<span style='font-size: 18px;' class='fa fa-gear'></span>".
                    "</button>".
                    "<ul class='dropdown-menu dropdown-menu-right' role='menu'>".
                     $content.
                    "</ul>".
                   "</div>";
      	echo $wrapper;
	}

	/**
	 * fungsi untuk me-render button set/group dalam bentuk toolbar, 
	 * bukan dropdown menu (gunakan renderButtonSet() untuk keperlua tersebut)
	 *
	 * id : tidak harus diisi, berguna jika ingin diintegrasikan dengan javascript, $('#id')....
	 * options:
	 * ~~~
	 * [
	 *  'return' => true|false, //wether to return or direct render , default false, direct render
	 * 	'groupTemplate' => ['button-group-1', 'button-group-2'],
	 * 	'groups' => [
	 * 		'button-group-1' => [
	 * 			'template' => ['add', 'filter', 'xxx'],
	 * 	  		'buttons' => [ 
	 * 		   		'add' => ['id' => 'someid1', url' => '', 'label' => '', 'icon' => ''],
	 * 		     	'filter' => ['id' => 'someid2','url' => '', 'label' => '', 'icon' => ''],
	 * 	 	     	'xxx' => "<a href='#'> can also in raw text/html format</a>",
	 * 	        ]
	 * 		],
	 * 		'button-group-2' => [
	 * 			'template' => ['...'],
	 * 			'buttons' => [
	 * 				'...' => ['id' => 'someid4','url' => '', 'label' => '', 'icon' => '']
	 * 			]
	 * 		]
	 * 	]
	 * 	'clientScript' => [
	 * 		'view' => $viewObject,
	 * 		'script' => 'raw-js-script';
	 * 	],
	 * 	'pull-right' => true|false, //default false; true to put the position on right align
	 * ]
	 * ~~~
	 * @param  array  $options [description]
	 * @return [type]          [description]
	 */
	public function renderToolbar($options=[]){
		$content = '';
		if (!isset($options['groupTemplate'])) {
			throw new InvalidConfigException("Element 'groupTemplate' harus di set untuk menentukan group buttons yang akan di render");
		}

		foreach ($options['groupTemplate'] as $groupTemplate) {
			if (!isset($options['groups'][$groupTemplate])) {
				throw new InvalidConfigException("Konfigurasi group harus di set untuk setiap group yang ada di groupTemplate");
			}
			if (!isset($options['groups'][$groupTemplate]['template'])) {
				throw new InvalidConfigException("Element 'template' harus di set untuk menentukan buttons yang akan di render");
			}
			$content .= "<div class='btn-group btn-group-sm' role='group'>";

			foreach ($options['groups'][$groupTemplate]['template'] as $buttonName) {
				if(!isset($options['groups'][$groupTemplate]['buttons'][$buttonName])){
					throw new InvalidConfigException("Konfigurasi button harus di set untuk setiap button yang ada di template");
				}
				if(is_array($options['groups'][$groupTemplate]['buttons'][$buttonName])){
					$icon = "<i class='".$options['groups'][$groupTemplate]['buttons'][$buttonName]['icon']."'></i>";
					$labelText = (isset($options['groups'][$groupTemplate]['buttons'][$buttonName]['label']))?$options['groups'][$groupTemplate]['buttons'][$buttonName]['label']:'';
					if($labelText !== ''){
						$labelText = "<span class='toolbar-label'>".$options['groups'][$groupTemplate]['buttons'][$buttonName]['label']."</span>";
					}
					$label = $icon.$labelText;
					$id = (isset($options['groups'][$groupTemplate]['buttons'][$buttonName]['id']))? $options['groups'][$groupTemplate]['buttons'][$buttonName]['id']:'';
					$content .= "<a id='".$id."' href='".$options['groups'][$groupTemplate]['buttons'][$buttonName]['url']."' class='btn btn-default'>".$label."</a>";
				}else{
					//handle raw content here
					$content .= $options['groups'][$groupTemplate]['buttons'][$buttonName];
				}
				
			}

			$content .= "</div>";
		}
		if(isset($options['clientScript'])){
			if (!isset($options['clientScript']['view']) || !isset($options['clientScript']['script'])) {
				throw new InvalidConfigException("Object view dan raw javascript harus di set !!");
			}
			$options['clientScript']['view']->registerJs($options['clientScript']['script'], View::POS_END);
		}

		$position = '';
		if(isset($options['pull-right']) && $options['pull-right'] == true){
			$position = 'pull-right';
		}
		
		$wrapper = "<div class='btn-toolbar sysx-toolbar ".$position."' role='toolbar'>". $content ."</div>";
		if(isset($options['return']) && $options['return'] == true){
			return $wrapper;
		}
		echo $wrapper;
	}
}

?>




































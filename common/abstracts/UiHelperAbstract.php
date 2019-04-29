<?php
namespace common\abstracts;
use yii\base\Component;
/**
 * abstract call yang harus di-extend oleh semua ui helper yang harus di include di semua theme.
 *
 * @author Marojahan Sigiro <marojahan@del.ac.id>
 * @since 0.1
 */
abstract class UiHelperAbstract extends Component{
	
	/**
	 * fungsi untuk me-render panel untuk menampilkan konfirmasi delete record di database;
	 * @param  [type] $urlContinue url yang dituju jika proses di-continue
	 * @param  [type] $urlCancel   url yang dituju jika proses di-cancel
	 * @param  [type] $message     opsional pesan yang akan ditampilkan di panel konfirmasi
	 * @return [type]              [description]
	 */
	abstract protected function renderConfirmDelete($urlContinue, $urlCancel, $message=null);
	
	/**
	 * render string as header with predefined style/class
	 * H1
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	abstract protected function renderContentHeader($string, $options=[]);
	
	/**
	 * render string as header with predefined style/class
	 * H2
	 * @param  string $string header text
	 * @return mixed
	 */
	abstract protected function renderContentSubHeader($string, $options=[]);

	/**
	 * draw line as page separator
	 * @return mixed
	 */
	abstract protected function renderLine();

	/**
	 * render ? button that will show help text or tooltip message
	 * option
	 * ~~~
	 * [
	 * 		'title' => 'tooltip title',
	 * 		'position' => 'left|top|right|bottom'
	 * ]
	 * @param  string $mesage  message to be shown
	 * @param  array $options Tooltip options
	 * @return mixed
	 */
	abstract protected function renderTooltip($mesage, $options=[]);
	
	/**
	 * render conten block container with array option with the following format
	 * ~~~
	 * [
	 *  'id' => "element id",
	 * 	'header' => "block header, no header if emptied",
	 * 	'icon' => "icon full class, if any", //fontawesome or glyphicon
	 * 	'type' => "default|success|alert|danger",
	 * 	'border' => "true|false",
	 * 	'closeable' => "true|false",
	 * 	'collapseable' => "true|false",
	 * 	'width' => "fluid|1|2|3|4|5|6|7|8|9|10|11|12", //comply bootstrap grid rule
	 * 	'background' => "#FFF",
	 * ]
	 * ~~~
	 * @param  array $options box options.. 
	 * @return mixed
	 */
	abstract protected function beginContentBlock($options=[]);

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
	abstract protected function beginAjaxContentBlock($options=[]);
	
	/**
	 * shortcut to create row with single block (full width)
	 * Shortcut for:
	 * ~~~
	 * Yii::$app->uiHelper->beginContentRow();
	 * Yii::$app->uiHelper->beginContentBlock(['id' => 'someId', 'width'=>12])
	 * //Content here
	 * Yii::$app->uiHelper->endContentBlock();
	 * Yii::$app->uiHelper->endContentRow();
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	abstract protected function beginSingleRowBlock($options=[]);

	/**
	 * call block end and row end function
	 * @return [type] [description]
	 */
	abstract protected function endSingleRowBlock();	
	
	/**
	 * return block end element tag
	 */
	public function endContentBlock(){
		echo "</div></div></div>";
	}

	/**
	 * create content row to seperate blocks, by row..
	 */
	public function beginContentRow(){
		echo "<div class='row'>";
	}

	/**
	 * return row end element tag
	 */
	public function endContentRow(){
		echo  "</div>";
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
	abstract protected function beginTab($options=[]);

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
	abstract protected function beginTabContent($options=[]);

	/**
	 * tab tag close
	 * @return mixed
	 */
	abstract protected function endTab();

	/**
	 * content tab tag close
	 * @return mixed
	 */
	abstract protected function endTabContent();

	/**
	 * Render image holder based on [holderjs script](http://holderjs.com)
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
	abstract protected function renderImageHolder($options=[]);

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
	 * 	 ]
	 * ]
	 * ~~~
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	abstract protected function renderButtonSet($options=[]);

	/**
	 * fungsi untuk me-render button set/group dalam bentuk toolbar, 
	 * bukan dropdown menu (gunakan renderButtonSet() untuk keperlua tersebut)
	 *
	 * options:
	 * ~~~
	 * [
	 * 	'groupTemplate' => ['button-group-1', 'button-group-2'],
	 * 	'groups' => [
	 * 		'button-group-1' => [
	 * 			'template' => ['edit', 'del', 'xxx'],
	 * 	  		'buttons' => [ 
	 * 		   		'edit' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 		     	'del' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 	 	     	'xxx' => ['url' => '', 'label' => '', 'icon' => ''],
	 * 	        ]
	 * 		],
	 * 		'button-group-2' => [
	 * 			'template' => ['...'],
	 * 			'buttons' => [
	 * 				'...' => ['url' => '', 'label' => '', 'icon' => '']
	 * 			]
	 * 		]
	 * 	]
	 * ]
	 * ~~~
	 * @param  array  $options [description]
	 * @return [type]          [description]
	 */
	abstract protected function renderToolbar($options=[]);
}

?>
<?php
namespace common\components;

use yii\base\Component;

class Debugger extends Component {

	/**
	 * print array/entity content
	 * @param  mixed $arr object to be displayed (e.g array, active record)
	 * @param  boolean $die stop the execution after data rendered
	 * @return mixed
	 */
	public static function print_array($obj, $die=false){
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		if ($die) {
			die();
		}
		
	}
}
?>
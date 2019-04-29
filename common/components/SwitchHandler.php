<?php

namespace common\components;

use Yii;
use yii\web\Response;

/**
 * class untuk menyediakan fungsi handler dari SwitchColum
 *
 *untuk menghandle request di dalam sebuah action dapat menggunakan format berikut 
 *
 *~~~
 *if(SwitchHandler::isSwitchRequest()){
 *	   if(//ok to proceed, e.g check privileges){
 * 	
 *     		if(SwitchHandler::isTurningOn()){
 *     			// what to do to turn the switch on??
 *         	}else{
 *     		   	//what to do to swith it off?
 *          }
 *          
 *          return SwitchHandler::respondWithSuccess();
 *      }     
 *     
 *     return SwitchHandler::respondWithFailed();
 *}
 *~~~
 *
 *
 * @author Marojahan Sigiro
 */

class SwitchHandler {

	/**
	 * memerika apakah request yang datang adalah switch request
	 * @return boolean
	 */
	public static function isSwitchRequest(){
		return (Yii::$app->request->isAjax && isset($_POST['switchcolumnreq']));
	}

	/**
	 * Memeriksa jenis request switch, apakah switch on (1) atau switch off (0)
	 * @return boolean
	 */
	public static  function isTurningOn(){
		return ((int) Yii::$app->request->post('switchcolumnreq') == 1);
	}

	/**
	 * merespon request ajax dengan JSON SUCCESS, untuk menandakan kalau reques switch yang diberikan telah success di handle
	 * @return mixed
	 */
	public static function respondWithSuccess(){
		Yii::$app->response->format = Response::FORMAT_JSON;
		return  ['SUCCESS' => 1];
	}

	/**
	 * see respondWithSuccess()
	 */
	public static function respondWithFailed($message=null){
		Yii::$app->response->format = Response::FORMAT_JSON;
		return ['FAILED' => 1, 'message' => $message];
	}
	/**
	 * return single data sent from client
	 * @return [type] [description]
	 */
	public static function getSwitchColumnData(){
		if(isset($_POST['id'])){
			return $_POST['id'];
		}

		return null;
	}

	public static function getRawSwitchButtonData(){
		return $_POST['switch_data'];
	}
	public static function getDecodedSwitchButtonData(){
		$data = $_POST['switch_data'];

		$result = [];
		$dataArr1 = explode('|', $data);
		foreach ($dataArr1 as $arrElement) {
			$element = explode('=', $arrElement);
		 	$result[$element[0]] = $element[1];
		 }

		return $result;
	}
}
<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/** Puro Messenger Server API */

/**
 * class ini berperan sebagai API, Library, yang akan digunakan oleh web application untuk
 * berinteraksi dengan Messenger server puro.
 *
 * //help: posting json data as post. 
 * //http://www.lornajane.net/posts/2011/posting-json-data-with-php-curl
 */

/**
 * Driver untuk proses messaging menggunakan service puro
 * 
 * @author Marojahan Sigiro <marojahan@{gmail.com, del.ac.id}>
 */
class PuroMessengerAPIDriverDummy {
	
	public static $sendUrlPrefix = "/messaging/mq/send";
	public static $collectUrlPrefix = "/messaging/mq/collect";
	public static $getUrlPrefix = "/messaging/mq";
	public static $markAsReadUrlPrefix = "/messaging/mq/markasread";
	public static $markAllAsReadUrlPrefix = "/messaging/mq/markallasread";
	public static $setAllAsSeenUrlPrefix = "/messaging/mq/setallseen";
	public static $deleteUrlPrefix = "/messaging/mq";

	public static $mailUrlPrefix = "/messaging/mail/send";
	public static $rocketchatUrlPrefix = '/messaging/rocketchat/send';

	public static $defautSender = "system";
	public static $defaultPermanent = 0;

	/**
	 * API interface to send message
	 *
	 * message format
	 * ~~~
	 * [
	 * 	'from' 	=> 'sender',
	 * 	'to'	=> 'recipient', // atau ['recipient1', 'recipient2', '...'] ... required
	 * 	'body'	=> 'string... text',
	 * 	'tag'	=> 'single_tag',
	 * 	'permanent' => boolean //permanent status, non permanent message will be cleaned up by server periodically
	 * ]
	 * ~~~
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  array  $message        message to be sent
	 * @return json                   status
	 */
	public static function send($serviceBaseUrl, $apiKey, $message){
		                                                                                  
		//validate message
		if(!isset($message['to'])){
			throw new InvalidConfigException("Message yang dikirim harus memiliki field 'to' berupa string atau array of string!");
		}   
		
		if(!isset($message['from'])){
			$message['from'] = self::$defautSender;
		}

		if(!isset($message['permanent'])){
			$message['permanent'] = self::$defaultPermanent;
		}

		$encodedMessage = json_encode($message); 

		return true;
	}

	/**
	 * API Interface to collect messages based on defined criteria
	 *
	 * criteria format
	 * ~~~
	 * [
	 * 	'from' 	=> 'sender',
	 * 	'to' 	=> 'recipient', //required
	 * 	'status' => boolean //message status 'seen' status
	 * 	'offset' => integer //start from?
	 * 	'limit'	 => integer //how many messages?
	 * 	'withData' => boolean //retrieve data? default false
	 * 	'withInfo'	=> boolean //retrieve global info? default true
	 * 	'tag'	=> ['tag1', 'tag2', 'tag...'] //OR operator
	 * ],
	 * 
	 * ~~~
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  array  $criteria        criteria to filter messages
	 * @return json
	 */
	public static function collect($serviceBaseUrl, $apiKey, $criteria){
		//validate message
		
		if(!isset($criteria['to'])){
			throw new InvalidConfigException("Criteria harus memiliki field 'to' berupa string !");
		}

		$encodedCriteria = json_encode($criteria);

		return true;
	}

	/**
	 * [getMessage description]
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  String $messageId      Message queue id
	 * @return json   message
	 */
	public static function getMessage($serviceBaseUrl, $apiKey, $messageId){
		return true;
	}

	/**
	 * mark single message as read
	 * 
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  String $messageId      Message queue id
	 * @return json   status
	 */
	public static function markAsRead($serviceBaseUrl, $apiKey, $messageId){
		return true;
	}

	/**
	 * mark all messages as read
	 * 
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  String $recipient      Message queue recipient
	 * @return json   status                
	 */
	public static function markAllAsRead($serviceBaseUrl, $apiKey, $recipient){
		return true;
	}

	/**
	 * set all messages as seen
	 * 
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  String $recipient      Message queue recipient
	 * @return json   status                
	 */
	public static function setAllAsSeen($serviceBaseUrl, $apiKey, $recipient){
		return true;
	}

	/**
	 * delete multiple/single message
	 * criteria format
	 * ~~~
	 * [
	 * 'id' => [
	 * 			'id1',
	 * 	 		'id2',
	 * 	   		'idn'
	 * 	     ]
	 * ]
	 * ~~~
	 * @param  string $serviceBaseUrl puro messaging service base url (domain)
	 * @param  String $apiKey 		  Puro service API KEY
	 * @param  Array  $criteria       Message id[s]
	 * @return json   status 
	 */
	public static function delete($serviceBaseUrl, $apiKey, $criteria){
		//validate message
		if(!isset($criteria['id'])){
			throw new InvalidConfigException("Criteria harus memiliki field 'id' array of string !");
		}

		if(!is_array($criteria['id'])){
			throw new InvalidConfigException("Criteria id harus berupa array of string !");
		}

		$encodedCriteria = json_encode($criteria);

		return true;
	}

	/**
	 * send email through puro API Service
	 * mail format
	 * ~~~
	 * [
	 * 	'fromName' 		=> String,
	 * 	'fromAddress'	=> String,  //email address
	 * 	'subject'		=> String,
	 * 	'to'			=> ['a@domain.com', 'nama b' => 'b.domain.com'],
	 *  'cc'			=> ['a@domain.com', 'nama b' => 'b.domain.com'],
	 *  'bcc'			=> ['a@domain.com', 'nama b' => 'b.domain.com'],
	 *  'replyTo'		=> Strinng //email address
	 *  'noReply'		=> boolean,
	 * 	'isHtml'		=> boolean,
	 * 	'body'			=> String,
	 * 	'attachmentFiles'=> ['http://bla.com/x.pdf', 'url to some static file']
	 * ]	
	 *
	 * ~~~
	 * @param  [type] $serviceBaseUrl [description]
	 * @param  [type] $apiKey         [description]
	 * @param  [type] $email          [description]
	 * @return [type]                 [description]
	 */
	public static function mail($serviceBaseUrl, $apiKey, $mail){
		//validate message
		if (!isset($mail['fromAddress'])) {
			throw new InvalidConfigException("Message yang dikirim harus memiliki field 'fromAddress' berupa string email address!");
		}
		
		if(!isset($mail['to']) || !is_array($mail['to']) || count($mail['to'] < 1)){
			throw new InvalidConfigException("Message yang dikirim harus memiliki field 'to' berupa array of string!");
		}
		
		$mail['to'] = self::formatMailRecipients($mail['to']);

		if(isset($mail['cc'])){
			if (!is_array($mail['cc']) || count($mail['cc'] < 1)){
				throw new InvalidConfigException("Format field 'cc' salah, harus berupa array of adrress!");
			}

			$mail['cc'] = self::formatMailRecipients($mail['cc']);
			
		}

		if(isset($mail['bcc'])){
			if (!is_array($mail['bcc']) || count($mail['bcc'] < 1)){
				throw new InvalidConfigException("Format field 'bcc' salah, harus berupa array of adrress!");
			}

			$mail['bcc'] = self::formatMailRecipients($mail['bcc']);
			
		}
		
		// \Yii::$app->debugger->print_array($mail, true);
		$encodedMail = json_encode($mail);

		return true;
	}

	private static function formatMailRecipients($rawRecipientArray){
		$recFormatted = [];
		foreach ($rawRecipientArray as $name => $address) {
			if (is_string($name)) {
				$recFormatted[] = ['name' => $name, 'address' => $address];
			}else{
				$recFormatted[] = ['address' => $address];
			}
		}
		return $recFormatted;
	}
	/**
	 * Driver untuk mengirimkan payload rockcet chat ke puro, yang selanjutnya di dispatch ke server Rocket.Chat
	 * {//json payload} ... for single payload
	 * [{//json payload...}, {...}] for multi payload
	 * @param [string] $serviceBaseUrl puro service base url (cht. htt://puro.del.ac.id/v2)
	 * @param [type] $apiKey puro api key
	 * @param [type] $rawPayload formatted payload
	 * @return void
	 */
	public static function rocketChat($serviceBaseUrl, $apiKey, $rawJSONPayload){
		$payloadDecoded = json_decode($rawJSONPayload);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new InvalidConfigException("Payload memiliki format json yang salah !!");
		}
		$formattedPayload = ['payloads' => ''];
		if (is_array($payloadDecoded)) {
			$formattedPayload = ['payloads' => $payloadDecoded];
		}else{
			$formattedPayload = ['payloads' => [$payloadDecoded]];
		}
		$encodedFormattedPayload = json_encode($formattedPayload);

		return true;
	}
}
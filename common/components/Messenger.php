<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\components\PuroMessengerApiClient;
use backend\modules\admin\models\User;
use backend\modules\admin\models\Workgroup;

/**
 * Component class provides API to handle messanging features (flash message, mail, in-app message)
 *
 * Komponen ini dapat digunakan dengan mengakses komponen Yii atau menggunakan secara langsung
 *
 * App component
 * ~~~
 * \Yii::$app->messenger->addSuccessFlash("Data Mahasiswa berhasil ditambahkan");
 * ~~~
 *
 * Direct Access
 *
 * ~~~
 * use common\components\Messenger;
 * //...
 * //...
 * $msger = new Messenger();
 * $msger->addErrorFlash("Data belum lengkap !!");
 * ~~~
 * 
 * @author Marojahan Sigiro <marojahan@del.ac.id>
 * @since 0.1
 */
class Messenger extends Component {
	
	const TYPE_ERROR = "__err__";
	const TYPE_SUCCESS = "__suc__";
	const TYPE_WARNING = "__wrn__";
	const TYPE_INFO = "__nfo__";

	/**
	 * Messaging tag const value
	 */
	
	const NOTIF_TAG	= "_notif_";
	const DIRECT_MESSAGE_TAG = "_dm_";

	public $messagingDriverClass = "common\components\PuroMessengerAPIDriverDummy";
	public $messagingServerBaseUrl = "http://api.puro.del.ac.id/v1";
	public $apiKey = 'abc';
	public $defaultEmailSender = 'cis-noreply@del.ac.id';
	public $defaultMessageSender = 'system';

	/**
	 * fungsi untung menambahkan flash message Info
	 * @param string $msg pesan flash Info
	 */
	public function addInfoFlash($msg){
		$this->addFlash(self::TYPE_INFO, $msg);
	}
	
	/**
	 * fungsi untung mengambil flash message Info
	 * @return Array Flash message
	 */
	public function getInfoFlash(){
		return Yii::$app->session->getFlash(self::TYPE_INFO);
	}


	/**
	 * fungsi untung menambahkan flash message success
	 * @param string $msg pesan flash success
	 */
	public function addSuccessFlash($msg){
		$this->addFlash(self::TYPE_SUCCESS, $msg);
	}
	
	/**
	 * fungsi untung mengambil flash message success
	 * @return Array Flash message
	 */
	public function getSuccessFlash(){
		return Yii::$app->session->getFlash(self::TYPE_SUCCESS);
	}

	/**
	 * fungsi untung menambahkan flash message warning
	 * @param string $msg pesan flash warning
	 */
	public function addWarningFlash($msg){
		$this->addFlash(self::TYPE_WARNING, $msg);
	}

	/**
	 * fungsi untung mengambil flash message warning
	 * @return Array Flash message
	 */
	public function getWarningFlash(){
		return Yii::$app->session->getFlash(self::TYPE_WARNING);
	}

	/**
	 * fungsi untung menambahkan flash message error
	 * @param string $msg pesan flash error
	 */
	public function addErrorFlash($msg){
		$this->addFlash(self::TYPE_ERROR, $msg);
	}

	/**
	 * fungsi untung mengambil flash message error
	 * @return Array Flash message
	 */
	public function getErrorFlash(){
		return Yii::$app->session->getFlash(self::TYPE_ERROR);
	}

	/**
	 * fungsi untung menambahkan flash message generic
	 * @param string $msg pesan flash generic
	 */
	public function addFlash($type, $msg){
		Yii::$app->session->addFlash($type, $msg);
	}

	/**
	 * fungsi untung memeriksa apakah ada flash message dengan type tertentu
	 * @return boolean
	 */
	public function hasFlash($type){
		return Yii::$app->session->hasFlash($type);
	}

	/**
	 * fungsi untung mengambil flash message generic
	 * @param string $msg pesan flash generic
	 */
	public function getFlash($type){
		return Yii::$app->session->getFlash($type);
	}

	/**
	 * Fungsi untuk mengirimkan email ke alamat emal tertentu
	 * @param  String $from email address
	 * @param  String $to   email address
	 * @param  String $msg  Email content
	 * @return json
	 */
	public function sendEmail($from, $to, $subject, $body, $options=[]) {
		$apiClient = $this->messagingDriverClass;
		return $apiClient::mail($this->messagingServerBaseUrl, $this->apiKey, [
				'fromAddress' => $from,
				'to' => $to,
				'subject' => $subject,
				'msg' => $body
			]);
	}

	/**
	 * Fungsi untuk mengirim email ke internal user
	 * options
	 * ~~~
	 * [
	 * 	'from' => 'some_email@some.domain'
	 * ]
	 * ~~~
	 * 
	 * @param  Integer $userId  user_id dari user yang akan di kirim email, email tujuan akan
	 *                          diretriever berdasarkan ID ini
	 * @param  String $subject Subject email
	 * @param  String $body    Email content
	 * @param  array  $options option untuk custom from address
	 * @return Json or false if no user found
	 */
	public function sendEmailToUser($userId, $subject, $body, $options=[]){
		if(!is_integer($userId)){
			throw new InvalidConfigException("'userId' harus berupa integer !");
		}

		$user = User::findOne($userId);
		$apiClient = $this->messagingDriverClass;
		if($user){
			$from = $this->defaultEmailSender;

			if(isset($options['from'])){
				$from = $options['from'];
			}

			return $apiClient::mail($this->messagingServerBaseUrl, $this->apiKey, [
				'fromAddress' => $from,
				'to' => $user->email,
				'subject' => $subject,
				'msg' => $body
			]); 
		}else{
			false;
		}

	}

	/**
	 * Mengirimkan notifikasi ke internal user menggunakan service puro messaging
	 * @param  Integer/Array $userId  user id, gunakan array untuk mengirimkan ke multi user
	 * @param  String  $message Notification content
	 * @return json status
	 */
	public function sendNotificationToUser($userId, $message){
		$apiClient = $this->messagingDriverClass;
	
		$recipients = '';
		if(!is_integer($userId) && !is_array($userId)){
			throw new InvalidConfigException("'userId' harus berupa integer atau array of integer !");
		}

		if(is_array($userId)){
			$users = User::findAll($userId);

			foreach ($users as $user) {
			 	$recipients [] = $user->username;
			} 
			if(count($recipients) < 1){
				$recipients = '';
			}

		}else{
			$user = User::find()->where('user_id = :user_id', [':user_id' => $userId])->one();	
			$recipients = $user->username;
		}

		if ($recipients) {
			return $apiClient::send($this->messagingServerBaseUrl, $this->apiKey,[
					'from' 	=> $this->defaultMessageSender,
					'to'	=> $recipients,
					'body'	=> $message,
					'tag'	=> self::NOTIF_TAG,
					'permanent'	=> 0
				]);
		}
	}
	/**
	 * Mengirimkan notification ke member sebuah workgroup
	 * @param  Integer $workgroupId Id dari workgroup
	 * @param  String  $message     Isi dari notification
	 * @return Json status
	 */
	public function sendNotificationToWorkgroup($workgroupId, $message){
		//TODO: optimize this with query builder
		$workgroup = Workgroup::find()->with('users')->where('workgroup_id = :wg_id', [':wg_id' => $workgroupId])->one();

		$recipients = [];
		foreach ($workgroup->users as $user) {
			$recipients [] = $user->username;
		}

		$apiClient = $this->messagingDriverClass;
		if(count($recipients) > 0){
			return $apiClient::send($this->messagingServerBaseUrl, $this->apiKey,[
						'from' 	=> $this->defaultMessageSender,
						'to'	=> $recipients,
						'body'	=> $message,
						'tag'	=> self::NOTIF_TAG,
						'permanent'	=> 0
					]);
		}

	}

	/**
	 * Mengambil notification info dari server puro, jumlah new dan unread notification
	 * @return Json
	 */
	public function getMyNotificationsInfo(){
		$me = \Yii::$app->user->getIdentity()->username;
		$apiClient = $this->messagingDriverClass;

		return $apiClient::collect($this->messagingServerBaseUrl, $this->apiKey,[
					'to'	=> $me,
					'tag'	=> [self::NOTIF_TAG],
					'withInfo' => true,
					'withData' => false
				]);
	}

	/**
	 * Get notifications data with options
	 * options
	 * ~~~
	 * [
	 * 	'limit' = Integer,
	 * 	'page' = Integer //start from 1
	 * ]
	 * ~~~
	 * no Options means all notifications will be retrieved
	 * @param  array  $options pengaturan limit dan page (untuk menentukan offset)
	 * @return json notifications data
	 */
	public function getMyNotificationsData($options = []){
		$me = \Yii::$app->user->getIdentity()->username;
		$apiClient = $this->messagingDriverClass;
		$criteria = [
					'to'	=> $me,
					'tag'	=> [self::NOTIF_TAG],
					'withInfo' => false,
					'withData' => true
				];

		if (isset($options['limit'])) {
			$criteria['limit'] = $options['limit'];
		}

		if(isset($options['page']) && isset($options['limit'])) {

			$criteria['offset'] = $options['limit'] * ($options['page'] - 1);
		}
		return $apiClient::collect($this->messagingServerBaseUrl, $this->apiKey, $criteria);
	}

	/**
	 * Set all notification menjadi seen, digunakan dalam kasus ketika user melihat daftar notification
	 */
	public function setAllMyNotificationsAsSeen(){
		$me = \Yii::$app->user->getIdentity()->username;
		$apiClient = $this->messagingDriverClass;
		return $apiClient::setAllAsSeen($this->messagingServerBaseUrl, $this->apiKey, $me);
	}

	/**
	 * set/flag sebuah notification menjadi read
	 * @param String $notificationId Id dari notification
	 */
	public function markMyNotificationAsRead($notificationId){
		$apiClient = $this->messagingDriverClass;
		return $apiClient::markAsRead($this->messagingServerBaseUrl, $this->apiKey, $notificationId);
	}

	/**
	 * delete sebuah atau beberapa notification by ID
	 * @param  array  $id array of string of notification id
	 * @return json status
	 */
	public function deleteMyNotifications($id = []){
		if(!is_array($id)){
			throw new InvalidConfigException("'id' harus berupa array !");
		}

		$criteria = [
			'id' => $id, 
		];
		$apiClient = $this->messagingDriverClass;
		return $apiClient::delete($this->messagingServerBaseUrl, $this->apiKey, $criteria);
	}

	/**
	 * Mengambil isi lengkap sebuah notification berdasarkan ID
	 * @param  Integer $notificationId ID Notofication
	 * @return Json                Notofication data
	 */
	public function getMyNotification($notificationId){
		$apiClient = $this->messagingDriverClass;
		return $apiClient::getMessage($this->messagingServerBaseUrl, $this->apiKey, $notificationId);
	}

	/******************************************** TODO *************************************************/
	//NOTE: Implementation pending for v2, have to add feature to puro api, for threaded message.
	

	public function sendMessage($fromId, $toId, $message){
		$apiClient = $this->messagingDriverClass;

		$sender = User::find()->where('user_id = :user_id', [':user_id' => $fromId])->one();
		$recipient = User::find()->where('user_id = :user_id', [':user_id' => $toId])->one();
		if ($user) {
			return $apiClient::send($this->messagingServerBaseUrl, $this->apiKey,[
					'from' 	=> $sender->username,
					'to'	=> $recipient->username,
					'body'	=> $message,
					'tag'	=> self::DIRECT_MESSAGE_TAG,
					'permanent'	=> 1
				]);
		}
	}

	public function getMyMessageInfo(){
		//TBD
	}

	/**
	 * Get notifications data with options
	 * options
	 * ~~~
	 * [
	 * 	'limit' = Integer,
	 * 	'page' = Integer,
	 * 	'thread' = Integer
	 * ]
	 * ~~~
	 * no Options means all notifications will be retrieved
	 * @param  array  $options [description]
	 * @return [type]          [description]
	 */
	public function getMyMessageData($options = []){
		//TBD
	}

}
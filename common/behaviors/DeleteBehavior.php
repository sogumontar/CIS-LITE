<?php
namespace common\behaviors;

use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\base\Behavior;

use yii\web\ForbiddenHttpException;
/**
 * SoftDelete
 * Usage:
 *
 * ~~~
 * public function behaviors() {
 *     return [
 *         'softDelete' => [
 *             'class' => 'common\behaviors\DeleteBehavior',
 *             'attribute' => 'deleted_at',
 *             'enableSoftDelete' => true, //default = true
 *             'hardDeleteTaskName' => 'HardDeleteDB',
 *         ],
 *     ];
 * }
 * ~~~
 * @author Marojahan Sigiro <marojahan@del.ac.id>
 * base code written by:
 * @author amnah <amnah.dev@gmail.com>
 */
class DeleteBehavior extends Behavior {

    /**
     * @var string SoftDelete attribute
     */
    public $deletedAtAttribute = "deleted_at";
    public $deletedByAttribute = "deleted_by";
    public $deleteFlagAttribute = 'deleted';
    /**
     * @inheritdoc
     */
    public $timestamp;
    /**
     * @var bool If true, this behavior will process '$model->delete()' as a soft-delete. Thus, the
     *           only way to truely delete a record is to call '$model->forceDelete()'
     */
    public $enableSoftDelete = true;
    
    /**
     * @var string nama task yang menjadi privilege untuk melakukan hard delete
     */
    public $hardDeleteTaskName = 'HardDeleteDB';

    /**
     * @inheritdoc
     */
    public function events() {
        return [ActiveRecord::EVENT_BEFORE_DELETE => 'doDelete'];
    }
    /**
     * Set the attribute with the current timestamp to mark as deleted
     *
     * @param Event $event
     */
    public function doDelete($event) {
        // do nothing if safeMode is disabled. this will result in a normal deletion or user has HardDeleteDB Task
        if (!$this->enableSoftDelete || Yii::$app->privilegeControl->isHasTask($this->hardDeleteTaskName)) {
            return;
        }else{
            $event->isValid = false;
            //throw exception, no privilege
            throw new ForbiddenHttpException(Yii::t('yii', 'Permission Denied!!, Special permission needed to delete data.'));
            
        }
        // remove and mark as invalid to prevent real deletion
    }
    
    public function softDelete() {
        // evaluate timestamp and set attribute
        $this->owner->{$this->deletedAtAttribute} = date("Y-m-d H:i:s", time());
        $user = Yii::$app->get('user', false);

        $username = $user && !$user->isGuest ? $user->getIdentity()->username : 'guest';
        $this->owner->{$this->deletedByAttribute} = $username;

        $this->owner->{$this->deleteFlagAttribute} = 1;
        // save record
        
        return $this->owner->save(false, [$this->deleteFlagAttribute, 
                                   $this->deletedAtAttribute, 
                                   $this->deletedByAttribute]
                            );
    }
    /**
     * Restore soft-deleted record
     */
    public function restore() {
        // evaluate timestamp and set attribute
        $this->owner->{$this->deletedAtAttribute} = null;
        $this->owner->{$this->deletedByAttribute} = null;
        $this->owner->{$this->deleteFlagAttribute} = 0;
        // save record
        
        return $this->owner->save(false, [$this->deleteFlagAttribute, 
                                   $this->deletedAtAttribute, 
                                   $this->deletedByAttribute]
                            );
    }
    /**
     * Delete record from database regardless of the $safeMode attribute
     */
    public function forceDelete() {
        // store model so that we can detach the behavior and delete as normal
        $model = $this->owner;
        $this->detach();
        $model->delete();
    }

    /**
     * fungsi untuk menambahkan kondisi query untuk mem-filter row yang di-softDelete (exclude)
     * @param  ActiveQuery $query
     */
    public static function excludeDeleted($query){
        $query->andWhere('deleted != 1');
    }

}
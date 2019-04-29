<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\Vendor;
use backend\modules\invt\models\FileVendor;
/**
 * This is the model class for table "invt_arsip_vendor".
 *
 * @property integer $arsip_vendor_id
 * @property integer $vendor_id
 * @property string $judul_arsip
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $craeted_by
 *
 * @property InvtRVendor $vendor
 * @property InvtFileVendor[] $invtFileVendors
 */
class ArsipVendor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => DeleteBehavior::className(),
                'hardDeleteTaskName' => 'HardDeleteDB', //default
                'enableSoftDelete' => true, //default, set false jika behavior ini ingin di bypass. cth, untuk proses debugging
            ]
        ];
    }

    public static function tableName()
    {
        return 'invt_arsip_vendor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['judul_arsip','required'],
            [['vendor_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['judul_arsip'], 'string', 'max' => 150],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'arsip_vendor_id' => 'Arsip Vendor ID',
            'vendor_id' => 'Vendor ID',
            'judul_arsip' => 'Judul Arsip',
            'desc' => 'Desc',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Craeted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor()
    {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFileVendor()
    {
        return $this->hasMany(FileVendor::className(), ['arsip_vendor_id' => 'arsip_vendor_id']);
    }
}

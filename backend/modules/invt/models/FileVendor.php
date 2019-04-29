<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "invt_file_vendor".
 *
 * @property integer $file_vendor_id
 * @property integer $arsip_vendor_id
 * @property string $nama_file
 * @property string $kode_file
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property InvtArsipVendor $arsipVendor
 */
class FileVendor extends \yii\db\ActiveRecord
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
        return 'invt_file_vendor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['arsip_vendor_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama_file', 'kode_file'], 'string', 'max' => 250],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_vendor_id' => 'File Vendor ID',
            'arsip_vendor_id' => 'Arsip Vendor ID',
            'nama_file' => 'Nama File',
            'kode_file' => 'Kode File',
            'desc' => 'Desc',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArsipVendor()
    {
        return $this->hasOne(ArsipVendor::className(), ['arsip_vendor_id' => 'arsip_vendor_id']);
    }
}

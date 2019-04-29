<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\Barang;
/**
 * This is the model class for table "invt_r_vendor".
 *
 * @property integer $vendor_id
 * @property string $nama
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class Vendor extends \yii\db\ActiveRecord
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
        return 'invt_r_vendor';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [
            [['alamat'], 'required'],
            [['desc'], 'string'],
            [['deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama', 'alamat'], 'string', 'max' => 150],
            [['telp', 'telp_contact_person'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 100],
            [['link'], 'string', 'max' => 250],
            [['contact_person'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function attributeLabels() 
    { 
        return [ 
            'vendor_id' => 'Vendor ID',
            'nama' => 'Nama Vendor',
            'telp' => 'Telepon',
            'email' => 'Email',
            'alamat' => 'Alamat',
            'link' => 'Link/Website',
            'contact_person' => 'Contact Person',
            'telp_contact_person' => 'Telepon Contact Person',
            'desc' => 'Deksripsi',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ]; 
    } 
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getBarang() 
    { 
        return $this->hasMany(Barang::className(), ['vendor_id' => 'vendor_id']);
    } 
}

<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "invt_pic_barang_file".
 *
 * @property integer $pic_barang_file_id
 * @property string $nama_file
 * @property string $kode_file
 * @property integer $pic_barang_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property InvtPicBarang $picBarang
 */
class PicBarangFile extends \yii\db\ActiveRecord
{
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
                'enableSoftDelete' => false, //default, set false jika behavior ini ingin di bypass. cth, untuk proses debugging
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_pic_barang_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pic_barang_id', 'deleted'], 'integer'],
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
            'pic_barang_file_id' => 'Pic Barang File ID',
            'nama_file' => 'Nama File',
            'kode_file' => 'Kode File',
            'pic_barang_id' => 'Pic Barang ID',
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
    public function getPicBarang()
    {
        return $this->hasOne(PicBarang::className(), ['pic_barang_id' => 'pic_barang_id']);
    }
}

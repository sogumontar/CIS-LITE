<?php

namespace backend\modules\mref\models;

use Yii;

use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\models\validators\EmailStudentsValidator;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_asal_sekolah".
 *
 * @property integer $asal_sekolah_id
 * @property string $nama
 * @property string $alamat
 * @property integer $kabupaten_id
 * @property string $kodepos
 * @property string $telepon
 * @property string $email
 * @property integer $deleted
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property "mrefRKabupaten" $"kabupatenId"
 */
class AsalSekolah extends \yii\db\ActiveRecord
{
    
    /**
     * behaviour to add created_at and updatet_at field with current datetime (timestamp)
     * and created_by and updated_by field with current user id (blameable)
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mref_r_asal_sekolah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alamat'], 'string'],
            [['alamat'], 'required'],
            [['kabupaten_id', 'deleted'], 'integer'],
            [['kabupaten_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 45],
            [['nama'], 'required'],
            [['kodepos'], 'string', 'max' => 8],
            [['telepon'], 'string', 'max' => 20],

            [['email'], 'string', 'max' => 50],
            [['email'], 'required'],
            [['email'], EmailStudentsValidator::className()],

            [['created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asal_sekolah_id' => 'Asal Sekolah ID',
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'kabupaten_id' => 'Nama Kabupaten',
            'kodepos' => 'Kodepos',
            'telepon' => 'Telepon',
            'email' => 'Email',
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupaten()
    {
        return $this->hasOne(Kabupaten::className(), ["kabupaten_id" => "kabupaten_id"]);
    }
}

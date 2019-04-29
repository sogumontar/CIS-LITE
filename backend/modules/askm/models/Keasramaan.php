<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_keasramaan".
 *
 * @property integer $keasramaan_id
 * @property string $no_hp
 * @property string $email
 * @property integer $asrama_id
 * @property integer $pegawai_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Pegawai $pegawai
 * @property Asrama $asrama
 */
class Keasramaan extends \yii\db\ActiveRecord
{

    /**
     * behaviour to add created_at and updatet_at field with current datetime (timestamp)
     * and created_by and updated_by field with current user id (blameable)
     */
    public function behaviors(){
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
            ],
            'delete' => [
                'class' => DeleteBehavior::className(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'askm_keasramaan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asrama_id', 'pegawai_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['no_hp', 'deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['asrama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asrama::className(), 'targetAttribute' => ['asrama_id' => 'asrama_id']],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keasramaan_id' => 'Keasramaan ID',
            'no_hp' => 'No Hp',
            'email' => 'Email',
            'asrama_id' => 'Asrama ID',
            'pegawai_id' => 'Pegawai ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsrama()
    {
        return $this->hasOne(Asrama::className(), ['asrama_id' => 'asrama_id']);
    }
}

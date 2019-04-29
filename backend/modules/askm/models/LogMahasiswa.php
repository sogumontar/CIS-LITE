<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_log_mahasiswa".
 *
 * @property integer $log_mahasiswa_id
 * @property integer $dim_id
 * @property string $tanggal_keluar
 * @property string $tanggal_masuk
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Dim $dim
 */
class LogMahasiswa extends \yii\db\ActiveRecord
{
    public $realisasi_berangkat;
    public $realisasi_kembali;

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
        return 'askm_log_mahasiswa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dim_id', 'deleted'], 'integer'],
            [['realisasi_berangkat', 'realisasi_kembali', 'tanggal_keluar', 'tanggal_masuk', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_mahasiswa_id' => 'Log Mahasiswa ID',
            'dim_id' => 'Dim ID',
            'tanggal_keluar' => 'Tanggal Keluar',
            'tanggal_masuk' => 'Tanggal Masuk',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function afterFind(){
        parent::afterFind();

        $this->realisasi_berangkat = $this->tanggal_keluar;
        $this->realisasi_kembali = $this->tanggal_masuk;

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(Dim::className(), ['dim_id' => 'dim_id']);
    }
}

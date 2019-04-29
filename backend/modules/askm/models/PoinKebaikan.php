<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_poin_kebaikan".
 *
 * @property integer $kebaikan_id
 * @property string $name
 * @property string $desc
 * @property integer $penilaian_id
 * @property integer $pelanggaran_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property DimPelanggaran $pelanggaran
 * @property DimPenilaian $penilaian
 */
class PoinKebaikan extends \yii\db\ActiveRecord
{
    public $is_tebus;
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
        return 'askm_poin_kebaikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['penilaian_id'], 'required'],
            [['penilaian_id', 'pelanggaran_id', 'deleted'], 'integer'],
            [['is_tebus', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['pelanggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPelanggaran::className(), 'targetAttribute' => ['pelanggaran_id' => 'pelanggaran_id']],
            [['penilaian_id'], 'exist', 'skipOnError' => false, 'targetClass' => DimPenilaian::className(), 'targetAttribute' => ['penilaian_id' => 'penilaian_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kebaikan_id' => 'Kebaikan ID',
            'name' => 'Tindakan',
            'desc' => 'Keterangan',
            'penilaian_id' => 'Penilaian ID',
            'pelanggaran_id' => 'Pelanggaran',
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
    public function getPelanggaran()
    {
        return $this->hasOne(DimPelanggaran::className(), ['pelanggaran_id' => 'pelanggaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenilaian()
    {
        return $this->hasOne(DimPenilaian::className(), ['penilaian_id' => 'penilaian_id']);
    }
}

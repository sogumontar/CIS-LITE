<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_dim_pelanggaran".
 *
 * @property integer $pelanggaran_id
 * @property integer $status_pelanggaran
 * @property integer $pembinaan_id
 * @property integer $penilaian_id
 * @property integer $poin_id
 * @property string $desc_pembinaan
 * @property string $desc_pelanggaran
 * @property string $tanggal
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Pembinaan $pembinaan
 * @property DimPenilaian $penilaian
 * @property PoinPelanggaran $poin
 * @property PoinKebaikan[] $askmPoinKebaikans
 */
class DimPelanggaran extends \yii\db\ActiveRecord
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
        return 'askm_dim_pelanggaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_pelanggaran', 'pembinaan_id', 'penilaian_id', 'poin_id', 'deleted'], 'integer'],
            [['pembinaan_id', 'penilaian_id', 'poin_id', 'tanggal'], 'required'],
            [['desc_pembinaan', 'desc_pelanggaran'], 'string'],
            [['tanggal', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['pembinaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pembinaan::className(), 'targetAttribute' => ['pembinaan_id' => 'pembinaan_id']],
            [['penilaian_id'], 'exist', 'skipOnError' => true, 'targetClass' => DimPenilaian::className(), 'targetAttribute' => ['penilaian_id' => 'penilaian_id']],
            [['poin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoinPelanggaran::className(), 'targetAttribute' => ['poin_id' => 'poin_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pelanggaran_id' => 'Pelanggaran',
            'status_pelanggaran' => 'Status Pelanggaran',
            'pembinaan_id' => 'Pembinaan',
            'penilaian_id' => 'Penilaian',
            'poin_id' => 'Poin Pelanggaran',
            'desc_pembinaan' => 'Ket. Pembinaan',
            'desc_pelanggaran' => 'Ket. Pelanggaran',
            'tanggal' => 'Tanggal',
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
    public function getPembinaan()
    {
        return $this->hasOne(Pembinaan::className(), ['pembinaan_id' => 'pembinaan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenilaian()
    {
        return $this->hasOne(DimPenilaian::className(), ['penilaian_id' => 'penilaian_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoin()
    {
        return $this->hasOne(PoinPelanggaran::className(), ['poin_id' => 'poin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoinKebaikans()
    {
        return $this->hasMany(PoinKebaikan::className(), ['pelanggaran_id' => 'pelanggaran_id']);
    }
}

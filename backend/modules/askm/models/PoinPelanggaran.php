<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_poin_pelanggaran".
 *
 * @property integer $poin_id
 * @property string $name
 * @property integer $poin
 * @property string $desc
 * @property integer $bentuk_id
 * @property integer $tingkat_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property DimPelanggaran[] $DimPelanggarans
 * @property TingkatPelanggaran $tingkat
 * @property BentukPelanggaran $bentuk
 */
class PoinPelanggaran extends \yii\db\ActiveRecord
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
        return 'askm_poin_pelanggaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['poin', 'bentuk_id', 'tingkat_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name', 'deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 255],
            [['tingkat_id'], 'exist', 'skipOnError' => true, 'targetClass' => TingkatPelanggaran::className(), 'targetAttribute' => ['tingkat_id' => 'tingkat_id']],
            [['bentuk_id'], 'exist', 'skipOnError' => true, 'targetClass' => BentukPelanggaran::className(), 'targetAttribute' => ['bentuk_id' => 'bentuk_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'poin_id' => 'Poin ID',
            'name' => 'Nama',
            'poin' => 'Poin',
            'desc' => 'Deskripsi',
            'bentuk_id' => 'Bentuk Pelanggaran',
            'tingkat_id' => 'Tingkat Pelanggaran',
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
    public function getDimPelanggarans()
    {
        return $this->hasMany(DimPelanggaran::className(), ['poin_id' => 'poin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTingkat()
    {
        return $this->hasOne(TingkatPelanggaran::className(), ['tingkat_id' => 'tingkat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBentuk()
    {
        return $this->hasOne(BentukPelanggaran::className(), ['bentuk_id' => 'bentuk_id']);
    }
}

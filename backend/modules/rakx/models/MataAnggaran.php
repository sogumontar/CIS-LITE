<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "rakx_mata_anggaran".
 *
 * @property integer $mata_anggaran_id
 * @property integer $standar_id
 * @property string $kode_anggaran
 * @property string $name
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxRStandar $standar
 * @property RakxStrukturJabatanHasMataAnggaran[] $rakxStrukturJabatanHasMataAnggarans
 */
class MataAnggaran extends \yii\db\ActiveRecord
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
        return 'rakx_mata_anggaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_anggaran', 'name'], 'required'],
            [['kode_anggaran'], 'unique'],
            [['standar_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['kode_anggaran'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 100],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['standar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Standar::className(), 'targetAttribute' => ['standar_id' => 'standar_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mata_anggaran_id' => 'Mata Anggaran ID',
            'standar_id' => 'Standar ID',
            'kode_anggaran' => 'Kode Anggaran',
            'name' => 'Nama Anggaran',
            'desc' => 'Deskripsi',
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
    public function getStandar()
    {
        return $this->hasOne(Standar::className(), ['standar_id' => 'standar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatanHasMataAnggarans()
    {
        return $this->hasMany(StrukturJabatanHasMataAnggaran::className(), ['mata_anggaran_id' => 'mata_anggaran_id']);
    }
}

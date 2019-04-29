<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "hrdx_staf".
 *
 * @property integer $staf_id
 * @property integer $pegawai_id
 * @property integer $prodi_id
 * @property integer $staf_role_id
 * @property string $aktif_start
 * @property string $aktif_end
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $temp_id_old
 *
 * @property HrdxRiwayatPendidikanOld[] $hrdxRiwayatPendidikanOlds
 * @property HrdxPegawai $pegawai
 * @property InstProdi $prodi
 * @property HrdxRStafRole $stafRole
 */
class Staf extends \yii\db\ActiveRecord
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
        return 'hrdx_staf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'prodi_id', 'staf_role_id', 'deleted'], 'integer'],
            [['aktif_start', 'aktif_end', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['temp_id_old'], 'string', 'max' => 100],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrdxPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['prodi_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstProdi::className(), 'targetAttribute' => ['prodi_id' => 'ref_kbk_id']],
            [['staf_role_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrdxRStafRole::className(), 'targetAttribute' => ['staf_role_id' => 'staf_role_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staf_id' => 'Staf ID',
            'pegawai_id' => 'Pegawai ID',
            'prodi_id' => 'Prodi ID',
            'staf_role_id' => 'Staf Role ID',
            'aktif_start' => 'Aktif Start',
            'aktif_end' => 'Aktif End',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'temp_id_old' => 'Temp Id Old',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxRiwayatPendidikanOlds()
    {
        return $this->hasMany(HrdxRiwayatPendidikanOld::className(), ['staf_id' => 'staf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(HrdxPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdi()
    {
        return $this->hasOne(InstProdi::className(), ['ref_kbk_id' => 'prodi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStafRole()
    {
        return $this->hasOne(HrdxRStafRole::className(), ['staf_role_id' => 'staf_role_id']);
    }
}

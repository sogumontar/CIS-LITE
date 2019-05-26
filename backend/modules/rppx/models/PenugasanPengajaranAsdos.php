<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "rppx_penugasan_pengajaran_asdos".
 *
 * @property integer $penugasan_pengajaran_id
 * @property integer $pengajaran_id
 * @property integer $kelas
 * @property integer $jumlah_kelas_riil
 * @property integer $kelas_tatap_muka
 * @property integer $asdos1
 * @property double $load1
 * @property integer $asdos2
 * @property double $load2
 * @property integer $asdos3
 * @property double $load3
 * @property integer $approved
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 *
 * @property KrkmKuliah $pengajaran
 * @property HrdxStaf $asdos10
 * @property HrdxStaf $asdos20
 * @property HrdxStaf $asdos30
 */
class PenugasanPengajaranAsdos extends \yii\db\ActiveRecord
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
        return 'rppx_penugasan_pengajaran_asdos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pengajaran_id', 'kelas', 'jumlah_kelas_riil', 'kelas_tatap_muka', 'asdos1', 'asdos2', 'asdos3', 'approved', 'deleted', 'updated_by'], 'integer'],
            [['load1', 'load2', 'load3'], 'number'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by'], 'string', 'max' => 32],
            [['pengajaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kuliah::className(), 'targetAttribute' => ['pengajaran_id' => 'kuliah_id']],
            [['asdos1'], 'exist', 'skipOnError' => true, 'targetClass' => Staf::className(), 'targetAttribute' => ['asdos1' => 'pegawai_id']],
            [['asdos2'], 'exist', 'skipOnError' => true, 'targetClass' => Staf::className(), 'targetAttribute' => ['asdos2' => 'pegawai_id']],
            [['asdos3'], 'exist', 'skipOnError' => true, 'targetClass' => Staf::className(), 'targetAttribute' => ['asdos3' => 'pegawai_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penugasan_pengajaran_id' => 'Penugasan Pengajaran ID',
            'pengajaran_id' => 'Pengajaran ID',
            'kelas' => 'Kelas',
            'jumlah_kelas_riil' => 'Jumlah Kelas Riil',
            'kelas_tatap_muka' => 'Kelas Tatap Muka',
            'asdos1' => 'Asdos1',
            'load1' => 'Load1',
            'asdos2' => 'Asdos2',
            'load2' => 'Load2',
            'asdos3' => 'Asdos3',
            'load3' => 'Load3',
            'approved' => 'Approved',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengajaran()
    {
        return $this->hasOne(KrkmKuliah::className(), ['kuliah_id' => 'pengajaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsdos10()
    {
        return $this->hasOne(HrdxStaf::className(), ['pegawai_id' => 'asdos1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsdos20()
    {
        return $this->hasOne(HrdxStaf::className(), ['pegawai_id' => 'asdos2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsdos30()
    {
        return $this->hasOne(HrdxStaf::className(), ['pegawai_id' => 'asdos3']);
    }
}

<?php

namespace backend\modules\hrdx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\hrdx\models\Jenjang;


/**
 * This is the model class for table "hrdx_riwayat_pendidikan".
 *
 * @property integer $riwayat_pendidikan_id
 * @property integer $jenjang_id
 * @property string $universitas
 * @property string $jurusan
 * @property string $thn_mulai
 * @property string $thn_selesai
 * @property string $ipk
 * @property string $gelar
 * @property string $judul_ta
 * @property integer $pegawai_id
 * @property string $website
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $profile_id
 * @property string $id_old
 * @property string $jenjang
 */
class RiwayatPendidikan extends \yii\db\ActiveRecord
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
        return 'hrdx_riwayat_pendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenjang_id', 'pegawai_id', 'deleted', 'profile_id'], 'integer'],
            [['judul_ta'], 'string'],
            [['jenjang_id', 'pegawai_id', 'thn_mulai', 'thn_selesai'],'required'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['universitas'], 'string', 'max' => 100],
            [['jurusan'], 'string', 'max' => 200],
            [['thn_mulai', 'thn_selesai'], 'string', 'max' => 50],
            [['ipk', 'gelar'], 'string', 'max' => 10],
            [['website'], 'string', 'max' => 255],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['id_old'], 'string', 'max' => 20],
            [['jenjang'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'riwayat_pendidikan_id' => 'Riwayat Pendidikan ID',
            'jenjang_id' => 'Jenjang',
            'universitas' => 'Universitas',
            'jurusan' => 'Jurusan',
            'thn_mulai' => 'Tahun Mulai',
            'thn_selesai' => 'Tahun Selesai',
            'ipk' => 'IPK',
            'gelar' => 'Gelar',
            'judul_ta' => 'Judul TA',
            'pegawai_id' => 'Pegawai ID',
            'website' => 'Website Universitas',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'profile_id' => 'Profile ID',
            'id_old' => 'Id Old',
            'jenjang' => 'Jenjang',
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
    public function getJenjangs()
    {
        return $this->hasOne(Jenjang::className(), ['jenjang_id' => 'jenjang_id']);
    }
}

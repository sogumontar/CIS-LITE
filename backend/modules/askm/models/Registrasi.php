<?php

namespace backend\modules\askm\models;

use Yii;

/**
 * This is the model class for table "adak_registrasi".
 *
 * @property integer $registrasi_id
 * @property string $nim
 * @property string $status_akhir_registrasi
 * @property string $ta
 * @property integer $sem_ta
 * @property integer $sem
 * @property string $tgl_daftar
 * @property double $keuangan
 * @property string $kelas
 * @property string $id
 * @property double $nr
 * @property integer $koa_approval
 * @property integer $koa_approval_bp
 * @property integer $kelas_id
 * @property integer $dosen_wali_id
 * @property integer $dim_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property Pegawai $dosenWali
 * @property Kelas $kelas0
 * @property Dim $dim
 */
class Registrasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adak_registrasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sem_ta', 'sem', 'koa_approval', 'koa_approval_bp', 'kelas_id', 'dosen_wali_id', 'dim_id', 'deleted'], 'integer'],
            [['tgl_daftar', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['keuangan', 'nr'], 'number'],
            [['nim'], 'string', 'max' => 8],
            [['status_akhir_registrasi'], 'string', 'max' => 50],
            [['ta'], 'string', 'max' => 30],
            [['kelas', 'id'], 'string', 'max' => 20],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dosen_wali_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['dosen_wali_id' => 'pegawai_id']],
            [['kelas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_id' => 'kelas_id']],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'registrasi_id' => 'Registrasi ID',
            'nim' => 'Nim',
            'status_akhir_registrasi' => 'Status Akhir Registrasi',
            'ta' => 'Ta',
            'sem_ta' => 'Sem Ta',
            'sem' => 'Sem',
            'tgl_daftar' => 'Tgl Daftar',
            'keuangan' => 'Keuangan',
            'kelas' => 'Kelas',
            'id' => 'ID',
            'nr' => 'Nr',
            'koa_approval' => 'Koa Approval',
            'koa_approval_bp' => 'Koa Approval Bp',
            'kelas_id' => 'Kelas ID',
            'dosen_wali_id' => 'Dosen Wali ID',
            'dim_id' => 'Dim ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenWali()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'dosen_wali_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelas0()
    {
        return $this->hasOne(Kelas::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(Dim::className(), ['dim_id' => 'dim_id']);
    }
}

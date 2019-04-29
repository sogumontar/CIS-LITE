<?php

namespace backend\modules\inst\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\mref\models\JenisKelamin;
use backend\modules\mref\models\Agama;
use backend\modules\mref\models\GolonganDarah;
use backend\modules\mref\models\Kabupaten;
use backend\modules\mref\models\StatusAktifPegawai;
use backend\modules\mref\models\StatusIkatanKerjaPegawai;
use backend\modules\mref\models\StatusMarital;
use backend\modules\admin\models\User;

/**
 * This is the model class for table "hrdx_pegawai".
 *
 * @property integer $pegawai_id
 * @property string $profile_old_id
 * @property string $nama
 * @property string $nip
 * @property string $kpt_no
 * @property string $kbk_id
 * @property integer $ref_kbk_id
 * @property string $alias
 * @property string $posisi
 * @property string $tempat_lahir
 * @property string $tgl_lahir
 * @property integer $agama_id
 * @property integer $jenis_kelamin_id
 * @property integer $golongan_darah_id
 * @property string $hp
 * @property string $telepon
 * @property resource $alamat
 * @property string $alamat_libur
 * @property string $kecamatan
 * @property string $kota
 * @property integer $kabupaten_id
 * @property string $kode_pos
 * @property string $no_ktp
 * @property string $email
 * @property string $ext_num
 * @property string $study_area_1
 * @property string $study_area_2
 * @property string $jabatan
 * @property integer $jabatan_akademik_id
 * @property integer $gbk_1
 * @property integer $gbk_2
 * @property integer $status_ikatan_kerja_pegawai_id
 * @property string $status_akhir
 * @property integer $status_aktif_pegawai_id
 * @property string $tanggal_masuk
 * @property string $tanggal_keluar
 * @property string $nama_bapak
 * @property string $nama_ibu
 * @property string $status
 * @property integer $status_marital_id
 * @property string $nama_p
 * @property string $tgl_lahir_p
 * @property string $tmp_lahir_p
 * @property string $pekerjaan_ortu
 * @property integer $user_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class Pegawai extends \yii\db\ActiveRecord
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
        return 'hrdx_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref_kbk_id', 'agama_id', 'jenis_kelamin_id', 'golongan_darah_id', 'kabupaten_id', 'jabatan_akademik_id', 'gbk_1', 'gbk_2', 'status_ikatan_kerja_pegawai_id', 'status_aktif_pegawai_id', 'status_marital_id', 'deleted'], 'integer'],
            [['tgl_lahir', 'tanggal_masuk', 'tanggal_keluar', 'tgl_lahir_p', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['user_id'],'required'],
            [['alamat', 'email'], 'string'],
            [['profile_old_id', 'kbk_id', 'hp'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 135],
            [['nip', 'telepon'], 'string', 'max' => 45],
            [['kpt_no'], 'string', 'max' => 10],
            [['alias'], 'string', 'max' => 9],
            [['posisi', 'alamat_libur', 'pekerjaan_ortu'], 'string', 'max' => 100],
            [['tempat_lahir'], 'string', 'max' => 60],
            [['kecamatan'], 'string', 'max' => 150],
            [['kota', 'study_area_1', 'study_area_2', 'nama_bapak', 'nama_ibu', 'nama_p', 'tmp_lahir_p'], 'string', 'max' => 50],
            [['kode_pos'], 'string', 'max' => 15],
            [['no_ktp'], 'string', 'max' => 255],
            [['ext_num'], 'string', 'max' => 3],
            [['jabatan', 'status_akhir', 'status'], 'string', 'max' => 1],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pegawai_id' => 'Pegawai ID',
            'profile_old_id' => 'Profile Old ID',
            'nama' => 'Nama',
            'nip' => 'Nip',
            'kpt_no' => 'Kpt No',
            'kbk_id' => 'Kbk ID',
            'ref_kbk_id' => 'Ref Kbk ID',
            'alias' => 'Alias',
            'posisi' => 'Posisi',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'agama_id' => 'Agama',
            'jenis_kelamin_id' => 'Jenis Kelamin',
            'golongan_darah_id' => 'Golongan Darah ',
            'hp' => 'Hp',
            'telepon' => 'Telepon',
            'alamat' => 'Alamat',
            'alamat_libur' => 'Alamat Libur',
            'kecamatan' => 'Kecamatan',
            'kota' => 'Kota',
            'kabupaten_id' => 'Kabupaten ',
            'kode_pos' => 'Kode Pos',
            'no_ktp' => 'No Ktp',
            'email' => 'Email',
            'ext_num' => 'Ext Num',
            'study_area_1' => 'Study Area 1',
            'study_area_2' => 'Study Area 2',
            'jabatan' => 'Jabatan',
            'jabatan_akademik_id' => 'Jabatan Akademik',
            'gbk_1' => 'Gbk 1',
            'gbk_2' => 'Gbk 2',
            'status_ikatan_kerja_pegawai_id' => 'Status Ikatan Kerja Pegawai',
            'status_akhir' => 'Status Akhir',
            'status_aktif_pegawai_id' => 'Status Aktif Pegawai',
            'tanggal_masuk' => 'Tanggal Masuk',
            'tanggal_keluar' => 'Tanggal Keluar',
            'nama_bapak' => 'Nama Bapak',
            'nama_ibu' => 'Nama Ibu',
            'status' => 'Status',
            'status_marital_id' => 'Status Marital',
            'nama_p' => 'Nama P',
            'tgl_lahir_p' => 'Tgl Lahir P',
            'tmp_lahir_p' => 'Tmp Lahir P',
            'pekerjaan_ortu' => 'Pekerjaan Ortu',
            'user_id' => 'User ID',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKelamin()
    {
        return $this->hasOne(JenisKelamin::className(), ["jenis_kelamin_id" => "jenis_kelamin_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgama()
    {
        return $this->hasOne(Agama::className(), ["agama_id" => "agama_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganDarah()
    {
        return $this->hasOne(GolonganDarah::className(), ["golongan_darah_id" => "golongan_darah_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKabupaten()
    {
        return $this->hasOne(Kabupaten::className(), ["kabupaten_id" => "kabupaten_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusAktifPegawai()
    {
        return $this->hasOne(StatusAktifPegawai::className(), ["status_aktif_pegawai_id" => "status_aktif_pegawai_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIkatanKerjaPegawai()
    {
        return $this->hasOne(StatusIkatanKerjaPegawai::className(), ["status_ikatan_kerja_pegawai_id" => "status_ikatan_kerja_pegawai_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusMarital()
    {
        return $this->hasOne(StatusMarital::className(), ["status_marital_id" => "status_marital_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ["user_id" => "user_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::className(), ["pegawai_id" => "pegawai_id"]);
    }

    public function getStaf()
    {
        return $this->hasOne(Staf::className(), ["pegawai_id" => "pegawai_id"]);
    }
}

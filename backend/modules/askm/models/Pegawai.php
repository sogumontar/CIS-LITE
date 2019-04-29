<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "hrdx_pegawai".
 *
 * @property integer $pegawai_id
 * @property string $profile_old_id
 * @property string $nama
 * @property string $user_name
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
 *
 * @property AdakPenugasanPengajaran[] $adakPenugasanPengajarans
 * @property AdakRegistrasi[] $adakRegistrasis
 * @property IzinBermalam[] $IzinBermalams
 * @property IzinKeluar[] $IzinKeluars
 * @property IzinKeluar[] $IzinKeluars0
 * @property IzinKeluar[] $IzinKeluars1
 * @property IzinKolaboratif[] $IzinKolaboratifs
 * @property IzinRuangan[] $IzinRuangans
 * @property Keasramaan[] $Keasramaans
 * @property HrdxDosen[] $hrdxDosens
 * @property MrefRJenisKelamin $jenisKelamin
 * @property MrefRAgama $agama
 * @property MrefRGolonganDarah $golonganDarah
 * @property MrefRJabatanAkademik $jabatanAkademik
 * @property MrefRKabupaten $kabupaten
 * @property InstProdi $refKbk
 * @property MrefRStatusAktifPegawai $statusAktifPegawai
 * @property MrefRStatusIkatanKerjaPegawai $statusIkatanKerjaPegawai
 * @property MrefRStatusMarital $statusMarital
 * @property User $user
 * @property HrdxPengajar[] $hrdxPengajars
 * @property HrdxRiwayatPendidikan[] $hrdxRiwayatPendidikans
 * @property HrdxStaf[] $hrdxStafs
 * @property InstPejabat[] $instPejabats
 * @property InvtPicBarang[] $invtPicBarangs
 * @property LppmTLogreview[] $lppmTLogreviews
 * @property LppmTPublikasi[] $lppmTPublikasis
 * @property PrklCourseUnit[] $prklCourseUnits
 * @property PrklKrsMhs[] $prklKrsMhs
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
            [['ref_kbk_id', 'agama_id', 'jenis_kelamin_id', 'golongan_darah_id', 'kabupaten_id', 'jabatan_akademik_id', 'gbk_1', 'gbk_2', 'status_ikatan_kerja_pegawai_id', 'status_aktif_pegawai_id', 'status_marital_id', 'user_id', 'deleted'], 'integer'],
            [['tgl_lahir', 'tanggal_masuk', 'tanggal_keluar', 'tgl_lahir_p', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['alamat', 'email'], 'string'],
            [['profile_old_id', 'kbk_id', 'hp'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 135],
            [['user_name', 'posisi', 'alamat_libur', 'pekerjaan_ortu'], 'string', 'max' => 100],
            [['nip', 'telepon'], 'string', 'max' => 45],
            [['kpt_no'], 'string', 'max' => 10],
            [['alias'], 'string', 'max' => 9],
            [['tempat_lahir'], 'string', 'max' => 60],
            [['kecamatan'], 'string', 'max' => 150],
            [['kota', 'study_area_1', 'study_area_2', 'nama_bapak', 'nama_ibu', 'nama_p', 'tmp_lahir_p'], 'string', 'max' => 50],
            [['kode_pos'], 'string', 'max' => 15],
            [['no_ktp'], 'string', 'max' => 255],
            [['ext_num'], 'string', 'max' => 3],
            [['jabatan', 'status_akhir', 'status'], 'string', 'max' => 1],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
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
            'user_name' => 'User Name',
            'nip' => 'Nip',
            'kpt_no' => 'Kpt No',
            'kbk_id' => 'Kbk ID',
            'ref_kbk_id' => 'Ref Kbk ID',
            'alias' => 'Alias',
            'posisi' => 'Posisi',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'agama_id' => 'Agama ID',
            'jenis_kelamin_id' => 'Jenis Kelamin ID',
            'golongan_darah_id' => 'Golongan Darah ID',
            'hp' => 'Hp',
            'telepon' => 'Telepon',
            'alamat' => 'Alamat',
            'alamat_libur' => 'Alamat Libur',
            'kecamatan' => 'Kecamatan',
            'kota' => 'Kota',
            'kabupaten_id' => 'Kabupaten ID',
            'kode_pos' => 'Kode Pos',
            'no_ktp' => 'No Ktp',
            'email' => 'Email',
            'ext_num' => 'Ext Num',
            'study_area_1' => 'Study Area 1',
            'study_area_2' => 'Study Area 2',
            'jabatan' => 'Jabatan',
            'jabatan_akademik_id' => 'Jabatan Akademik ID',
            'gbk_1' => 'Gbk 1',
            'gbk_2' => 'Gbk 2',
            'status_ikatan_kerja_pegawai_id' => 'Status Ikatan Kerja Pegawai ID',
            'status_akhir' => 'Status Akhir',
            'status_aktif_pegawai_id' => 'Status Aktif Pegawai ID',
            'tanggal_masuk' => 'Tanggal Masuk',
            'tanggal_keluar' => 'Tanggal Keluar',
            'nama_bapak' => 'Nama Bapak',
            'nama_ibu' => 'Nama Ibu',
            'status' => 'Status',
            'status_marital_id' => 'Status Marital ID',
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
    public function getIzinBermalams()
    {
        return $this->hasMany(IzinBermalam::className(), ['keasramaan_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinKeluars()
    {
        return $this->hasMany(IzinKeluar::className(), ['dosen_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinKeluars0()
    {
        return $this->hasMany(IzinKeluar::className(), ['keasramaan_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinKeluars1()
    {
        return $this->hasMany(IzinKeluar::className(), ['staf_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinKolaboratifs()
    {
        return $this->hasMany(IzinKolaboratif::className(), ['staf_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinRuangans()
    {
        return $this->hasMany(IzinRuangan::className(), ['staf_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeasramaans()
    {
        return $this->hasMany(Keasramaan::className(), ['pegawai_id' => 'pegawai_id']);
    }

}

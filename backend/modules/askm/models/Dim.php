<?php

namespace backend\modules\askm\models;

use Yii;
use yii\base\InvalidConfigException;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\askm\models\Registrasi;

/**
 * This is the model class for table "dimx_dim".
 *
 * @property integer $dim_id
 * @property string $nim
 * @property string $no_usm
 * @property string $jalur
 * @property string $user_name
 * @property string $kbk_id
 * @property string $kpt_prodi
 * @property integer $id_kur
 * @property string $nama
 * @property string $tgl_lahir
 * @property string $tempat_lahir
 * @property string $gol_darah
 * @property string $jenis_kelamin
 * @property string $agama
 * @property string $alamat
 * @property string $kabupaten
 * @property string $kode_pos
 * @property string $email
 * @property string $telepon
 * @property string $hp
 * @property string $hp2
 * @property string $no_ijazah_sma
 * @property string $nama_sma
 * @property string $alamat_sma
 * @property string $kabupaten_sma
 * @property string $telepon_sma
 * @property string $kodepos_sma
 * @property integer $thn_masuk
 * @property string $status_akhir
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string $no_hp_ayah
 * @property string $no_hp_ibu
 * @property string $alamat_orangtua
 * @property string $pekerjaan_ayah
 * @property string $keterangan_pekerjaan_ayah
 * @property string $penghasilan_ayah
 * @property string $pekerjaan_ibu
 * @property string $keterangan_pekerjaan_ibu
 * @property string $penghasilan_ibu
 * @property string $nama_wali
 * @property string $pekerjaan_wali
 * @property string $keterangan_pekerjaan_wali
 * @property string $penghasilan_wali
 * @property string $alamat_wali
 * @property string $telepon_wali
 * @property string $no_hp_wali
 * @property string $pendapatan
 * @property double $ipk
 * @property integer $anak_ke
 * @property integer $dari_jlh_anak
 * @property integer $jumlah_tanggungan
 * @property double $nilai_usm
 * @property integer $score_iq
 * @property string $rekomendasi_psikotest
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $tahun_kurikulum_id
 *
 * @property "instRProdi" $"prodiId"
 * @property "krkmRTahunKurikulum" $"tahunKurikulumId"
 */
class Dim extends \yii\db\ActiveRecord
{

    /**
     * behaviour to add created_at and updatet_at field with current datetime (timestamp)
     * and created_by and updated_by field with current user id (blameable)
     */

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => DeleteBehavior::className(),
                'hardDeleteTaskName' => 'HardDeleteDB', //default
                'enableSoftDelete' => true, //default, set false jika behavior ini ingin di bypass. cth, untuk proses debugging
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dimx_dim';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kur', 'thn_masuk', 'anak_ke', 'dari_jlh_anak', 'jumlah_tanggungan', 'score_iq', 'deleted', 'tahun_kurikulum_id',  'user_id', 'asal_sekolah_id', 'agama_id', 'jenis_kelamin_id', 'golongan_darah_id','pekerjaan_ayah_id', 'penghasilan_ayah_id', 'pekerjaan_ibu_id', 'penghasilan_ibu_id', 'pekerjaan_wali_id'
                , 'penghasilan_wali_id', 'penghasilan_ayah', 'penghasilan_ibu', 'penghasilan_wali'], 'integer'],
            [['tgl_lahir', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['alamat', 'alamat_sma', 'alamat_orangtua', 'keterangan_pekerjaan_ayah', 'keterangan_pekerjaan_ibu', 'keterangan_pekerjaan_wali', 'alamat_wali'], 'string'],
            [['ipk', 'nilai_usm', 'kode_pos'], 'number'],
            [['nim', 'kodepos_sma'], 'string', 'max' => 8],
            [['no_usm'], 'string', 'max' => 15],
            [['jalur', 'kbk_id', 'telepon_wali'], 'string', 'max' => 20],
            [['user_name', 'kpt_prodi'], 'string', 'max' => 10],
            [['nama', 'tempat_lahir', 'kabupaten', 'email', 'telepon', 'hp', 'hp2', 'nama_sma', 'telepon_sma', 'status_akhir', 'nama_ayah', 'nama_ibu', 'no_hp_ayah', 'no_hp_ibu', 'penghasilan_ayah', 'penghasilan_ibu', 'nama_wali', 'pekerjaan_wali', 'penghasilan_wali', 'no_hp_wali', 'pendapatan'], 'string', 'max' => 50],
            [['gol_darah'], 'string', 'max' => 2],
            [['jenis_kelamin'], 'string', 'max' => 1],
            [['agama'], 'string', 'max' => 30],
            [['kode_pos'], 'string', 'max' => 5],
            [['no_ijazah_sma', 'kabupaten_sma', 'pekerjaan_ayah', 'pekerjaan_ibu'], 'string', 'max' => 100],
            [['rekomendasi_psikotest'], 'string', 'max' => 4],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['nim'], 'unique'],
            [['foto', 'kode_foto'], 'safe'],
            [['tgl_lahir'], 'validateDate'],
            [['nim','nama','tgl_lahir','tempat_lahir','jenis_kelamin_id','agama_id', 'golongan_darah_id', 'alamat', 'thn_masuk', 'user_name','tahun_kurikulum_id','ref_kbk_id','nama_ayah','nama_ibu', 'hp'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dim_id' => 'Dim ID',
            'nim' => 'NIM',
            'no_usm' => 'No Usm',
            'jalur' => 'Jalur',
            'user_name' => 'User Name',
            'kbk_id' => 'Kbk ID',
            'kpt_prodi' => 'Kpt Prodi',
            'id_kur' => 'Id Kur',
            'nama' => 'Nama',
            'tgl_lahir' => 'Tgl Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'gol_darah' => 'Gol Darah',
            'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'alamat' => 'Alamat',
            'kabupaten' => 'Kabupaten',
            'kode_pos' => 'Kode Pos',
            'email' => 'Email',
            'telepon' => 'Telepon',
            'hp' => 'Hp',
            'hp2' => 'Hp2',
            'no_ijazah_sma' => 'No Ijazah Sma',
            'nama_sma' => 'Nama Sma',
            'alamat_sma' => 'Alamat Sma',
            'kabupaten_sma' => 'Kabupaten Sma',
            'telepon_sma' => 'Telepon Sma',
            'kodepos_sma' => 'Kodepos Sma',
            'thn_masuk' => 'Tahun Masuk',
            'status_akhir' => 'Status Akhir',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
            'no_hp_ayah' => 'No. Hp Ayah',
            'no_hp_ibu' => 'No. Hp Ibu',
            'alamat_orangtua' => 'Alamat Orangtua',
            'pekerjaan_ayah' => 'Pekerjaan Ayah',
            'keterangan_pekerjaan_ayah' => 'Keterangan Pekerjaan Ayah',
            'penghasilan_ayah' => 'Penghasilan Ayah',
            'pekerjaan_ibu' => 'Pekerjaan Ibu',
            'keterangan_pekerjaan_ibu' => 'Keterangan Pekerjaan Ibu',
            'penghasilan_ibu' => 'Penghasilan Ibu',
            'nama_wali' => 'Nama Wali',
            'pekerjaan_wali' => 'Pekerjaan Wali',
            'keterangan_pekerjaan_wali' => 'Keterangan Pekerjaan Wali',
            'penghasilan_wali' => 'Penghasilan Wali',
            'alamat_wali' => 'Alamat Wali',
            'telepon_wali' => 'Telepon Wali',
            'no_hp_wali' => 'No Hp Wali',
            'pendapatan' => 'Pendapatan',
            'ipk' => 'Ipk',
            'anak_ke' => 'Anak Ke',
            'dari_jlh_anak' => 'Dari Jlh Anak',
            'jumlah_tanggungan' => 'Jumlah Tanggungan',
            'nilai_usm' => 'Nilai Usm',
            'score_iq' => 'Score Iq',
            'rekomendasi_psikotest' => 'Rekomendasi Psikotest',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'tahun_kurikulum_id' => 'Tahun Kurikulum ID',
            'user_id'=>'User ID',
            'ref_kbk_id' =>'Ref Kbk ID',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKbkId()
    {
        return $this->hasOne(Prodi::className(), ['ref_kbk_id'=>'ref_kbk_id']);
    }

    public function getAbsnAbsensis()
    {
        return $this->hasMany(AbsnAbsensi::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdakMahasiswaAssistants()
    {
        return $this->hasMany(AdakMahasiswaAssistant::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrasis()
    {
        return $this->hasMany(Registrasi::className(), ['dim_id'=>'dim_id']);
        //return $this->hasOne(Registrasi::className(), ['dim_id' => 'dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['adak_registrasi.status_akhir_registrasi' => 'Aktif'])->orderBy(['adak_registrasi.ta' => SORT_DESC, 'adak_registrasi.sem_ta' => SORT_DESC]);
    }

    // public function getRegistrasis()
    // {
    //     return $this->hasOne(Registrasi::className(), ['dim_id'=>'dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['adak_registrasi.status_akhir_registrasi' => 'Aktif'])->orderBy(['adak_registrasi.ta' => SORT_DESC, 'adak_registrasi.sem_ta' => SORT_DESC]);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimKamar()
    {
        return $this->hasMany(DimKamar::className(), ['dim_id' => 'dim_id']);
    }

    public function getDimAsrama()
    {
        return $this->hasOne(DimKamar::className(), ['dim_id' => 'dim_id']);//->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinBermalams()
    {
        return $this->hasMany(AskmIzinBermalam::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKeluars()
    {
        return $this->hasMany(AskmIzinKeluar::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKolaboratifs()
    {
        return $this->hasMany(AskmIzinKolaboratif::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinRuangans()
    {
        return $this->hasMany(AskmIzinRuangan::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmLogMahasiswas()
    {
        return $this->hasMany(AskmLogMahasiswa::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxAlumnis()
    {
        return $this->hasMany(DimxAlumni::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgama0()
    {
        return $this->hasOne(MrefRAgama::className(), ['agama_id' => 'agama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsalSekolah()
    {
        return $this->hasOne(MrefRAsalSekolah::className(), ['asal_sekolah_id' => 'asal_sekolah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganDarah()
    {
        return $this->hasOne(MrefRGolonganDarah::className(), ['golongan_darah_id' => 'golongan_darah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKelamin()
    {
        return $this->hasOne(MrefRJenisKelamin::className(), ['jenis_kelamin_id' => 'jenis_kelamin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanAyah()
    {
        return $this->hasOne(MrefRPekerjaan::className(), ['pekerjaan_id' => 'pekerjaan_ayah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanIbu()
    {
        return $this->hasOne(MrefRPekerjaan::className(), ['pekerjaan_id' => 'pekerjaan_ibu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPekerjaanWali()
    {
        return $this->hasOne(MrefRPekerjaan::className(), ['pekerjaan_id' => 'pekerjaan_wali_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenghasilanAyah()
    {
        return $this->hasOne(MrefRPenghasilan::className(), ['penghasilan_id' => 'penghasilan_ayah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenghasilanIbu()
    {
        return $this->hasOne(MrefRPenghasilan::className(), ['penghasilan_id' => 'penghasilan_ibu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenghasilanWali()
    {
        return $this->hasOne(MrefRPenghasilan::className(), ['penghasilan_id' => 'penghasilan_wali_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKbk()
    {
        return $this->hasOne(Prodi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunKurikulum()
    {
        return $this->hasOne(KrkmRTahunKurikulum::className(), ['tahun_kurikulum_id' => 'tahun_kurikulum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SysxUser::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxDimPmbs()
    {
        return $this->hasMany(DimxDimPmb::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxDimPmbDaftars()
    {
        return $this->hasMany(DimxDimPmbDaftar::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxDimTrnonLuluses()
    {
        return $this->hasMany(DimxDimTrnonLulus::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxHistoriProdis()
    {
        return $this->hasMany(DimxHistoriProdi::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsDetailKasuses()
    {
        return $this->hasMany(KmhsDetailKasus::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsMasterKasuses()
    {
        return $this->hasMany(KmhsMasterKasus::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsNilaiPerilakus()
    {
        return $this->hasMany(KmhsNilaiPerilaku::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsNilaiPerilakuArsips()
    {
        return $this->hasMany(KmhsNilaiPerilakuArsip::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsNilaiPerilakuAs()
    {
        return $this->hasMany(KmhsNilaiPerilakuAs::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsNilaiPerilakuSummaries()
    {
        return $this->hasMany(KmhsNilaiPerilakuSummary::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKmhsNilaiPerilakuTs()
    {
        return $this->hasMany(KmhsNilaiPerilakuTs::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiExtMhs()
    {
        return $this->hasMany(NlaiExtMhs::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilais()
    {
        return $this->hasMany(NlaiNilai::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiKomponenTambahans()
    {
        return $this->hasMany(NlaiNilaiKomponenTambahan::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiPraktikums()
    {
        return $this->hasMany(NlaiNilaiPraktikum::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiQuis()
    {
        return $this->hasMany(NlaiNilaiQuis::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiTugas()
    {
        return $this->hasMany(NlaiNilaiTugas::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiUas()
    {
        return $this->hasMany(NlaiNilaiUas::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiNilaiUts()
    {
        return $this->hasMany(NlaiNilaiUts::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiUasDetails()
    {
        return $this->hasMany(NlaiUasDetail::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNlaiUtsDetails()
    {
        return $this->hasMany(NlaiUtsDetail::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklBeritaAcaraDaftarHadirs()
    {
        return $this->hasMany(PrklBeritaAcaraDaftarHadir::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklInfoTas()
    {
        return $this->hasMany(PrklInfoTa::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKrsMhs()
    {
        return $this->hasMany(PrklKrsMhs::className(), ['dim_id' => 'dim_id']);
    }
}

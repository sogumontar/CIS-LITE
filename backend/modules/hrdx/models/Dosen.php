<?php

namespace backend\modules\hrdx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\DeleteBehavior;
use yii\behaviors\BlameableBehavior;
use backend\modules\mref\models\GolonganKepangkatan;
use backend\modules\mref\models\JabatanAkademik;
use backend\modules\mref\models\Gbk;
use backend\modules\mref\models\StatusIkatanKerjaDosen;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\hrdx\models\RiwayatPendidikan;
use backend\modules\hrdx\models\Prodi;
use backend\modules\hrdx\models\Dosen;
use backend\modules\jdwl\models\Pengajaran;
use backend\modules\jdwl\models\JadwalSesi;
use yii2fullcalendar\models\Event;
use backend\modules\adak\models\Registrasi;
use backend\modules\askm\models\IzinKeluar;
use yii\base\InvalidConfigException;
/**
 * This is the model class for table "hrdx_dosen".
 *
 * @property integer $dosen_id
 * @property integer $prodi_id
 * @property integer $golongan_kepangkatan_id
 * @property integer $jabatan_akademik_id
 * @property integer $status_ikatan_kerja_dosen_id
 * @property integer $gbk_id
 * @property string $aktif_start
 * @property string $aktif_end
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $pegawai_id
 * @property string $nidn
 *
 * @property "mrefRGolonganKepangkatan" $"golonganKepangkatanId"
 * @property "mrefRGbk" $"gbkId"
 * @property "mrefRJabatanAkademik" $"jabatanAkademikId"
 * @property "hrdxPegawai" $"pegawaiId"
 * @property "mrefRStatusIkatanKerjaDosen" $"statusIkatanKerjaDosenId"
 */
class Dosen extends \yii\db\ActiveRecord
{
    
    public $status_aktif='';
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
        return 'hrdx_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [ 
            [['prodi_id', 'jabatan_akademik_id', 'status_ikatan_kerja_dosen_id', 'gbk_1', 'gbk_2', 'aktif_start'], 'required'],
            [['prodi_id', 'golongan_kepangkatan_id', 'jabatan_akademik_id', 'status_ikatan_kerja_dosen_id',  'deleted', 'pegawai_id'], 'integer'],
            [['aktif_start', 'aktif_end', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['nidn'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_id' => 'Dosen ID',
            'prodi_id' => 'Prodi',
            'pegawai_id' => 'Pegawai ID',
            'golongan_kepangkatan_id' => 'Golongan Kepangkatan',
            'jabatan_akademik_id' => 'Jabatan Akademik',
            'status_ikatan_kerja_dosen_id' => 'Status Ikatan Kerja Dosen',
            'gbk_1' => 'Gbk',
            'gbk_2' => 'Gbk',
            'aktif_start' => 'Aktif Start',
            'aktif_end' => 'Aktif End',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'pegawai_id' => 'Pegawai',
            'nidn' => 'NIDN',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganKepangkatan()
    {
        return $this->hasOne(GolonganKepangkatan::className(), ["golongan_kepangkatan_id" => "golongan_kepangkatan_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getProdi()
    // {
    //     return $this->hasOne(Prodi::className(), ["prodi_id" => "prodi_id"]);
    // }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanAkademik()
    {
        return $this->hasOne(JabatanAkademik::className(), ["jabatan_akademik_id" => "jabatan_akademik_id"]);
    }

    // /**
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getGbk()
    // {
    //     return $this->hasOne(Pegawai::className(), ["gbk_id" => "gbk_1"]);
    // }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ["pegawai_id" => "pegawai_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIkatanKerjaDosen()
    {
        return $this->hasOne(StatusIkatanKerjaDosen::className(), ["status_ikatan_kerja_dosen_id" => "status_ikatan_kerja_dosen_id"]);
    }

   public function getRiwayatPendidikan()
   {
        return $this->hasMany(RiwayatPendidikan::className(),['pegawai_id'=> "pegawai_id"]);

   }
   /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdi()
    {
        return $this->hasOne(Prodi::className(), ["ref_kbk_id" => "prodi_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIkatanKerja()
    {
        return $this->hasOne(StatusIkatanKerjaDosen::className(), ["status_ikatan_kerja_dosen_id" => "status_ikatan_kerja_dosen_id"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getKelasWali()
    {
        return $this->hasOne(Kelas::className(), ["dosen_wali_id" => 'dosen_id']);
    }*/
    

    public function getMyCourses($ta, $sem_ta)
    {
        $pegawai_id =$this->pegawai_id;
        // $pegawai_id = 2;
        // $sem_ta =1;
        // $ta=2014;

        $modelPenugasan = Penugasan::find()->
                                    with('pengajaran.kuliah')->
                                    joinWith('pengajaran')->
                                    where(['adak_pengajaran.sem_ta'=>$sem_ta , 'adak_pengajaran.ta' =>$ta])->
                                    andWhere(['adak_penugasan_pengajaran.deleted' => 0])->
                                    andWhere(['adak_penugasan_pengajaran.pegawai_id' => $pegawai_id])->andWhere(['adak_penugasan_pengajaran.is_fulltime' => 1])->
                                    all();
        $kuliah = [];
        foreach ($modelPenugasan as $penugasan) {
            $kuliah[] = $penugasan->pengajaran->kuliah;
        }

        return $kuliah;
    }

    public function getJadwalToday($date, &$bg_key){
        $time = strtotime($date);
        $day = date('w',$time);
        $ta = \Yii::$app->appConfig->get('tahun_ajaran', true);
        $sem_ta = \Yii::$app->appConfig->get('semester_tahun_ajaran', true);
        
        $user_id = Yii::$app->user->getId();
        $pegawai = Pegawai::find()->where('deleted!=1')->andWhere(['user_id'=>$user_id])->one();
        
        $pengajaran = Pengajaran::find()->select(['adak_pengajaran.kuliah_id'])->where('adak_pengajaran.deleted!=1')->andWhere(['adak_pengajaran.ta' => $ta, 'adak_pengajaran.sem_ta' => $sem_ta])
        ->joinWith(['penugasanPengajaran'=>function($query) use($pegawai){
            $query->andWhere(['adak_penugasan_pengajaran.pegawai_id'=>$pegawai->pegawai_id]);
        }]);

        $jadwalSesi = JadwalSesi::find()->where('jdwl_jadwal_sesi.deleted!=1')->joinWith(['jadwal'=>function($query) use($pengajaran,$sem_ta,$ta,$day){
            $query->where('jdwl_jadwal.deleted!=1')->andWhere(['in','jdwl_jadwal.kuliah_id',$pengajaran])
            ->andWhere(['jdwl_jadwal.ta'=>$ta,'jdwl_jadwal.sem_ta'=>$sem_ta,'jdwl_jadwal.hari_id'=>$day,'jdwl_jadwal.successor'=>NULL]);
        }])->all();

        $events = array();

        foreach($jadwalSesi as $data){
            $jdwl = new Event();
            $jdwl->id = $data->jadwal_id;
            if($data->jadwal->type===1){
                $type = 'Teori';
                $shortType = '(T)';
            }else if($data->jadwal->type===2){
                $type ='Praktikum';
                $shortType = '(P)';
            }
            $jdwl->title = (isset($data->jadwal->kuliah->short_name) && trim($data->jadwal->kuliah->short_name)!=''?$data->jadwal->kuliah->short_name:$data->jadwal->kuliah->kode_mk).' '.$shortType.' - '.$data->jadwal->lokasi->name.' - '.$data->jadwal->kelas->nama;
            if(!array_key_exists($data->jadwal->kuliah_id, $bg_key))
                $bg_key[$data->jadwal->kuliah_id] = '#'.substr(md5(rand()), 0, 6);
            $jdwl->backgroundColor = $bg_key[$data->jadwal->kuliah_id];
            $jdwl->textColor = '#000000';
            $jdwl->nonstandard = [
                'matkul' => $data->jadwal->kuliah->nama_kul_ind,
                'type' => $type,
                'shortname' => $data->jadwal->kuliah->short_name,
                'lokasi'=>$data->jadwal->lokasi->name,
                'kode'=>$data->jadwal->kuliah->kode_mk,
                'kelas'=>$data->jadwal->kelas->nama,
            ];
            $jdwl->start = date('Y-m-d\TH:i:s\Z',strtotime($date.' '.$data->sesi->start));
            $jdwl->end = date('Y-m-d\TH:i:s\Z',strtotime($date.' '.$data->sesi->end));
            $events[] = $jdwl;
        }
        return $events;
    }

    public function getWaliMhsIk(){
        $dosen_wali = $this->pegawai_id;
        $dim_arr = Registrasi::find()->select(['adak_registrasi.dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->joinWith(['dosenWali' => function($query) use($dosen_wali){
                $query->where(['hrdx_pegawai.pegawai_id' => $dosen_wali]);
        }]);
        $ik = IzinKeluar::find()->where('deleted!=1')->andWhere(['in', 'askm_izin_keluar.dim_id', $dim_arr])->andWhere(['status_request_dosen_wali' => 1])->andWhere(['>', 'rencana_berangkat', date('Y-m-d H:i:s')])->orderBy(['rencana_berangkat' => SORT_ASC])->all();
        return $ik;
    }

    /**get Dosen Aktif**/
    public static function getDosenAktif()
    {
        $pegawai_aktif = Pegawai::find()
                                ->where(['status_aktif_pegawai_id'=>1])
                                ->andWhere(['tanggal_keluar'=>0])
                                ->andWhere(['deleted'=>0])
                                ->orderBy(['nama'=>SORT_ASC])
                                ->all();
        $dosen_aktif = [];
        $i = 0;
        foreach ($pegawai_aktif as $key => $value) {
            $_dosen = Dosen::find()
                                ->where(['aktif_end'=>0])
                                ->orWhere(['aktif_end'=>NULL])
                                ->andWhere(['deleted'=>0])
                                ->andWhere(['pegawai_id'=>$value->pegawai_id])
                                ->one();
            if($_dosen!=null)
            {
                $dosen_aktif[$i]= $_dosen;
            }
            $i++;
        }
        return $dosen_aktif;
    } 

}

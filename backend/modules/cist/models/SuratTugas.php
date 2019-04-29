<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\cist\models\AtasanSuratTugas;
use backend\modules\inst\models\InstApiModel;
use backend\modules\sppd\models\BiayaPerjalanan;

/**
 * This is the model class for table "cist_surat_tugas".
 *
 * @property integer $surat_tugas_id
 * @property integer $perequest
 * @property string $no_surat
 * @property string $tempat
 * @property string $tanggal_berangkat
 * @property string $tanggal_kembali
 *  * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $agenda
 * @property string $review_surat
 * @property string $desc_surat_tugas
 * @property string $pengalihan_tugas
 * @property integer $jenis_surat_id
 * @property integer $surat_tugas_file_id
 * @property integer $name
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property CistLaporanSuratTugas[] $laporanSuratTugas
 * @property HrdxPegawai $atasan0
 * @property CistStatus $statusName
 * @property CistSuratTugasFile $idSuratTugasFile
 * @property CistJenisSurat $idJenisSurat
 * @property HrdxPegawai $pengalihanTugas
 * @property SysxUser $perequest0
 * @property CistSuratTugasAssignee[] $suratTugasAssignees
 */
class SuratTugas extends \yii\db\ActiveRecord
{
    public $files, $atasan, $review_laporan;

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
        return 'cist_surat_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['perequest', 'tanggal_berangkat', 'tanggal_kembali', 'tanggal_mulai', 'tanggal_selesai', 'kembali_bekerja', 'nama_kegiatan', 'agenda', 'tempat'], 'required'],
            [['perequest', 'jenis_surat_id', 'status_id', 'deleted', 'penandatangan', 'penyetuju'], 'integer'],
            [['tanggal_berangkat', 'tanggal_kembali', 'tanggal_mulai', 'tanggal_selesai', 'kembali_bekerja', 'realisasi_berangkat', 'realisasi_kembali', 'deleted_at', 'updated_at', 'created_at', 'status_sppd', 'review_surat', 'tanggal_surat'], 'safe'],
            [['desc_surat_tugas', 'pengalihan_tugas', 'transportasi', 'catatan', 'tempat', 'agenda', 'review_laporan'], 'string'],
            [['no_surat'], 'string', 'max' => 45],
            [['nama_kegiatan'], 'string', 'max' => 100],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['files'], 'file', 'maxFiles' => 0],
            [['atasan'], 'each', 'rule' => ['integer']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']],
            [['jenis_surat_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisSurat::className(), 'targetAttribute' => ['jenis_surat_id' => 'jenis_surat_id']],
            [['perequest'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['perequest' => 'pegawai_id']],
            [['penyetuju'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['penyetuju' => 'pegawai_id']],
            [['tanggal_berangkat'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['tanggal_berangkat'], 'berangkatValid'],
            [['tanggal_kembali'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['tanggal_kembali'], 'kembaliValid'],
            [['tanggal_mulai'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['tanggal_mulai'], 'mulaiValid'],
            [['tanggal_selesai'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['tanggal_selesai'], 'selesaiValid'],
            [['kembali_bekerja'], 'date', 'format' => 'php:Y-m-d H:i'],
            [['kembali_bekerja'], 'bekerjaValid']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penyetuju' => 'Penyetuju/Penolak/Reviewer',
            'surat_tugas_id' => 'Id Surat Tugas',
            'perequest' => 'Pemohon/Pemberi Tugas',
            'no_surat' => 'No Surat',
            'tempat' => 'Alamat',
            'tanggal_berangkat' => 'Tanggal Berangkat',
            'tanggal_kembali' => 'Tanggal Kembali',
            'tanggal_mulai' => 'Tanggal Mulai Kegiatan',
            'tanggal_selesai' => 'Tanggal Selesai Kegiatan',
            'nama_kegiatan' => 'Nama Kegiatan',
            'agenda' => 'Agenda',
            'review_surat' => 'Review Surat',
            'desc_surat_tugas' => 'Keterangan',
            'pengalihan_tugas' => 'Pengalihan Tugas',
            'atasan' => 'Atasan yang Menyetujui/Menolak',
            'files' => 'Tambah Lampiran',
            'transportasi' => 'Transportasi',
            'catatan' => 'Catatan',
            'jenis_surat_id' => 'Id Jenis Surat',
            'status_id' => 'Status',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function berangkatValid($attribute, $params)
    {
        if((strtotime($this->tanggal_berangkat)>=strtotime($this->tanggal_kembali) && trim($this->tanggal_kembali)!='') || (strtotime($this->tanggal_berangkat)>strtotime($this->tanggal_mulai) && trim($this->tanggal_mulai)!=''))
            $this->addError($attribute, 'Tanggal berangkat tidak valid !');
    }

    public function kembaliValid($attribute, $params)
    {
        if((strtotime($this->tanggal_kembali)<=strtotime($this->tanggal_berangkat) && trim($this->tanggal_berangkat)!='') || (strtotime($this->tanggal_kembali)<strtotime($this->tanggal_selesai) && trim($this->tanggal_selesai)!=''))
            $this->addError($attribute, 'Tanggal kembali tidak valid !');
    }

    public function mulaiValid($attribute, $params)
    {
        if((strtotime($this->tanggal_mulai)<strtotime($this->tanggal_berangkat) && trim($this->tanggal_berangkat)!='') || (strtotime($this->tanggal_mulai)>=strtotime($this->tanggal_selesai) && trim($this->tanggal_selesai)!=''))
            $this->addError($attribute, 'Tanggal mulai kegiatan tidak valid !');
    }

    public function selesaiValid($attribute, $params)
    {
        if((strtotime($this->tanggal_selesai)>strtotime($this->tanggal_kembali) && trim($this->tanggal_kembali)!='') || (strtotime($this->tanggal_selesai)<=strtotime($this->tanggal_mulai) && trim($this->tanggal_mulai)!=''))
            $this->addError($attribute, 'Tanggal selesai kegiatan tidak valid !');
    }

    public function bekerjaValid($attribute, $params)
    {
        if(strtotime($this->kembali_bekerja)<strtotime($this->tanggal_kembali) && trim($this->tanggal_kembali)!='')
            $this->addError($attribute, 'Tanggal kembali bekerja tidak valid !');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLaporanSuratTugas()
    {
        return $this->hasMany(LaporanSuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusName()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJenisSurat()
    {
        return $this->hasOne(JenisSurat::className(), ['jenis_surat_id' => 'jenis_surat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerequest0()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'perequest']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenyetuju0()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'penyetuju']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuratTugasAssignees()
    {
        return $this->hasMany(SuratTugasAssignee::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    public function getAtasanSuratTugas()
    {
        return $this->hasMany(AtasanSuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    public function getAssignedAtasan($id){
        $arrayId= array();
        $model = AtasanSuratTugas::find()->select(['id_pegawai'])->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        foreach ($model as $key) {
            array_push($arrayId, $key['id_pegawai']);
        }

        return $arrayId;
    } 

    public function getSuratTugas($id){
        $arraySuratTugasId = array(); 
        $pegawai = Pegawai::find()->where(['user_id' => $id])->andWhere('deleted!=1')->one();
        $modelSuratTugas = SuratTugasAssignee::find()->where(['id_pegawai' => $pegawai->pegawai_id])->andWhere('deleted!=1')->all();
        foreach($modelSuratTugas as $data){
            array_push($arraySuratTugasId, $data['surat_tugas_id']);
        }

        return $arraySuratTugasId;
    }

    public function getSuratTugasBawahan($id){
        $modelSurat = AtasanSuratTugas::find()->where(['id_pegawai' => $id])->andWhere('deleted!=1')->all();
        $arrayIdSurat = array();
        foreach($modelSurat as $data){
            array_push($arrayIdSurat, $data['surat_tugas_id']);
        }
        $modelSuratTugas = SuratTugas::find()->where(['in', 'surat_tugas_id', $arrayIdSurat])->andWhere(['!=', 'jenis_surat_id', 3])->andWhere('deleted!=1')->all();
       
        return $modelSuratTugas;
    }

    public function getStatus($id){
        $model = Status::find()->where(['status_id' => $id])->andWhere('deleted!=1')->one();

        return $model->name;
    }

    public function getNama($id){
        $model = Pegawai::find()->where(['pegawai_id' => $id])->andWhere('deleted!=1')->one();
        
        return $model->nama;
    }

    public function getLaporan($id){
        $model = LaporanSuratTugas::find()->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->one();

        return $model;
    }

    public function getLaporanAll()
    {
        return $this->hasMany(LaporanSuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    public function getStatusLaporan($id){
        $laporan = SuratTugas::getLaporan($id);
        $status = Status::find()->where(['status_id' => $laporan['status_id']])->andWhere('deleted!=1')->one();
        
        return $status['name'];
    }

    public function getAssignee($id){
        $suratTugasAssignee = SuratTugasAssignee::find()->select('id_pegawai')->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $arrayAssignee = array();
        foreach($suratTugasAssignee as $data){
            array_push($arrayAssignee, $data['id_pegawai']);
        }
        $pegawais = Pegawai::find()->where(['in', 'pegawai_id', $arrayAssignee])->andWhere('deleted!=1')->orderBy('nama')->asArray()->all();

        return $pegawais;
    }

    public function getAtasan($id){
        $atasanSuratTugas = AtasanSuratTugas::find()->select('id_pegawai')->where(['surat_tugas_id' => $id])->andWhere('deleted!=1')->all();
        $arrayAtasan = array();
        foreach($atasanSuratTugas as $data){
            array_push($arrayAtasan, $data['id_pegawai']);
        }
        $pegawais = Pegawai::find()->where(['in', 'pegawai_id', $arrayAtasan])->andWhere('deleted!=1')->orderBy('nama')->asArray()->all();

        return $pegawais;
    }

    public function getTotLayanan(){
        $total = SuratTugas::find()->where('deleted!=1')->all();
        return count($total);
    }

    public function getTotRequest(){
        $requested = SuratTugas::find()->where(['status_id' => 1])->andWhere('deleted!=1')->all();
        return count($requested);
    }

    public function getTotDiterima(){
        $accepted = SuratTugas::find()->where(['status_id' => 6])->andWhere('deleted!=1')->all();
        return count($accepted);
    }

    public function getTotDitolak(){
        $rejected = SuratTugas::find()->where(['status_id' => 4])->andWhere('deleted!=1')->all();
        return count($rejected);
    }

    public function getTotDiterbitkan(){
        $published = SuratTugas::find()->where(['status_id' => 3])->andWhere('deleted!=1')->all();
        return count($published);
    }
}

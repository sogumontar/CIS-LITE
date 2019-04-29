<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\InstApiModel;

/**
 * This is the model class for table "rakx_program".
 *
 * @property integer $program_id
 * @property integer $struktur_jabatan_has_mata_anggaran_id
 * @property integer $kode_program
 * @property string $name
 * @property string $tujuan
 * @property string $sasaran
 * @property string $target
 * @property string $desc
 * @property integer $rencana_strategis_id
 * @property integer $volume
 * @property integer $satuan_id
 * @property string $harga_satuan
 * @property string $jumlah_sebelum_revisi
 * @property string $jumlah
 * @property integer $status_program_id
 * @property integer $diusulkan_oleh
 * @property string $tanggal_diusulkan
 * @property integer $dilaksanakan_oleh
 * @property integer $disetujui_oleh
 * @property string $tanggal_disetujui
 * @property integer $ditolak_oleh
 * @property string $tanggal_ditolak
 * @property integer $is_revisi
 * @property integer $direvisi_oleh
 * @property string $tanggal_direvisi
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxDetilProgram[] $rakxDetilPrograms
 * @property InstStrukturJabatan $dilaksanakanOleh
 * @property InstStrukturJabatan $direvisiOleh
 * @property InstStrukturJabatan $disetujuiOleh
 * @property InstStrukturJabatan $ditolakOleh
 * @property InstStrukturJabatan $diusulkanOleh
 * @property RakxRRencanaStrategis $rencanaStrategis
 * @property RakxRSatuan $satuan
 * @property RakxRStatusProgram $statusProgram
 * @property RakxStrukturJabatanHasMataAnggaran $strukturJabatanHasMataAnggaran
 * @property RakxProgramHasSumberDana[] $rakxProgramHasSumberDanas
 * @property RakxProgramHasWaktu[] $rakxProgramHasWaktus
 * @property RakxReviewProgram[] $rakxReviewPrograms
 */
class Program extends \yii\db\ActiveRecord
{
    // authorization
    public $auth_view = false;
    public $auth_edit = false;
    public $auth_del = false;
    public $auth_dana = false;
    public $auth_detil = false;
    public $auth_review = false; //
    public $auth_accept = false; //
    public $auth_reject = false; //
    public $auth_dana_detil_cud = false;    

    public $waktu;
    public static $valid = array(
        1 => "Waktu Pelaksanaan Program/Kegiatan belum diisi",
        2 => "Sumber Dana untuk Program/Kegiatan belum diisi atau Total Sumber Dana berbeda dengan Biaya",
        3 => "Total Biaya Breakdown berbeda dengan Total Biaya Program/Kegiatan",
    );
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
        return 'rakx_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['struktur_jabatan_has_mata_anggaran_id', 'name', 'volume', 'satuan_id', 'harga_satuan'], 'required'],
            [['struktur_jabatan_has_mata_anggaran_id', 'rencana_strategis_id', 'satuan_id', 'status_program_id', 'diusulkan_oleh', 'dilaksanakan_oleh', 'disetujui_oleh', 'ditolak_oleh', 'is_revisi', 'direvisi_oleh', 'deleted'], 'integer'],
            [['volume'], 'number'],
            [['name', 'tujuan', 'sasaran', 'target', 'desc'], 'string'],
            [['waktu', 'tanggal_diusulkan', 'tanggal_disetujui', 'tanggal_ditolak', 'tanggal_direvisi', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['harga_satuan', 'jumlah_sebelum_revisi', 'jumlah'], 'string', 'max' => 50],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dilaksanakan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatan::className(), 'targetAttribute' => ['dilaksanakan_oleh' => 'struktur_jabatan_id']],
            [['direvisi_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => Pejabat::className(), 'targetAttribute' => ['direvisi_oleh' => 'pejabat_id']],
            [['disetujui_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => Pejabat::className(), 'targetAttribute' => ['disetujui_oleh' => 'pejabat_id']],
            [['ditolak_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => Pejabat::className(), 'targetAttribute' => ['ditolak_oleh' => 'pejabat_id']],
            [['diusulkan_oleh'], 'exist', 'skipOnError' => true, 'targetClass' => Pejabat::className(), 'targetAttribute' => ['diusulkan_oleh' => 'pejabat_id']],
            [['rencana_strategis_id'], 'exist', 'skipOnError' => true, 'targetClass' => RencanaStrategis::className(), 'targetAttribute' => ['rencana_strategis_id' => 'rencana_strategis_id']],
            [['satuan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Satuan::className(), 'targetAttribute' => ['satuan_id' => 'satuan_id']],
            [['status_program_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusProgram::className(), 'targetAttribute' => ['status_program_id' => 'status_program_id']],
            [['struktur_jabatan_has_mata_anggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatanHasMataAnggaran::className(), 'targetAttribute' => ['struktur_jabatan_has_mata_anggaran_id' => 'struktur_jabatan_has_mata_anggaran_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_id' => 'Program ID',
            'struktur_jabatan_has_mata_anggaran_id' => 'Mata Anggaran',
            'kode_program' => 'Kode Program',
            'name' => 'Nama Program/Kegiatan',
            'tujuan' => 'Tujuan',
            'sasaran' => 'Sasaran',
            'target' => 'Target',
            'desc' => 'Deskripsi',
            'rencana_strategis_id' => 'Rencana Strategis',
            'volume' => 'Volume',
            'satuan_id' => 'Satuan',
            'harga_satuan' => 'Harga Satuan',
            'jumlah_sebelum_revisi' => 'Harga Total Sebelum Revisi',
            'jumlah' => 'Harga Total',
            'status_program_id' => 'Status Program',
            'diusulkan_oleh' => 'Pengusul',
            'tanggal_diusulkan' => 'Tanggal Diusulkan',
            'dilaksanakan_oleh' => 'Pelaksana',
            'disetujui_oleh' => 'Disetujui Oleh',
            'tanggal_disetujui' => 'Tanggal Disetujui',
            'ditolak_oleh' => 'Ditolak Oleh',
            'tanggal_ditolak' => 'Tanggal Ditolak',
            'is_revisi' => 'Direvisi ?',
            'direvisi_oleh' => 'Direvisi Oleh',
            'tanggal_direvisi' => 'Tanggal Direvisi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public function afterFind(){
        parent::afterFind();
        $inst_api = new InstApiModel();
        $pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
        
        $jabatanByPegawai = $inst_api->getJabatanByPegawaiId($pegawai->pegawai_id);
        $jabatanIdAnggaranByPegawai = array();
        foreach($jabatanByPegawai as $s){
            if($s->mata_anggaran==1){
                $jabatanIdAnggaranByPegawai[] = $s->struktur_jabatan_id;
            }
        }

        $pejabats = $inst_api->getPejabatByPegawaiId($pegawai->pegawai_id);
        $pejabatIdByPegawai = array();
        foreach($pejabats as $p){
            $pejabatIdByPegawai[] = $p->pejabat_id;
        }

        $this->auth_view = true;
        $this->setAuthEdit($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);
        $this->setAuthDelDanaDetil($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);
        /*$this->setAuthDel($jabatanIdAnggaranByPegawai);
        $this->setAuthDana($jabatanIdAnggaranByPegawai);
        $this->setAuthDetil($jabatanIdAnggaranByPegawai);*/
        $this->setAuthReview($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);
        $this->setAuthAccept($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);
        $this->setAuthReject($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);
        $this->setAuthDanaDetilCud($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai);

        return true;
    }

    public function setAuthEdit($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // tidak boleh yg sudah rejected
        // dan (dari mata anggaran yg sudah ditugaskan || yg diusulkan dia)
        if((in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) || in_array($this->diusulkan_oleh, $pejabatIdByPegawai)) && $this->status_program_id!=4){
            $this->auth_edit = true;
        }
    }

    public function setAuthDelDanaDetil($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // masih draft || draft for revise
        // dan (dari mata anggaran yg sudah ditugaskan || dia yg mengusulkan)
        if(($this->status_program_id==0 || $this->status_program_id==6) && (in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) || in_array($this->diusulkan_oleh, $pejabatIdByPegawai))){
            $this->auth_del = true;
            $this->auth_dana = true;
            $this->auth_detil = true;
        }
    }    

    /*public function setAuthDel($jabatanIdAnggaranByPegawai){
        // masih draft
        // dan dari mata anggaran yg sudah ditugaskan
        if($this->status_program_id==0 && in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai)){
            $this->auth_del = true;
        }
    }
    
    public function setAuthDana($jabatanIdAnggaranByPegawai){
        // masih draft
        // dan dari mata anggaran yg sudah ditugaskan
        if(in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id==0){
            $this->auth_dana = true;
        }
    }

    public function setAuthDetil($jabatanIdAnggaranByPegawai){
        // masih draft
        // dan dari mata anggaran yg sudah ditugaskan
        if(in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id==0){
            $this->auth_detil = true;
        }
    }*/

    public function setAuthReview($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // ga boleh program dari mata anggaran dia
        // dan ga boleh yg rejected
        // dan ga boleh program yg dia usulkan
        if(!in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id!=4 /*&& $this->status_program_id!=0*/ && !in_array($this->diusulkan_oleh, $pejabatIdByPegawai)){
                $this->auth_review = true;
        }
    }

    public function setAuthAccept($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // ga boleh reject, accept, draft, legitimate, draft for revise, in review for revise, revised
        // dan ga boleh dia yg usulkan
        // dan ga boleh program dr mata anggaran dia
        if((!in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id!=4 && $this->status_program_id<5 && $this->status_program_id!=3 && $this->status_program_id!=0 && !in_array($this->diusulkan_oleh, $pejabatIdByPegawai)) || (!in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id!=4 && $this->status_program_id<5 && $this->status_program_id!=3 && $this->status_program_id!=0)){
                $this->auth_accept = true;
        }
    }

    public function setAuthReject($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // ga boleh reject, legitimate, draft for revise, in review for revise, revised
        // dan ga boleh dia yg usulkan
        // dan ga boleh program dr mata anggaran dia
        if((!in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id!=4 && $this->status_program_id<5 /*&& $this->status_program_id!=0*/ && !in_array($this->diusulkan_oleh, $pejabatIdByPegawai)) || (in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) && $this->status_program_id!=4 && $this->status_program_id<5 /*&& $this->status_program_id!=0*/ && !in_array($this->diusulkan_oleh, $pejabatIdByPegawai))){
                $this->auth_reject = true;
        }
    }

    public function setAuthDanaDetilCud($jabatanIdAnggaranByPegawai, $pejabatIdByPegawai){
        // tidak boleh udah reject
        // dan (dari mata anggaran yg sudah ditugaskan
        // || (dia yg mengusulkan && status <= 3))
        if($this->status_program_id!=4 && (in_array($this->strukturJabatanHasMataAnggaran->struktur_jabatan_id, $jabatanIdAnggaranByPegawai) || (in_array($this->diusulkan_oleh, $pejabatIdByPegawai) && $this->status_program_id < 3))){
            $this->auth_dana_detil_cud = true;
        }
    }

    public static function toProgramValidity($id){
        $program = Program::findOne($id);
        if(is_null(self::isProgramValid($id))){
            if($program->status_program_id < 5){
                if(empty($program->reviewPrograms))
                    $program->status_program_id = 1;
                else
                    $program->status_program_id = 2;
            }else{
                $program->status_program_id = 8;
            }
            $program->save();
        }else{
            if($program->status_program_id < 5)
                $program->status_program_id = 0;
            else $program->status_program_id = 6;
            $program->save();
        }
    }

    public static function isProgramValid($id){
        $program = Program::findOne($id);
        $result = array();

        //1. waktu
        $result[] = self::$valid[1];
        foreach($program->programHasWaktus as $pw){
            if($pw->deleted!=1){
                $result = array();
                break;                
            }
        }

        //2. sumber dana
        $totalSumberDana = 0;
        foreach($program->programHasSumberDanas as $psd){
            if($psd->deleted!=1)
                $totalSumberDana += $psd->jumlah;    
        }
        if($totalSumberDana == 0 || $totalSumberDana!=$program->jumlah)
            $result[] = self::$valid[2];

        //3. breakdown
        $totalBreakdown = 0;
        foreach($program->detilPrograms as $pdp){
            if($pdp->deleted!=1){
                $totalBreakdown += $pdp->jumlah;    
            }
        }
        if(!empty($program->detilPrograms) && $totalBreakdown!=$program->jumlah){
            $result[] = self::$valid[3];       
        }

        if(empty($result))
                $result = null;
        return $result;
    }

    public static function getBawahanRecursive($atasan, &$jabatans){
        $inst_api = new InstApiModel();
        $atasan2 = array();
        foreach($atasan as $j1){
            $j2 = $inst_api->getBawahanByJabatan($j1);
            foreach($j2 as $j3){
                if(!in_array($j3->struktur_jabatan_id, $jabatans) && $j3->mata_anggaran==1){
                    $atasan2[] = $j3->struktur_jabatan_id;
                    $jabatans[] = $j3->struktur_jabatan_id;
                }
            }
        }
        if(!empty($atasan2)){
            self::getBawahanRecursive($atasan2, $jabatans);
        }
    }

    public static function getJumlah($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return "Rp".number_format($total,2,',','.');
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        StrukturJabatanHasMataAnggaran::updateSubtotal($this->struktur_jabatan_has_mata_anggaran_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetilPrograms()
    {
        return $this->hasMany(DetilProgram::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDilaksanakanOleh()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'dilaksanakan_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirevisiOleh()
    {
        return $this->hasOne(Pejabat::className(), ['pejabat_id' => 'direvisi_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDisetujuiOleh()
    {
        return $this->hasOne(Pejabat::className(), ['pejabat_id' => 'disetujui_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDitolakOleh()
    {
        return $this->hasOne(Pejabat::className(), ['pejabat_id' => 'ditolak_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiusulkanOleh()
    {
        return $this->hasOne(Pejabat::className(), ['pejabat_id' => 'diusulkan_oleh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRencanaStrategis()
    {
        return $this->hasOne(RencanaStrategis::className(), ['rencana_strategis_id' => 'rencana_strategis_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSatuan()
    {
        return $this->hasOne(Satuan::className(), ['satuan_id' => 'satuan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusProgram()
    {
        return $this->hasOne(StatusProgram::className(), ['status_program_id' => 'status_program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatanHasMataAnggaran()
    {
        return $this->hasOne(StrukturJabatanHasMataAnggaran::className(), ['struktur_jabatan_has_mata_anggaran_id' => 'struktur_jabatan_has_mata_anggaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramHasSumberDanas()
    {
        return $this->hasMany(ProgramHasSumberDana::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramHasWaktus()
    {
        return $this->hasMany(ProgramHasWaktu::className(), ['program_id' => 'program_id'])->orderBy(['bulan_id' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewPrograms()
    {
        return $this->hasMany(ReviewProgram::className(), ['program_id' => 'program_id']);
    }
}

<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "krkm_kuliah".
 *
 * @property integer $kuliah_id
 * @property integer $id_kur
 * @property string $kode_mk
 * @property string $nama_kul_ind
 * @property string $nama_kul_ing
 * @property string $short_name
 * @property string $kbk_id
 * @property string $course_group
 * @property integer $sks
 * @property integer $GBK_id
 * @property integer $sem
 * @property integer $urut_dlm_sem
 * @property integer $sifat
 * @property string $meetings
 * @property string $tipe
 * @property string $level
 * @property string $key_topics_ind
 * @property string $key_topics_ing
 * @property string $objektif_ind
 * @property string $objektif_ing
 * @property integer $lab_hour
 * @property integer $tutorial_hour
 * @property integer $course_hour
 * @property integer $course_hour_in_week
 * @property integer $lab_hour_in_week
 * @property integer $number_week
 * @property string $other_activity
 * @property integer $other_activity_hour
 * @property integer $knowledge
 * @property integer $skill
 * @property integer $attitude
 * @property integer $uts
 * @property integer $uas
 * @property integer $tugas
 * @property integer $quiz
 * @property string $whiteboard
 * @property string $lcd
 * @property string $courseware
 * @property string $lab
 * @property string $elearning
 * @property integer $status
 * @property string $prerequisites
 * @property string $course_description
 * @property string $course_objectives
 * @property string $learning_outcomes
 * @property string $course_format
 * @property string $grading_procedure
 * @property string $course_content
 * @property integer $ref_kbk_id
 * @property integer $course_group_id
 * @property integer $tahun_kurikulum_id
 * @property string $web_page
 * @property integer $ekstrakurikuler
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property AdakPengajaran[] $adakPengajarans
 * @property HrdxPengajar[] $hrdxPengajars
 * @property JdwlJadwal[] $jdwlJadwals
 * @property KrkmRTahunKurikulum $tahunKurikulum
 * @property KrkmCourseGroup $courseGroup
 * @property InstProdi $refKbk
 * @property MrefRGbk $gBK
 * @property KrkmKuliahProdi[] $krkmKuliahProdis
 * @property KrkmKurikulumProdi[] $krkmKurikulumProdis
 * @property KrkmPrerequisiteCourses[] $krkmPrerequisiteCourses
 * @property KrkmPrerequisiteCourses[] $krkmPrerequisiteCourses0
 * @property LabxPemesanan[] $labxPemesanans
 * @property LabxPeminjaman[] $labxPeminjamen
 * @property PrklBeritaAcaraDaftarHadir[] $prklBeritaAcaraDaftarHadirs
 * @property PrklBeritaAcaraKuliah[] $prklBeritaAcaraKuliahs
 * @property PrklCourseUnitMaterial[] $prklCourseUnitMaterials
 * @property PrklKrsDetail[] $prklKrsDetails
 * @property PrklKuesionerMateri[] $prklKuesionerMateris
 * @property PrklKuesionerPraktikum[] $prklKuesionerPraktikums
 * @property PrklKurikulumSyllabus[] $prklKurikulumSyllabi
 * @property RppxPengajaran[] $rppxPengajarans
 * @property SchdJadwalKuliah[] $schdJadwalKuliahs
 * @property TmbhSoftwareTools[] $tmbhSoftwareTools
 */
class Kuliah extends \yii\db\ActiveRecord
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
        return 'krkm_kuliah';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_kur', 'sks', 'GBK_id', 'sem', 'urut_dlm_sem', 'sifat', 'lab_hour', 'tutorial_hour', 'course_hour', 'course_hour_in_week', 'lab_hour_in_week', 'number_week', 'other_activity_hour', 'knowledge', 'skill', 'attitude', 'uts', 'uas', 'tugas', 'quiz', 'status', 'ref_kbk_id', 'course_group_id', 'tahun_kurikulum_id', 'ekstrakurikuler', 'deleted'], 'integer'],
            [['kode_mk'], 'required'],
            [['key_topics_ind', 'key_topics_ing', 'objektif_ind', 'objektif_ing', 'prerequisites', 'course_description', 'course_objectives', 'learning_outcomes', 'course_format', 'grading_procedure', 'course_content'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['kode_mk'], 'string', 'max' => 11],
            [['nama_kul_ind', 'nama_kul_ing'], 'string', 'max' => 255],
            [['short_name', 'kbk_id', 'course_group'], 'string', 'max' => 20],
            [['meetings'], 'string', 'max' => 100],
            [['tipe'], 'string', 'max' => 25],
            [['level'], 'string', 'max' => 15],
            [['other_activity'], 'string', 'max' => 50],
            [['whiteboard', 'lcd', 'courseware', 'lab', 'elearning'], 'string', 'max' => 1],
            [['web_page'], 'string', 'max' => 150],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['tahun_kurikulum_id'], 'exist', 'skipOnError' => true, 'targetClass' => KrkmRTahunKurikulum::className(), 'targetAttribute' => ['tahun_kurikulum_id' => 'tahun_kurikulum_id']],
            [['course_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => KrkmCourseGroup::className(), 'targetAttribute' => ['course_group_id' => 'course_group_id']],
            [['ref_kbk_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstProdi::className(), 'targetAttribute' => ['ref_kbk_id' => 'ref_kbk_id']],
            [['GBK_id'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRGbk::className(), 'targetAttribute' => ['GBK_id' => 'gbk_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kuliah_id' => 'Kuliah ID',
            'id_kur' => 'Id Kur',
            'kode_mk' => 'Kode Mk',
            'nama_kul_ind' => 'Nama Kul Ind',
            'nama_kul_ing' => 'Nama Kul Ing',
            'short_name' => 'Short Name',
            'kbk_id' => 'Kbk ID',
            'course_group' => 'Course Group',
            'sks' => 'Sks',
            'GBK_id' => 'Gbk ID',
            'sem' => 'Sem',
            'urut_dlm_sem' => 'Urut Dlm Sem',
            'sifat' => 'Sifat',
            'meetings' => 'Meetings',
            'tipe' => 'Tipe',
            'level' => 'Level',
            'key_topics_ind' => 'Key Topics Ind',
            'key_topics_ing' => 'Key Topics Ing',
            'objektif_ind' => 'Objektif Ind',
            'objektif_ing' => 'Objektif Ing',
            'lab_hour' => 'Lab Hour',
            'tutorial_hour' => 'Tutorial Hour',
            'course_hour' => 'Course Hour',
            'course_hour_in_week' => 'Course Hour In Week',
            'lab_hour_in_week' => 'Lab Hour In Week',
            'number_week' => 'Number Week',
            'other_activity' => 'Other Activity',
            'other_activity_hour' => 'Other Activity Hour',
            'knowledge' => 'Knowledge',
            'skill' => 'Skill',
            'attitude' => 'Attitude',
            'uts' => 'Uts',
            'uas' => 'Uas',
            'tugas' => 'Tugas',
            'quiz' => 'Quiz',
            'whiteboard' => 'Whiteboard',
            'lcd' => 'Lcd',
            'courseware' => 'Courseware',
            'lab' => 'Lab',
            'elearning' => 'Elearning',
            'status' => 'Status',
            'prerequisites' => 'Prerequisites',
            'course_description' => 'Course Description',
            'course_objectives' => 'Course Objectives',
            'learning_outcomes' => 'Learning Outcomes',
            'course_format' => 'Course Format',
            'grading_procedure' => 'Grading Procedure',
            'course_content' => 'Course Content',
            'ref_kbk_id' => 'Ref Kbk ID',
            'course_group_id' => 'Course Group ID',
            'tahun_kurikulum_id' => 'Tahun Kurikulum ID',
            'web_page' => 'Web Page',
            'ekstrakurikuler' => 'Ekstrakurikuler',
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
    public function getAdakPengajarans()
    {
        return $this->hasMany(AdakPengajaran::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxPengajars()
    {
        return $this->hasMany(HrdxPengajar::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwlJadwals()
    {
        return $this->hasMany(JdwlJadwal::className(), ['kuliah_id' => 'kuliah_id']);
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
    public function getCourseGroup()
    {
        return $this->hasOne(KrkmCourseGroup::className(), ['course_group_id' => 'course_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefKbk()
    {
        return $this->hasOne(InstProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGBK()
    {
        return $this->hasOne(MrefRGbk::className(), ['gbk_id' => 'GBK_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmKuliahProdis()
    {
        return $this->hasMany(KrkmKuliahProdi::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmKurikulumProdis()
    {
        return $this->hasMany(KrkmKurikulumProdi::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmPrerequisiteCourses()
    {
        return $this->hasMany(KrkmPrerequisiteCourses::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmPrerequisiteCourses0()
    {
        return $this->hasMany(KrkmPrerequisiteCourses::className(), ['kuliah_pre_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabxPemesanans()
    {
        return $this->hasMany(LabxPemesanan::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabxPeminjamen()
    {
        return $this->hasMany(LabxPeminjaman::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklBeritaAcaraDaftarHadirs()
    {
        return $this->hasMany(PrklBeritaAcaraDaftarHadir::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklBeritaAcaraKuliahs()
    {
        return $this->hasMany(PrklBeritaAcaraKuliah::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklCourseUnitMaterials()
    {
        return $this->hasMany(PrklCourseUnitMaterial::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKrsDetails()
    {
        return $this->hasMany(PrklKrsDetail::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKuesionerMateris()
    {
        return $this->hasMany(PrklKuesionerMateri::className(), ['kurikulum_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKuesionerPraktikums()
    {
        return $this->hasMany(PrklKuesionerPraktikum::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKurikulumSyllabi()
    {
        return $this->hasMany(PrklKurikulumSyllabus::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRppxPengajarans()
    {
        return $this->hasMany(RppxPengajaran::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchdJadwalKuliahs()
    {
        return $this->hasMany(SchdJadwalKuliah::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTmbhSoftwareTools()
    {
        return $this->hasMany(TmbhSoftwareTools::className(), ['kurikulum_id' => 'kuliah_id']);
    }
}

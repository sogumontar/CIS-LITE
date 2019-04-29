<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_dim_penilaian".
 *
 * @property integer $penilaian_id
 * @property string $desc
 * @property integer $ta
 * @property integer $sem_ta
 * @property integer $akumulasi_skor
 * @property integer $dim_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property DimPelanggaran[] $askmDimPelanggarans
 * @property Dim $dim
 * @property PoinKebaikan[] $askmPoinKebaikans
 */
class DimPenilaian extends \yii\db\ActiveRecord
{
    public $nilai_huruf;
    public $pembinaan;
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
        return 'askm_dim_penilaian';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['ta', 'sem_ta', 'akumulasi_skor', 'dim_id', 'deleted'], 'integer'],
            [['nilai_huruf', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penilaian_id' => 'Penilaian',
            'desc' => 'Keterangan',
            'ta' => 'Tahun Ajaran',
            'sem_ta' => 'Semester',
            'akumulasi_skor' => 'Akumulasi Skor',
            'dim_id' => 'Mahasiswa',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimPelanggarans()
    {
        return $this->hasMany(DimPelanggaran::className(), ['penilaian_id' => 'penilaian_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(Dim::className(), ['dim_id' => 'dim_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoinKebaikans()
    {
        return $this->hasMany(PoinKebaikan::className(), ['penilaian_id' => 'penilaian_id']);
    }

    public function afterFind(){
       parent::afterFind();

       $this->pembinaan = '';

       $this->nilai_huruf = '';
       if ($this->akumulasi_skor == 0) {
           $this->nilai_huruf = 'A';
           $this->pembinaan = '-';
       } elseif ($this->akumulasi_skor >= 1 && $this->akumulasi_skor <= 5) {
           $this->nilai_huruf = 'AB';
           $this->pembinaan = '-';
       } elseif ($this->akumulasi_skor >= 6 && $this->akumulasi_skor <= 9) {
           $this->nilai_huruf = 'B';
           $this->pembinaan = '-';
       } elseif ($this->akumulasi_skor >= 10 && $this->akumulasi_skor <= 14) {
           $this->nilai_huruf = 'BC';
           $this->pembinaan = 'Dipanggil/dilakukan pendampingan atau konseling';
       } elseif ($this->akumulasi_skor == 15) {
           $this->nilai_huruf = 'BC';
           $this->pembinaan = 'Menulis Surat Pernyataan Komitmen';
       } elseif ($this->akumulasi_skor >= 16 && $this->akumulasi_skor <= 20) {
           $this->nilai_huruf = 'C';
           $this->pembinaan = 'Menghubungi orangtua (via telepon)';
       } elseif ($this->akumulasi_skor >= 21 && $this->akumulasi_skor <= 25) {
           $this->nilai_huruf = 'D';
           $this->pembinaan = 'Menghubungi orangtua (via telepon)';
       } elseif ($this->akumulasi_skor > 25 && $this->akumulasi_skor < 30) {
           $this->pembinaan = 'Menghubungi orangtua (via telepon)';
           $this->nilai_huruf = 'E';
       } elseif ($this->akumulasi_skor > 30) {
           $this->pembinaan = 'Diberi Surat Peringatan oleh Kemahasiswaan';
           $this->nilai_huruf = 'E';
       } elseif ($this->akumulasi_skor == 100) {
           $this->pembinaan = 'Dikeluarkan';
           $this->nilai_huruf = 'E';
       }

       return true;
    }
}

<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_asrama".
 *
 * @property integer $asrama_id
 * @property string $name
 * @property string $lokasi
 * @property string $desc
 * @property integer $kapasitas
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Kamar[] $Kamars
 * @property Keasramaan[] $Keasramaans
 */
class Asrama extends \yii\db\ActiveRecord
{
    public $jumlah_mahasiswa;

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
        return 'askm_asrama';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'lokasi'], 'required'],
            [['desc'], 'string'],
            [['kapasitas', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['lokasi'], 'string', 'max' => 45],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asrama_id' => 'Asrama ID',
            'name' => 'Name',
            'lokasi' => 'Lokasi',
            'desc' => 'Desc',
            'kapasitas' => 'Kapasitas',
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
    public function getKamars()
    {
        return $this->hasMany(Kamar::className(), ['asrama_id' => 'asrama_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeasramaans()
    {
        return $this->hasMany(Keasramaan::className(), ['asrama_id' => 'asrama_id']);
    }

    public function afterFind(){
       parent::afterFind();

       $komponen = Kamar::find()->where('deleted != 1')->andWhere(['asrama_id' => $this->asrama_id])->all();

       $this->jumlah_mahasiswa = 0;
       foreach($komponen as $k){
            foreach ($k->dimKamar as $b) {
                $this->jumlah_mahasiswa +=1;
            }
       }

       return true;
    }

}

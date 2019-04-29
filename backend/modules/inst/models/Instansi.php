<?php

namespace backend\modules\inst\models;

use yii\db\ActiveRecord;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "inst_instansi".
 *
 * @property integer $instansi_id
 * @property string $name
 * @property string $inisial
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class Instansi extends ActiveRecord
{
    //behaviour to add created_at and updatet_at field with current timestamp
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
        return 'inst_instansi';
    }

    public static function primaryKey()
    {
        return ['instansi_id'];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'inisial', 'desc'], 'required'],
            [['deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['inisial'], 'string', 'max' => 45],
            [['desc'], 'string'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['name'], 'isUniqueName'],
            [['inisial'], 'isUniqueInisial'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'instansi_id' => 'Instansi ID',
            'name' => 'Nama Instansi',
            'inisial' => 'Inisial',
            'desc' => 'Deskripsi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function isUniqueName($attribute, $params){
        if(is_null($this->instansi_id)){
            if($this->findOne(['name' => $this->name, 'deleted' => 0])){
                $this->addError($attribute, 'Nama Instansi is already used!');
            }
        }
        else{
            if(Instansi::find()->where('instansi_id != '.$this->instansi_id)->andWhere(['name' => $this->name])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Nama Instansi is already used!');
            }   
        }

        /*if($this->findOne(['name' => $this->name, 'deleted' => 0])){
            $this->addError($attribute, 'Nama Instansi is already used!');
        }*/
    }

    public function isUniqueInisial($attribute, $params){
        if(is_null($this->instansi_id)){
            if($this->findOne(['inisial' => $this->inisial, 'deleted' => 0])){
                $this->addError($attribute, 'Inisial is already used!');
            }
        }
        else{
            if(Instansi::find()->where('instansi_id != '.$this->instansi_id)->andWhere(['inisial' => $this->inisial])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Inisial is already used!');
            }   
        }

        /*if($this->findOne(['inisial' => $this->inisial, 'deleted' => 0])){
            $this->addError($attribute, 'Inisial is already used!');
        }*/
    }

    public function getStrukturJabatans()
    {
        return $this->hasMany(StrukturJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    public function getUnits()
    {
        return $this->hasMany(Unit::className(), ['unit_id' => 'unit_id']);
    }

}

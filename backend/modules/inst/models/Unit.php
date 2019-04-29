<?php

namespace backend\modules\inst\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "inst_unit".
 *
 * @property integer $unit_id
 * @property string $name
 * @property string $inisial
 * @property string $desc
 * @property integer $kepala
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property resource $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property InstStrukturJabatan[] $instStrukturJabatans
 * @property InstStrukturJabatan $kepala0
 */
class Unit extends \yii\db\ActiveRecord
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
        return 'inst_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'instansi_id', 'inisial', 'desc', 'kepala'], 'required'],
            [['desc'], 'string'],
            [['kepala', 'deleted'], 'integer'],
            [['instansi_id', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['inisial'], 'string', 'max' => 45],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['kepala'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatan::className(), 'targetAttribute' => ['kepala' => 'struktur_jabatan_id']],
            [['name'], 'isUniqueName'],
            [['inisial'], 'isUniqueInisial'],
            //[['instansi_id'], 'isThereMember'],
            [['kepala'], 'isKepalaOther'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_id' => 'Unit ID',
            'instansi_id' => 'Instansi ID',
            'name' => 'Nama Unit',
            'inisial' => 'Inisial',
            'desc' => 'Desc',
            'kepala' => 'Pejabat',
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
        if(is_null($this->unit_id)){
            if($this->findOne(['instansi_id' => $this->instansi_id, 'name' => $this->name, 'deleted' => 0])){
                $this->addError($attribute, 'Name is already used!');
            }
        }
        else{
            if(Unit::find()->where('unit_id != '.$this->unit_id)->andWhere(['instansi_id' => $this->instansi_id,])->andWhere(['name' => $this->name])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Name is already used!');
            }   
        }
    }

    public function isUniqueInisial($attribute, $params){
        if(is_null($this->unit_id)){
            if($this->findOne(['instansi_id' => $this->instansi_id, 'inisial' => $this->inisial, 'deleted' => 0])){
                $this->addError($attribute, 'Inisial is already used!');
            }
        }
        else{
            if(Unit::find()->where('unit_id != '.$this->unit_id)->andWhere(['instansi_id' => $this->instansi_id])->andWhere(['inisial' => $this->inisial])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Inisial is already used!');
            }   
        }
    }

    /*public function isThereMember($attribute, $params){
        if(!is_null($this->unit_id)){
            if(StrukturJabatan::find()->where(['unit_id' => $this->unit_id])->andWhere('deleted != 1')->count()>0)
                $this->addError($attribute, 'Unit masih memiliki Member. Tidak bisa dipindah ke Instansi lain!');
        }
    }*/

    public function isKepalaOther($attribute, $params){
        if(!is_null($this->unit_id)){
            if(Unit::find()->where(['kepala' => $this->kepala])->andWhere('unit_id != '.$this->unit_id)->andWhere('deleted != 1')->count()>0)
                $this->addError($attribute, 'Kepala telah mengepalai Unit lain!');
        }
        else{
            if(Unit::find()->where(['kepala' => $this->kepala])->andWhere('deleted != 1')->count()>0)
                $this->addError($attribute, 'Kepala telah mengepalai Unit lain!');
        }
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatans()
    {
        return $this->hasMany(StrukturJabatan::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKepala0()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'kepala'])->from(['kepala0' => 'inst_struktur_jabatan']);
    }

    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['instansi_id' => 'instansi_id']);
    }
}

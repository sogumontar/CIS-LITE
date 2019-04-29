<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_kategori_cuti_nontahunan".
 *
 * @property integer $kategori_cuti_nontahunan_id
 * @property string $name
 * @property integer $lama_pelaksanaan
 * @property integer $satuan
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property PermohonanCutiNontahunan[] $permohonanCutiNontahunans
 */
class KategoriCutiNontahunan extends \yii\db\ActiveRecord
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
        return 'cist_kategori_cuti_nontahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'satuan', 'lama_pelaksanaan'], 'required'],
            [['lama_pelaksanaan', 'satuan', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kategori_cuti_nontahunan_id' => 'Kategori Cuti Nontahunan ID',
            'name' => 'Name',
            'lama_pelaksanaan' => 'Lama Pelaksanaan',
            'satuan' => 'Satuan',
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
    public function getCistPermohonanCutiNontahunans()
    {
        return $this->hasMany(PermohonanCutiNontahunan::className(), ['kategori_id' => 'kategori_cuti_nontahunan_id']);
    }
}

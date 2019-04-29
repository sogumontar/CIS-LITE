<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "rakx_r_tahun_anggaran".
 *
 * @property integer $tahun_anggaran_id
 * @property string $name
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxStrukturJabatanHasMataAnggaran[] $rakxStrukturJabatanHasMataAnggarans
 */
class TahunAnggaran extends \yii\db\ActiveRecord
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
        return 'rakx_r_tahun_anggaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tahun'], 'required'],
            [['tahun'], 'unique'],
            [['tahun_anggaran_id', 'tahun', 'deleted'], 'integer'],
            [['tahun', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['desc'], 'string'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tahun_anggaran_id' => 'Tahun Anggaran ID',
            'tahun' => 'Tahun',
            'desc' => 'Desc',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public static function getIdYearBefore($tahun_anggaran_id){
        $tahun_anggaran = TahunAnggaran::findOne($tahun_anggaran_id);
        $tahun_now = $tahun_anggaran->tahun;
        $tahun_last = $tahun_now-1;
        $tahun_anggaran = TahunAnggaran::find()->where('deleted!=1')->andWhere(['tahun' => $tahun_last])->orderBy(['created_at' => SORT_DESC])->one();
        return $tahun_anggaran===null?0:$tahun_anggaran->tahun_anggaran_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatanHasMataAnggarans()
    {
        return $this->hasMany(StrukturJabatanHasMataAnggaran::className(), ['tahun_anggaran_id' => 'tahun_anggaran_id']);
    }
}

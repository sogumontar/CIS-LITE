<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\inst\models\StrukturJabatan;

/**
 * This is the model class for table "rakx_struktur_jabatan_has_mata_anggaran".
 *
 * @property integer $struktur_jabatan_has_mata_anggaran_id
 * @property integer $struktur_jabatan_id
 * @property integer $mata_anggaran_id
 * @property integer $tahun_anggaran_id
 * @property string $subtotal
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxProgram[] $rakxPrograms
 * @property RakxMataAnggaran $mataAnggaran
 * @property InstStrukturJabatan $strukturJabatan
 * @property RakxRTahunAnggaran $tahunAnggaran
 */
class StrukturJabatanHasMataAnggaran extends \yii\db\ActiveRecord
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
        return 'rakx_struktur_jabatan_has_mata_anggaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['struktur_jabatan_id', 'mata_anggaran_id', 'tahun_anggaran_id'], 'required'],
            [['struktur_jabatan_id', 'mata_anggaran_id', 'tahun_anggaran_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['subtotal'], 'string', 'max' => 50],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['mata_anggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => MataAnggaran::className(), 'targetAttribute' => ['mata_anggaran_id' => 'mata_anggaran_id']],
            [['struktur_jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatan::className(), 'targetAttribute' => ['struktur_jabatan_id' => 'struktur_jabatan_id']],
            [['tahun_anggaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => TahunAnggaran::className(), 'targetAttribute' => ['tahun_anggaran_id' => 'tahun_anggaran_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'struktur_jabatan_has_mata_anggaran_id' => 'Struktur Jabatan Has Mata Anggaran ID',
            'struktur_jabatan_id' => 'Struktur Jabatan',
            'mata_anggaran_id' => 'Mata Anggaran',
            'tahun_anggaran_id' => 'Tahun Anggaran',
            'subtotal' => 'Subtotal',
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

    public static function updateSubtotal($id){
        $model = StrukturJabatanHasMataAnggaran::findOne($id);
        $subtotal = 0;
        foreach($model->programs as $p){
            if($p->deleted!=1 && ($p->status_program_id==3 || $p->status_program_id>=5))
                $subtotal += $p->jumlah;
        }
        $model->subtotal = $subtotal;
        return $model->save();
    }

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return "Rp".number_format($total,2,',','.');
    }

    public static function isExist($tahun_anggaran_id, $struktur_jabatan_id, $mata_anggaran_id)
    {
        $data = StrukturJabatanHasMataAnggaran::find()->where(['tahun_anggaran_id' => $tahun_anggaran_id, 'struktur_jabatan_id' => $struktur_jabatan_id, 'mata_anggaran_id' => $mata_anggaran_id])->andWhere('deleted!=1')->all();
        return empty($data)?false:true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrograms()
    {
        return $this->hasMany(Program::className(), ['struktur_jabatan_has_mata_anggaran_id' => 'struktur_jabatan_has_mata_anggaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMataAnggaran()
    {
        return $this->hasOne(MataAnggaran::className(), ['mata_anggaran_id' => 'mata_anggaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatan()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTahunAnggaran()
    {
        return $this->hasOne(TahunAnggaran::className(), ['tahun_anggaran_id' => 'tahun_anggaran_id']);
    }
}

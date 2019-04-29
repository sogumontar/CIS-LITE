<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "rakx_detil_program".
 *
 * @property integer $detil_program_id
 * @property integer $program_id
 * @property string $name
 * @property string $desc
 * @property string $jumlah
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxProgram $program
 */
class DetilProgram extends \yii\db\ActiveRecord
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
        return 'rakx_detil_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'name', 'jumlah'], 'required'],
            [['program_id', 'deleted'], 'integer'],
            [['name', 'desc'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['jumlah'], 'string', 'max' => 50],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'program_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'detil_program_id' => 'Detil Program ID',
            'program_id' => 'Program ID',
            'name' => 'Nama Sub Program',
            'desc' => 'Deskripsi',
            'jumlah' => 'Jumlah',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public static function getJumlah($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return "Rp".number_format($total,2,',','.');
    }

    public static function getJumlahNumerik($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        return $total;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['program_id' => 'program_id']);
    }
}

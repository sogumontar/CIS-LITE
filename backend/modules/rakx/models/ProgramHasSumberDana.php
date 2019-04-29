<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "rakx_program_has_sumber_dana".
 *
 * @property integer $program_has_sumber_dana_id
 * @property integer $program_id
 * @property integer $sumber_dana_id
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxProgram $program
 * @property RakxRSumberDana $sumberDana
 */
class ProgramHasSumberDana extends \yii\db\ActiveRecord
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
        return 'rakx_program_has_sumber_dana';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'sumber_dana_id'], 'required'],
            [['program_id', 'sumber_dana_id', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['jumlah'], 'string', 'max' => 50],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'program_id']],
            [['sumber_dana_id'], 'exist', 'skipOnError' => true, 'targetClass' => SumberDana::className(), 'targetAttribute' => ['sumber_dana_id' => 'sumber_dana_id']],
            ['jumlah', 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_has_sumber_dana_id' => 'Program Has Sumber Dana ID',
            'program_id' => 'Program ID',
            'sumber_dana_id' => 'Sumber Dana',
            'jumlah' => 'Jumlah',
            'desc' => 'Deskripsi',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSumberDana()
    {
        return $this->hasOne(SumberDana::className(), ['sumber_dana_id' => 'sumber_dana_id']);
    }
}

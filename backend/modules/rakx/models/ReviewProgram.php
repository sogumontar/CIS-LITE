<?php

namespace backend\modules\rakx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\inst\models\Pejabat;

/**
 * This is the model class for table "rakx_review_program".
 *
 * @property integer $review_program_id
 * @property integer $program_id
 * @property integer $pejabat_id
 * @property string $review
 * @property string $tanggal_review
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property RakxProgram $program
 * @property InstPejabat $strukturJabatan
 */
class ReviewProgram extends \yii\db\ActiveRecord
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
        return 'rakx_review_program';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'pejabat_id', 'review', 'tanggal_review'], 'required'],
            [['program_id', 'pejabat_id', 'deleted'], 'integer'],
            [['review'], 'string'],
            [['tanggal_review', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::className(), 'targetAttribute' => ['program_id' => 'program_id']],
            [['pejabat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pejabat::className(), 'targetAttribute' => ['pejabat_id' => 'pejabat_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_program_id' => 'Review Program ID',
            'program_id' => 'Program ID',
            'pejabat_id' => 'Pejabat',
            'review' => 'Review',
            'tanggal_review' => 'Tanggal Review',
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
    public function getProgram()
    {
        return $this->hasOne(Program::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPejabat()
    {
        return $this->hasOne(Pejabat::className(), ['pejabat_id' => 'pejabat_id']);
    }
}

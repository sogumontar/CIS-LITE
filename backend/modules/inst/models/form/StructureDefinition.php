<?php

namespace backend\modules\inst\models\form;

use Yii;
use yii\base\Model;

class StructureDefinition extends Model
{
    public $file;

    public function rules()
    {
        return [
            ['file', 'required'],
            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'json']
        ];
    }

    public function attributeLabels()
    {
        return [
            'file' => 'Definition File',
        ];
    }
}
<?php

namespace backend\modules\inst\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
/**
 * This is the model class for table "inst_struktur_jabatan".
 *
 * @property integer $struktur_jabatan_id
 * @property integer $instansi_id
 * @property string $jabatan
 * @property integer $parent
 * @property string $inisial
 * @property integer $is_multi_tenant
 * @property integer $is_unit
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property InstPejabat[] $instPejabats
 * @property InstInstansi $instansi
 * @property StrukturJabatan $parent0
 * @property StrukturJabatan[] $strukturJabatans
 * @property PdrkAnggaran[] $pdrkAnggarans
 */
class StrukturJabatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
    
    public static function tableName()
    {
        return 'inst_struktur_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instansi_id', 'parent', 'is_multi_tenant', 'mata_anggaran', 'laporan', 'unit_id', 'deleted'], 'integer'],
            [['instansi_id', 'jabatan', 'inisial', 'is_multi_tenant', 'mata_anggaran', 'laporan'], 'required'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['jabatan'], 'string', 'max' => 255],
            [['inisial'], 'string', 'max' => 45],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['jabatan'], 'isUniqueJabatan'],
            [['inisial'], 'isUniqueInisial'],
            [['is_multi_tenant'], 'isLowerTenant'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'instansi_id' => 'Instansi ID',
            'jabatan' => 'Nama Jabatan',
            'parent' => 'Atasan',
            'inisial' => 'Inisial',
            'is_multi_tenant' => 'Status Tenant',
            'unit_id' => 'Unit ID',
            'mata_anggaran' => 'Mata Anggaran',
            'laporan' => 'Laporan',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public static function _buildStrukturs($strukturs, $instansi_id=null)
    {
        $index = array();
        $struktur_key = array();
        $strukturs_res = array();
        $tree_res = array();
        foreach($strukturs as $s)
        {
            $struktur_key[$s->struktur_jabatan_id] = $s;
        }

        $i = 0;
        foreach($strukturs as $s)
        {
            if(!in_array($s->struktur_jabatan_id, $index)){
                self::_recursiveStrukturJabatan($struktur_key, $struktur_key[$s->struktur_jabatan_id], $i, $index, $strukturs_res, $instansi_id, $tree_res);
            }
        }
        return $tree_res;
    }
        
    public static function _recursiveStrukturJabatan($struktur_key, $struktur_jabatan, $i, &$index, &$strukturs_res, $instansi_id, &$tree_res)
    {
        $strukturs_res[] = $struktur_jabatan;
        $index[] = $struktur_jabatan->struktur_jabatan_id;

        $tree_res[] = $struktur_jabatan;
        if(isset($struktur_jabatan->strukturJabatans)){
            foreach($struktur_jabatan->strukturJabatans as $s){
              if($s->deleted!=1 && $s->instansi_id==$struktur_jabatan->instansi_id && isset($struktur_key[$s->struktur_jabatan_id])){
                self::_recursiveStrukturJabatan($struktur_key, $struktur_key[$s->struktur_jabatan_id], $i+1, $index, $strukturs_res, $instansi_id, $tree_res);
              }
            }
        }
        $tree_res[] = ['parent' => $struktur_jabatan->struktur_jabatan_id];
    }

    public function isUniqueJabatan($attribute, $params){
        if(is_null($this->struktur_jabatan_id)){
            if($this->findOne(['jabatan' => $this->jabatan, 'instansi_id' => $this->instansi_id, 'deleted' => 0]))
                $this->addError($attribute, 'Jabatan is already used!');
        }
        else{
            if(StrukturJabatan::find()->where('struktur_jabatan_id != '.$this->struktur_jabatan_id)->andWhere(['jabatan' => $this->jabatan, 'instansi_id' => $this->instansi_id])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Jabatan is already used!');
            }   
        }
    }

    public function isUniqueInisial($attribute, $params){
        if(is_null($this->struktur_jabatan_id)){
            if($this->findOne(['inisial' => $this->inisial, 'instansi_id' => $this->instansi_id, 'deleted' => 0])){
                $this->addError($attribute, 'Inisial is already used!');
            }
        }
        else{
            if(StrukturJabatan::find()->where('struktur_jabatan_id != '.$this->struktur_jabatan_id)->andWhere(['inisial' => $this->inisial, 'instansi_id' => $this->instansi_id])->andWhere('deleted != 1')->count()>0){
                $this->addError($attribute, 'Inisial is already used!');
            }   
        }
    }

    public function isLowerTenant($attribute, $params){
        //$pejabats = Pejabat::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere('deleted != 1')->count();
        $pejabats = Pejabat::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere(['status_aktif' => 1])->andWhere('deleted != 1')->count();
        if($pejabats > 1 && $this->is_multi_tenant == 0)
            $this->addError($attribute, 'Pejabat saat ini lebih dari 1. Tidak bisa mengubah Status Tenant menjadi Single!');

        $pejabats = Pejabat::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere(['status_aktif' => 0])->andWhere(['>', 'awal_masa_kerja', date('Y-m-d')])->andWhere(['>', 'akhir_masa_kerja', date('Y-m-d')])->andWhere('deleted != 1')->count();
        if($pejabats > 1 && $this->is_multi_tenant == 0)
            $this->addError($attribute, 'Pejabat yang akan menjabat lebih dari 1. Tidak bisa mengubah Status Tenant menjadi Single!');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPejabats()
    {
        return $this->hasMany(Pejabat::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(Instansi::className(), ['instansi_id' => 'instansi_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'parent'])->from(['parent' => 'inst_struktur_jabatan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatans()
    {
        return $this->hasMany(StrukturJabatan::className(), ['parent' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdrkAnggarans()
    {
        return $this->hasMany(PdrkAnggaran::className(), ['unit_tujuan' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getJabatanHasAnggaran()
    {
        return StrukturJabatan::find()->where(['mata_anggaran' => 1])->andWhere('deleted != 1')->orderBy(['jabatan' => SORT_ASC])->all();
    }
}

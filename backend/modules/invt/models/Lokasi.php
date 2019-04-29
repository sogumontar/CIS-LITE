<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\invt\models\PengeluaranBarang;

/**
 * This is the model class for table "invt_r_lokasi".
 *
 * @property integer $lokasi_id
 * @property integer $parent_id
 * @property string $nama_lokasi
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class Lokasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $jumlahBarang;
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
        return 'invt_r_lokasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['desc','string'],
            [['parent_id', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama_lokasi'], 'string', 'max' => 50],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lokasi_id' => 'Lokasi ID',
            'parent_id' => 'Parent ID',
            'nama_lokasi' => 'Nama Lokasi',
            'desc'=>'Keterangan Lokasi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function getChilds()
    {
        $query = Lokasi::find()
                        ->where('parent_id = :id', [':id'=>$this->lokasi_id])
                        ->andWhere('deleted = :de',[':de'=>0])
                        ->all();
        return $query;
    }

    public function getDetail()
    {
        return $this->hasOne(Lokasi::className(),['lokasi_id'=>'parent_id']);
    }

    public static function getNamaLokasi($lokasi_id)
    {
        $lokasi = Lokasi::findOne($lokasi_id);
        if($lokasi!=null){
            return $lokasi->nama_lokasi;
        }
    }

    public function getDistribusiBarang()
    {
        return $this->hasMany(PengeluaranBarang::className(), ['lokasi_id'=>'lokasi_id']);
    }

    public function getDetailDistribusiByLokasi()
    {
        return $this->hasMany(PengeluaranBarang::className(),['lokasi_id'=>'lokasi_id'])
                                                                ->select(['*','jumlahBarang'=>'SUM(jumlah)'])
                                                                ->groupBy(['barang_id'])
                                                                ->where(["deleted"=>0]);
    }

    //get jumlah barang by lokasi
    public function getJumlahBarangByLokasi($lokasi_id)
    {
        $query = Lokasi::find()
                        ->joinWith('distribusiBarang')
                        ->where([PengeluaranBarang::tableName().".deleted"=>0])
                        ->andWhere([Lokasi::tableName().".deleted"=>0])
                        ->andWhere([PengeluaranBarang::tableName().".lokasi_id"=>$lokasi_id])
                        ->sum(PengeluaranBarang::tableName().".jumlah");
        return $query;
    }

    /*
        *get jumlah barang secara keseluruhan (tree) by lokasi
    */
    public function getJumlahBarang($lokasi_id)
    {
        $jumlahBarang=0;
        $_fromChild =0;
        $jumlahBarang = PengeluaranBarang::find()
                                        ->where(['deleted'=>0])
                                        ->andWhere(['lokasi_id'=>$lokasi_id])
                                        ->sum('jumlah');
        if($jumlahBarang==null||$jumlahBarang==0){
            $jumlahBarang=0;
        }
        //check child
        if($this->getChilds()!=null)
        {
            $_fromChild = $this->getJumlahBarangBychild($this->getChilds(),0);
            $jumlahBarang = $jumlahBarang+$_fromChild;
        }
        return $jumlahBarang;
    }
    //cek child
    private function getJumlahBarangBychild($childs,$total)
    {
        foreach ($childs as $key => $value) {
           $jumlahBarang = PengeluaranBarang::find()
                                            ->where(['deleted'=>0])
                                            ->andWhere(['lokasi_id'=>$value->lokasi_id])
                                            ->sum('jumlah');
            if($jumlahBarang==null||$jumlahBarang==0)
            {
                $jumlahBarang=0;
            }
            $total = $total+$jumlahBarang;
            //check child
            if($value->getChilds()!=null){
              $total = $this->getJumlahBarangBychild($value->getChilds(),$total);
            }
        }
        return $total;
    }
    /*
        *end of get jumlah barang (tree)
    */
}

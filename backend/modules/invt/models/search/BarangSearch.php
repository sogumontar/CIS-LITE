<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\Barang;

/**
 * BarangSearch represents the model behind the search form about `backend\modules\invt\models\Barang`.
 */
class BarangSearch extends Barang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barang_id', 'jenis_barang_id', 'kategori_id', 'jumlah','satuan_id', 'kapasitas', 'unit_id', 'deleted', 'brand_id', 'vendor_id'], 'integer'],
            [[ 'nama_barang', 'supplier', 'tanggal_masuk', 'serial_number', 'desc', 'nama_file', 'kode_file', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
            [['total_harga','harga_per_barang'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$_query,$page_size)
    {
        $query = Barang::find();
        if($_query!=null){
            $query = $_query;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(!is_null($params)){
            $this->load($params);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            $this->tableName().'.barang_id' => $this->barang_id,
            $this->tableName().'.jenis_barang_id' => $this->jenis_barang_id,
            $this->tableName().'.kategori_id' => $this->kategori_id,
            $this->tableName().'.jumlah' => $this->jumlah,
            $this->tableName().'.satuan_id' => $this->satuan_id,
            $this->tableName().'.unit_id'=>$this->unit_id,
            $this->tableName().'.kapasitas' => $this->kapasitas,
        ]);

        $query->andFilterWhere(['like', $this->tableName().'.nama_barang', $this->nama_barang]);
        $query->andWhere([$this->tableName().'.deleted'=>0]);
        $dataProvider->pagination->pageSize=$page_size;
        return $dataProvider;
    }
}

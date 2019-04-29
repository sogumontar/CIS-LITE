<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\PemindahanBarang;

/**
 * PemindahanBarangSearch represents the model behind the search form about `backend\modules\invt\models\PemindahanBarang`.
 */
class PemindahanBarangSearch extends PemindahanBarang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pemindahan_barang_id', 'pengeluaran_barang_id', 'lokasi_akhir_id', 'lokasi_awal_id', 'deleted'], 'integer'],
            [['kode_inventori', 'kode_inventori_awal','tanggal_pindah','status_transaksi', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
    public function search($params,$_query)
    {
        $query = PemindahanBarang::find();

        if($_query !=null){
            $query = $_query;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            $this->tableName().'.lokasi_akhir_id' => $this->lokasi_akhir_id,
            $this->tableName().'.lokasi_awal_id'=>$this->lokasi_awal_id,
        ]);

        $query->andFilterWhere(['like', $this->tableName().'.kode_inventori', $this->kode_inventori])
            ->andFilterWhere(['like', $this->tableName().'.kode_inventori_awal', $this->kode_inventori_awal])
            ->andFilterWhere(['like',$this->tableName().'.tanggal_pindah',$this->tanggal_pindah]);
        $dataProvider->pagination->pageSize = 20;
        return $dataProvider;
    }
}

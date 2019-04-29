<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\PengeluaranBarang;
use backend\modules\invt\models\Barang;

/**
 * PengeluaranBarangSearch represents the model behind the search form about `backend\modules\invt\models\PengeluaranBarang`.
 */
class PengeluaranBarangSearch extends PengeluaranBarang
{
    /**
     * @inheritdoc
     */

    public function rules() 
    { 
        return [ 
            [['pengeluaran_barang_id', 'barang_id', 'unit_id', 'lokasi_id', 'status_akhir', 'deleted','jumlah','is_has_pic'], 'integer'],
            [['kode_inventori', 'tgl_keluar', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
    public function search($params, $_query)
    {
        $query = PengeluaranBarang::find();
        if($_query!=null){
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
            'lokasi_id' => $this->lokasi_id,
        ]);

        $query->andFilterWhere(['like', 'kode_inventori', $this->kode_inventori])
            ->andFilterWhere(['like','tgl_keluar', $this->tgl_keluar]);
        $query->andWhere(['deleted'=>0]);
        $dataProvider->pagination->pageSize=20;
        return $dataProvider;
    }
}

<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\KeteranganPengeluaran;

/**
 * KeteranganPengeluaranSearch represents the model behind the search form about `backend\modules\invt\models\KeteranganPengeluaran`.
 */
class KeteranganPengeluaranSearch extends KeteranganPengeluaran
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keterangan_pengeluaran_id', 'unit_id', 'total_barang_keluar', 'oleh', 'lokasi_distribusi', 'deleted'], 'integer'],
            [['tgl_keluar', 'deleted_by', 'deleted_at', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'safe'],
            [['keterangan'], 'string'],
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
        $query = KeteranganPengeluaran::find();
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
            'lokasi_distribusi' => $this->lokasi_distribusi,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like','tgl_keluar', $this->tgl_keluar]);
        $query->andWhere(['deleted'=>0]);
        $dataProvider->pagination->pageSize=20;
        return $dataProvider;
    }
}

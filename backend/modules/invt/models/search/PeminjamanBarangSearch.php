<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\PeminjamanBarang;

/**
 * PeminjamanBarangSearch represents the model behind the search form about `backend\modules\invt\models\PeminjamanBarang`.
 */
class PeminjamanBarangSearch extends PeminjamanBarang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peminjaman_barang_id', 'oleh', 'status_approval', 'approved_by', 'status_kembali', 'unit_id', 'deleted'], 'integer'],
            [['tgl_pinjam', 'tgl_kembali', 'deskripsi', 'tgl_realisasi_kembali', 'deleted_by', 'deleted_at', 'updated_by', 'updated_at', 'created_by', 'created_at'], 'safe'],
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
    public function search($params,$_query,$_pagesize)
    {
        if($_query==null){
          $query = PeminjamanBarang::find();
        }else{
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
            'peminjaman_barang_id' => $this->peminjaman_barang_id,
            'tgl_pinjam' => $this->tgl_pinjam,
            'tgl_kembali' => $this->tgl_kembali,
            'oleh' => $this->oleh,
            'status_approval' => $this->status_approval,
            'approved_by' => $this->approved_by,
            'status_kembali' => $this->status_kembali,
            'tgl_realisasi_kembali' => $this->tgl_realisasi_kembali,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);
        $dataProvider->pagination->pagesize = $_pagesize;
        return $dataProvider;
    }
}

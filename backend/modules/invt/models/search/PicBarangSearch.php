<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\PicBarang;

/**
 * PicBarangSearch represents the model behind the search form about `backend\modules\invt\models\PicBarang`.
 */
class PicBarangSearch extends PicBarang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pic_barang_id', 'pengeluaran_barang_id', 'pegawai_id', 'is_unassign', 'deleted'], 'integer'],
            [['tgl_assign', 'keterangan', 'tgl_unassign', 'deleted_by', 'deleted_at', 'updated_by', 'updated_at', 'created_by', 'created_at'], 'safe'],
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
        $query = PicBarang::find();
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
            'pegawai_id' => $this->pegawai_id,
            'tgl_assign' => $this->tgl_assign,
            'is_unassign' => $this->is_unassign,
            'tgl_unassign' => $this->tgl_unassign,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);
        $query->andWhere(['deleted'=>0]);

        return $dataProvider;
    }
}

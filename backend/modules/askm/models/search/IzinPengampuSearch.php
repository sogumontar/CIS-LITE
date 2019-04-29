<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\IzinPengampu;

/**
 * IzinPengampuSearch represents the model behind the search form about `backend\modules\askm\models\IzinPengampu`.
 */
class IzinPengampuSearch extends IzinPengampu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_izin_pengampu', 'izin_keluar_id', 'dosen_id', 'status_request_id', 'deleted'], 'integer'],
            [['deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
    public function search($params)
    {
        $query = IzinPengampu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC,
                'status_request_id' => SORT_ASC, 
            ]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_izin_pengampu' => $this->id_izin_pengampu,
            'izin_keluar_id' => $this->izin_keluar_id,
            'dosen_id' => $this->dosen_id,
            'status_request_id' => $this->status_request_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}

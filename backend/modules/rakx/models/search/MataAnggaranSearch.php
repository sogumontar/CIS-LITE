<?php

namespace backend\modules\rakx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rakx\models\MataAnggaran;

/**
 * MataAnggaranSearch represents the model behind the search form about `backend\modules\rakx\models\MataAnggaran`.
 */
class MataAnggaranSearch extends MataAnggaran
{
    /**
     * @inheritdoc
     */
    public $standar_name;
    public function rules()
    {
        return [
            [['mata_anggaran_id', 'standar_id', 'deleted'], 'integer'],
            [['standar_name', 'kode_anggaran', 'name', 'desc', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = MataAnggaran::find();
        $query->joinWith(['standar']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['standar_name'] = [
            'asc' => ['rakx_r_standar.name' => SORT_ASC],
            'desc' => ['rakx_r_standar.name' => SORT_DESC],
        ];

        $query->andFilterWhere([
            'mata_anggaran_id' => $this->mata_anggaran_id,
            'standar_id' => $this->standar_id,
            'rakx_r_standar.standar_id' => $this->standar_name,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'kode_anggaran', $this->kode_anggaran])
            ->andFilterWhere(['like', 'rakx_mata_anggaran.name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            //->andFilterWhere(['like', 'rakx_r_standar.name', $this->standar_name])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_mata_anggaran.deleted' => 1]]);

        return $dataProvider;
    }
}

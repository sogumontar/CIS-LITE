<?php

namespace backend\modules\mref\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\mref\models\StatusMahasiswa;

/**
 * StatusMahasiswaSearch represents the model behind the search form about `backend\modules\mref\models\StatusMahasiswa`.
 */
class StatusMahasiswaSearch extends StatusMahasiswa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_mahasiswa_id'], 'integer'],
            [['nama', 'desc'], 'safe'],
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
        $query = StatusMahasiswa::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status_mahasiswa_id' => $this->status_mahasiswa_id,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}

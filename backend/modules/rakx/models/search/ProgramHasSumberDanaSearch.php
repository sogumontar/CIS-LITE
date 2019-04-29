<?php

namespace backend\modules\rakx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rakx\models\ProgramHasSumberDana;

/**
 * ProgramHasSumberDanaSearch represents the model behind the search form about `backend\modules\rakx\models\ProgramHasSumberDana`.
 */
class ProgramHasSumberDanaSearch extends ProgramHasSumberDana
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_has_sumber_dana_id', 'program_id', 'sumber_dana_id', 'deleted'], 'integer'],
            [['jumlah', 'desc', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
    public function search($params, $program_id=null)
    {
        $query = ProgramHasSumberDana::find();
        if($program_id!=null){
            $query = ProgramHasSumberDana::find()->where(['program_id' => $program_id]);
        }

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
            'program_has_sumber_dana_id' => $this->program_has_sumber_dana_id,
            'program_id' => $this->program_id,
            'sumber_dana_id' => $this->sumber_dana_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_program_has_sumber_dana.deleted' => 1]]);

        return $dataProvider;
    }
}

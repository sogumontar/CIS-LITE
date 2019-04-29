<?php

namespace backend\modules\rakx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rakx\models\ReviewProgram;

/**
 * ReviewProgram represents the model behind the search form about `backend\modules\rakx\models\ReviewProgram`.
 */
class ReviewProgramSearch extends ReviewProgram
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_program_id', 'program_id', 'pejabat_id', 'deleted'], 'integer'],
            [['review', 'tanggal_review', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = ReviewProgram::find();

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
            'review_program_id' => $this->review_program_id,
            'program_id' => $this->program_id,
            'pejabat_id' => $this->pejabat_id,
            'tanggal_review' => $this->tanggal_review,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'review', $this->review])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_review_program.deleted' => 1]]);

        return $dataProvider;
    }
}

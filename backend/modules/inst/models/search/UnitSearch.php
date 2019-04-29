<?php

namespace backend\modules\inst\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\inst\models\Unit;

/**
 * UnitSearch represents the model behind the search form about `backend\modules\inst\models\Unit`.
 */
class UnitSearch extends Unit
{
    public $kepala_nama;
    public $instansi_nama;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id', 'kepala', 'deleted'], 'integer'],
            [['kepala_nama', 'instansi_nama', 'name', 'inisial', 'desc', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
        $query = Unit::find();
        //$query->andWhere('deleted != 1');

        $query->joinWith(['kepala0', 'instansi']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['instansi_nama'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['kepala_nama'] = [
            'asc' => ['kepala0.jabatan' => SORT_ASC],
            'desc' => ['kepala0.jabatan' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'unit_id' => $this->unit_id,
            'kepala' => $this->kepala,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'inst_unit.name', $this->name])
            ->andFilterWhere(['like', 'inst_unit.inisial', $this->inisial])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'kepala0.jabatan', $this->kepala_nama])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->instansi_nama])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['not', ['inst_unit.deleted' => 1]]);

        //$query->orderBy(['instansi_id' => SORT_ASC, 'name' => SORT_ASC]);

        return $dataProvider;
    }

    /*public function searchDim($_query, $params=null)
    {
        $query = $_query->distinct();
        
        if(!is_null($params))
        {
            $this->load($params);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        //$query->join("left join","rprt_r_bagian","rprt_complaint.bagian_id=rprt_r_bagian.bagian_id");
        
        //$query->join("left join","rprt_r_status","rprt_complaint.status_id=rprt_r_status.status_id");
        
        $query->andFilterWhere([
            'inst_unit.unit_id' => $this->unit_id,
            'inst_unit.kepala' => $this->kepala,
            'inst_unit.deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'inst_unit.name', $this->name])
            ->andFilterWhere(['like', 'inst_unit.inisial', $this->inisial])
            ->andFilterWhere(['like', 'inst_unit.desc', $this->desc]);

        //$dataProvider->pagination->pageSize = 20;
        return $dataProvider;
    }*/
}

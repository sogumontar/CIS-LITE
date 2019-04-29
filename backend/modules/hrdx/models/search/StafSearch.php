<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Staf;

/**
 * StafSearch represents the model behind the search form about `backend\modules\hrdx\models\Staf`.
 */
class StafSearch extends Staf
{
    public $nip;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staf_id', 'deleted'], 'integer'],
            [['pegawai_id', 'prodi_id'],'string'],
            [['nip', 'staf_role_id', 'aktif_start', 'aktif_end', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = Staf::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['pegawai','prodi', 'stafRole']);
        $query->join('LEFT JOIN', 'mref_r_status_aktif_pegawai', 'mref_r_status_aktif_pegawai.status_aktif_pegawai_id = hrdx_pegawai.status_aktif_pegawai_id');

        $query->andFilterWhere([
            'staf_id' => $this->staf_id,
            //'pegawai_id' => $this->pegawai_id,
            // 'prodi_id' => $this->prodi_id,
            'hrdx_staf.staf_role_id' => $this->staf_role_id,
            'aktif_start' => $this->aktif_start,
            'aktif_end' => $this->aktif_end,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_id])
            ->andFilterWhere(['like', 'inst_prodi.kbk_ind',$this->prodi_id])
            ->andFilterWhere(['like', 'hrdx_pegawai.nip',$this->nip])
            ->orderBy(['mref_r_status_aktif_pegawai.nama'=>SORT_ASC, 'hrdx_pegawai.nama'=>SORT_ASC]);
        $query->andWhere([$this->tableName().'.deleted'=>0]);
        return $dataProvider;
    }
}

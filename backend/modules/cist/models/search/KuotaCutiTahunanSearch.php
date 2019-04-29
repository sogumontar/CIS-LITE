<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\KuotaCutiTahunan;

/**
 * KuotaCutiTahunanSearch represents the model behind the search form about `backend\modules\cist\models\KuotaCutiTahunan`.
 */
class KuotaCutiTahunanSearch extends KuotaCutiTahunan
{
    public $pegawai_nama;
    public $pegawai_ikatan;
    public $pegawai_masuk;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kuota_cuti_tahunan_id', 'pegawai_id', 'kuota', 'deleted'], 'integer'],
            [['pegawai_nama', 'pegawai_ikatan', 'pegawai_masuk'], 'safe'],
            [['deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = KuotaCutiTahunan::find();
        $query->joinWith(['pegawai']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pegawai_ikatan'] = [
            'asc' => ['hrdx_pegawai.status_ikatan_kerja_pegawai_id' => SORT_ASC],
            'desc' => ['hrdx_pegawai.status_ikatan_kerja_pegawai_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pegawai_masuk'] = [
            'asc' => ['hrdx_pegawai.tanggal_masuk' => SORT_ASC],
            'desc' => ['hrdx_pegawai.tanggal_masuk' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'kuota_cuti_tahunan_id' => $this->kuota_cuti_tahunan_id,
            'pegawai_id' => $this->pegawai_id,
            'kuota' => $this->kuota,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}

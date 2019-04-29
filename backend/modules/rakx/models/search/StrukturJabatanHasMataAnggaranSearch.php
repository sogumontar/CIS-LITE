<?php

namespace backend\modules\rakx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rakx\models\StrukturJabatanHasMataAnggaran;

/**
 * StrukturJabatanHasMataAnggaranSearch represents the model behind the search form about `backend\modules\rakx\models\StrukturJabatanHasMataAnggaran`.
 */
class StrukturJabatanHasMataAnggaranSearch extends StrukturJabatanHasMataAnggaran
{
    /**
     * @inheritdoc
     */
    public $struktur_jabatan_jabatan;
    public $mata_anggaran_name;
    public $tahun_anggaran_name;
    public function rules()
    {
        return [
            [['struktur_jabatan_has_mata_anggaran_id', 'struktur_jabatan_id', 'mata_anggaran_id', 'tahun_anggaran_id', 'deleted'], 'integer'],
            [['tahun_anggaran_name', 'mata_anggaran_name', 'struktur_jabatan_jabatan', 'subtotal', 'desc', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = StrukturJabatanHasMataAnggaran::find();
        $query->joinWith(['strukturJabatan', 'mataAnggaran', 'tahunAnggaran']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['struktur_jabatan_jabatan'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['mata_anggaran_name'] = [
            'asc' => ['rakx_mata_anggaran.name' => SORT_ASC],
            'desc' => ['rakx_mata_anggaran.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['tahun_anggaran_name'] = [
            'asc' => ['rakx_r_tahun_anggaran.tahun' => SORT_ASC],
            'desc' => ['rakx_r_tahun_anggaran.tahun' => SORT_DESC],
        ];

        $query->andFilterWhere([
            'struktur_jabatan_has_mata_anggaran_id' => $this->struktur_jabatan_has_mata_anggaran_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'inst_struktur_jabatan.struktur_jabatan_id' => $this->struktur_jabatan_jabatan,
            'mata_anggaran_id' => $this->mata_anggaran_id,
            'rakx_mata_anggaran.mata_anggaran_id' => $this->mata_anggaran_name,
            'tahun_anggaran_id' => $this->tahun_anggaran_id,
            'rakx_r_tahun_anggaran.tahun_anggaran_id' => $this->tahun_anggaran_name,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subtotal', $this->subtotal])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_struktur_jabatan_has_mata_anggaran.deleted' => 1]]);

        return $dataProvider;
    }
}

<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\DimKamar;

/**
 * DimKamarSearch represents the model behind the search form about `backend\modules\askm\models\DimKamar`.
 */
class DimKamarSearch extends DimKamar
{
    public $dim_nama;
    public $dim_prodi;
    public $dim_dosen;
    public $dim_angkatan;
    public $dim_kamar;
    public $dim_asrama;
    public $nomor_kamar;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dim_kamar_id', 'status_dim_kamar', 'dim_id', 'kamar_id', 'deleted'], 'integer'],
            [['deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'dim_nama', 'dim_prodi', 'dim_angkatan', 'dim_asrama', 'dim_kamar', 'nomor_kamar', 'dim_dosen'], 'safe'],
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
        $query = DimKamar::find();
        $query->joinWith(['dim', 'kamar', 'kamar.asrama'/*, 'dim.registrasis.dosenWali'*/]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['dim_nama'] = [
            'asc' => ['dimx_dim.nama' => SORT_ASC],
            'desc' => ['dimx_dim.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_prodi'] = [
            'asc' => ['dimx_dim.ref_kbk_id' => SORT_ASC],
            'desc' => ['dimx_dim.ref_kbk_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_angkatan'] = [
            'asc' => ['dimx_dim.thn_masuk' => SORT_ASC],
            'desc' => ['dimx_dim.thn_masuk' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_dosen'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_asrama'] = [
            'asc' => ['askm_asrama.asrama_id' => SORT_ASC],
            'desc' => ['askm_asrama.asrama_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nomor_kamar'] = [
            'asc' => ['askm_kamar.nomor_kamar' => SORT_ASC],
            'desc' => ['askm_kamar.nomor_kamar' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'dim_kamar_id' => $this->dim_kamar_id,
            'status_dim_kamar' => $this->status_dim_kamar,
            'dim_id' => $this->dim_id,
            'kamar_id' => $this->kamar_id,
            'askm_asrama.asrama_id' => $this->dim_asrama,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'askm_kamar.nomor_kamar', $this->nomor_kamar])
            //->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->dim_dosen])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            // ->andFilterWhere(['like', 'dimx_dim.ref_kbk_id', $this->dim_prodi])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif']);

        return $dataProvider;
    }
}

<?php

namespace backend\modules\mref\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Dosen;

/**
 * DosenSearch represents the model behind the search form about `backend\modules\hrdx\models\Dosen`.
 */
class DosenSearch extends Dosen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dosen_id', 'prodi_id', 'golongan_kepangkatan_id', 'jabatan_akademik_id', 'status_ikatan_kerja_dosen_id', 'gbk_id', 'role_dosen_id', 'deleted', 'pegawai_id'], 'integer'],
            [['aktif_start', 'aktif_end', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'nidn'], 'safe'],
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
        $query = Dosen::find();

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
            'dosen_id' => $this->dosen_id,
            'prodi_id' => $this->prodi_id,
            'golongan_kepangkatan_id' => $this->golongan_kepangkatan_id,
            'jabatan_akademik_id' => $this->jabatan_akademik_id,
            'status_ikatan_kerja_dosen_id' => $this->status_ikatan_kerja_dosen_id,
            'gbk_id' => $this->gbk_id,
            'aktif_start' => $this->aktif_start,
            'aktif_end' => $this->aktif_end,
            'role_dosen_id' => $this->role_dosen_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pegawai_id' => $this->pegawai_id,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'nidn', $this->nidn]);

        return $dataProvider;
    }
}

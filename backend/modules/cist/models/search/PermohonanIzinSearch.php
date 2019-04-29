<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\PermohonanIzin;

/**
 * PermohonanIzinSearch represents the model behind the search form about `backend\modules\cist\models\PermohonanIzin`.
 */
class PermohonanIzinSearch extends PermohonanIzin
{
    public $pegawai_nama;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_izin_id', 'kategori_id', 'lama_izin', 'status_izin_id', 'atasan_id', 'pegawai_id', 'deleted'], 'integer'],
            [['kode_file_surat', 'file_surat', 'pegawai_nama', 'waktu_pelaksanaan', 'alasan_izin', 'pengalihan_tugas', 'file_surat', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = PermohonanIzin::find();
        $query->joinWith(['pegawai']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'permohonan_izin_id' => $this->permohonan_izin_id,
            'kategori_id' => $this->kategori_id,
            'lama_izin' => $this->lama_izin,
            'status_izin_id' => $this->status_izin_id,
            'atasan_id' => $this->atasan_id,
            'pegawai_id' => $this->pegawai_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'waktu_pelaksanaan', $this->waktu_pelaksanaan])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'alasan_izin', $this->alasan_izin])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'file_surat', $this->file_surat])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}

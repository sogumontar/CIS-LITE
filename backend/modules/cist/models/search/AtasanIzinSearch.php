<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\AtasanIzin;

/**
 * AtasanIzinSearch represents the model behind the search form about `backend\modules\cist\models\AtasanIzin`.
 */
class AtasanIzinSearch extends AtasanIzin
{
    public $lama_izin;
    public $pegawai_nama;
    public $waktu_pelaksanaan;
    public $alasan_izin;
    public $pengalihan_tugas;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['atasan_izin_id', 'lama_izin', 'permohonan_izin_id', 'pegawai_id', 'deleted'], 'integer'],
            [['pegawai_nama', 'waktu_pelaksanaan', 'alasan_izin', 'pengalihan_tugas', 'name', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = AtasanIzin::find();
        $query->joinWith('permohonanIzin.pegawai', 'permohonanIzin');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['lama_izin'] = [
            'asc' => ['cist_permohonan_izin.lama_izin' => SORT_ASC],
            'desc' => ['cist_permohonan_izin.lama_izin' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['waktu_pelaksanaan'] = [
            'asc' => ['cist_permohonan_izin.waktu_pelaksanaan' => SORT_ASC],
            'desc' => ['cist_permohonan_izin.waktu_pelaksanaan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pengalihan_tugas'] = [
            'asc' => ['cist_permohonan_izin.pengalihan_tugas' => SORT_ASC],
            'desc' => ['cist_permohonan_izin.pengalihan_tugas' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['alasan_izin'] = [
            'asc' => ['cist_permohonan_izin.alasan_izin' => SORT_ASC],
            'desc' => ['cist_permohonan_izin.alasan_izin' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'atasan_izin_id' => $this->atasan_izin_id,
            'permohonan_izin_id' => $this->permohonan_izin_id,
            'cist_atasan_izin.pegawai_id' => $this->pegawai_id,
            'lama_izin' => $this->lama_izin,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cist_permohonan_izin.waktu_pelaksanaan', $this->waktu_pelaksanaan])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'cist_permohonan_izin.alasan_izin', $this->alasan_izin])
            ->andFilterWhere(['like', 'cist_permohonan_izin.pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}

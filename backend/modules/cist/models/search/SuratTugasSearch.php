<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\AtasanSuratTugas;
use backend\modules\cist\models\Pegawai;

/**
 * SuratTugasSearch represents the model behind the search form about `backend\modules\cist\models\SuratTugas`.
 */
class SuratTugasSearch extends SuratTugas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_id', 'pengalihan_tugas', 'jenis_surat_id', 'status_id', 'deleted', 'penandatangan', 'penyetuju'], 'integer'],
            [['perequest', 'no_surat', 'tempat', 'tanggal_berangkat', 'tanggal_kembali', 'agenda', 'review_surat', 'desc_surat_tugas', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by','nama_kegiatan', 'kembali_bekerja', 'status_sppd'], 'safe'],
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
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);

        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('perequest0');

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
            'penyetuju' => $this->penyetuju,
        ]);

        $query->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            //->andFilterWhere(['in', 'status_id', [3, 6]])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchWr($params)
    {
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);

        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('perequest0');

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
            'penyetuju' => $this->penyetuju,
        ]);

        $query->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['in', 'status_id', 3])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchPegawai($params)
    {
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);
        $arraySuratTugasId = SuratTugas::getSuratTugas(Yii::$app->user->identity->id);

        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('perequest0');

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
            'penyetuju' => $this->penyetuju,
        ]);

        $query->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            //->andFilterWhere(['in', 'status_id', [3, 6]])
            // ->andFilterWhere(['in', 'surat_tugas_id', $arraySuratTugasId])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchHrd($params)
    {
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);

        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('perequest0');

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
            'penyetuju' => $this->penyetuju,
        ]);

        $query->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            ->andFilterWhere(['in', 'status_id', [3, 6]])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchBawahan($params)
    {
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                    'created_at' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);

        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $surat_tugas = AtasanSuratTugas::find()->select(['cist_atasan_surat_tugas.surat_tugas_id'])->where('cist_atasan_surat_tugas.deleted!=1')->joinWith([
            'atasan.user' => function($query){
                $query->where(['sysx_user.user_id' => Yii::$app->user->identity->id]);
            }
        ])->asArray();
        $query->joinWith('perequest0');

        $query->andFilterWhere([
            // ['in', 'surat_tugas_id', $arraySuratTugasId],
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'atasan' => $this->atasan,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
        ]);

        $query->andFilterWhere(['in', 'surat_tugas_id', $surat_tugas])
            ->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchSek($params)
    {
        $query = SuratTugas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status_sppd' => SORT_ASC,
                    'created_at' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'pageSize' => 7
            ]
        ]);
        
        $this->load($params);

        // echo "<pre>";print_r($this);
        // die();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('perequest0');

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            //'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'pengalihan_tugas' => $this->pengalihan_tugas,
            'jenis_surat_id' => $this->jenis_surat_id,
            'status_id' => $this->status_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'penandatangan' => $this->penandatangan,
            'kembali_bekerja' => $this->kembali_bekerja,
            'status_sppd' => $this->status_sppd,
        ]);

        $query->andFilterWhere(['like', 'no_surat', $this->no_surat])
            ->andFilterWhere(['like', 'tempat', $this->tempat])
            ->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'nama_kegiatan', $this->nama_kegiatan])
            ->andFilterWhere(['like', 'review_surat', $this->review_surat])
            ->andFilterWhere(['like', 'desc_surat_tugas', $this->desc_surat_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->perequest])
            ->andFilterWhere(['in', 'status_id', [3]])
            ->andFilterWhere(['not', ['cist_surat_tugas.deleted' => 1]]);

        return $dataProvider;
    }
}

<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\IzinBermalam;
use backend\modules\askm\models\DimKamar;
use backend\modules\adak\models\Registrasi;

/**
 * IzinBermalamSearch represents the model behind the search form about `backend\modules\askm\models\IzinBermalam`.
 */
class IzinBermalamSearch extends IzinBermalam
{
    public $dim_nama;
    public $dim_prodi;
    public $dim_dosen;
    public $dim_angkatan;
    public $dim_kamar;
    public $dim_asrama;
    public $keasramaan;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['izin_bermalam_id', 'dim_id', 'keasramaan_id', 'status_request_id', 'deleted', 'dim_asrama'], 'integer'],
            [['rencana_berangkat', 'rencana_kembali', 'realisasi_berangkat', 'realisasi_kembali', 'desc', 'tujuan','dim_nama', 'dim_prodi', 'dim_angkatan', 'dim_dosen', 'keasramaan', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = IzinBermalam::find();
        $query->joinWith(['dim', 'dim.dimAsrama.kamar.asrama', 'dim.registrasis', 'dim.registrasis.dosenWali']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => ['status_request_id' => SORT_ASC, 'created_at' => SORT_ASC]],
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

        $dataProvider->sort->attributes['dim_asrama'] = [
            'asc' => ['askm_asrama.asrama_id' => SORT_ASC],
            'desc' => ['askm_asrama.asrama_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_dosen'] = [
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
            'izin_bermalam_id' => $this->izin_bermalam_id,
            'dim_id' => $this->dim_id,
            'keasramaan_id' => $this->keasramaan_id,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'status_request_id' => $this->status_request_id,
            'askm_asrama.asrama_id' => $this->dim_asrama,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'askm_izin_bermalam.desc', $this->desc])
            ->andFilterWhere(['like', 'rencana_berangkat',SUBSTR($this->rencana_berangkat,1,10)])
            ->andFilterWhere(['like', 'rencana_kembali',SUBSTR($this->rencana_kembali,1,10)])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->dim_dosen])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            // ->andFilterWhere(['like', 'dimx_dim.ref_kbk_id', $this->dim_prodi])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_bermalam.deleted' => 1]]);

            if($this->realisasi_berangkat!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_berangkat',SUBSTR($this->realisasi_kembali,1,10)]);
            }
            if($this->realisasi_kembali!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_kembali',SUBSTR($this->realisasi_kembali,1,10)]);
            }

        return $dataProvider;
    }

    public function searchByMahasiswa($params)
    {
        $query = IzinBermalam::find();
        $query->joinWith(['dim', 'dim.dimAsrama.kamar.asrama']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC]],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izin_bermalam_id' => $this->izin_bermalam_id,
            'dimx_dim.user_id' => Yii::$app->user->identity->user_id,
            'keasramaan_id' => $this->keasramaan_id,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'status_request_id' => $this->status_request_id,
            'askm_asrama.asrama_id' => $this->dim_asrama,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'askm_izin_bermalam.desc', $this->desc])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            // ->andFilterWhere(['like', 'dimx_dim.ref_kbk_id', $this->dim_prodi])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_bermalam.deleted' => 1]]);

            if($this->realisasi_berangkat!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_berangkat',SUBSTR($this->realisasi_kembali,1,10)]);
            }
            if($this->realisasi_kembali!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_kembali',SUBSTR($this->realisasi_kembali,1,10)]);
            }

        return $dataProvider;
    }

    public function searchIbIndex($params)
    {
        $query = IzinBermalam::find();
        $query->joinWith(['dim', 'dim.dimAsrama.kamar.asrama']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => ['status_request_id' => SORT_ASC, 'created_at' => SORT_ASC]],
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

        $dataProvider->sort->attributes['dim_asrama'] = [
            'asc' => ['askm_asrama.asrama_id' => SORT_ASC],
            'desc' => ['askm_asrama.asrama_id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izin_bermalam_id' => $this->izin_bermalam_id,
            'dim_id' => $this->dim_id,
            'keasramaan_id' => $this->keasramaan_id,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'status_request_id' => $this->status_request_id,
            'askm_asrama.asrama_id' => $this->dim_asrama,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'askm_izin_bermalam.desc', $this->desc])
            ->andFilterWhere(['like', 'rencana_berangkat',SUBSTR($this->rencana_berangkat,1,10)])
            ->andFilterWhere(['like', 'rencana_kembali',SUBSTR($this->rencana_kembali,1,10)])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            //->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->dim_dosen])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            // ->andFilterWhere(['like', 'dimx_dim.ref_kbk_id', $this->dim_prodi])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_bermalam.deleted' => 1]]);

            if($this->realisasi_berangkat!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_berangkat',SUBSTR($this->realisasi_kembali,1,10)]);
            }
            if($this->realisasi_kembali!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_kembali',SUBSTR($this->realisasi_kembali,1,10)]);
            }

        return $dataProvider;
    }

    public function searchCombinedLog($params)
    {
        $query = IzinBermalam::find();
        $query->joinWith(['dim'/*, 'dim.dimAsrama.kamar.asrama'*/]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => ['status_request_id' => SORT_ASC, 'created_at' => SORT_ASC]],
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

        $dataProvider->sort->attributes['dim_asrama'] = [
            'asc' => ['askm_asrama.asrama_id' => SORT_ASC],
            'desc' => ['askm_asrama.asrama_id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izin_bermalam_id' => $this->izin_bermalam_id,
            'dim_id' => $this->dim_id,
            'keasramaan_id' => $this->keasramaan_id,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'status_request_id' => $this->status_request_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'askm_izin_bermalam.desc', $this->desc])
            ->andFilterWhere(['like', 'rencana_berangkat',SUBSTR($this->rencana_berangkat,1,10)])
            ->andFilterWhere(['like', 'rencana_kembali',SUBSTR($this->rencana_kembali,1,10)])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_bermalam.deleted' => 1]]);

            ///if($this->realisasi_berangkat!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_berangkat',SUBSTR($this->realisasi_kembali,1,10)]);
            //}
            if($this->realisasi_kembali!=""){
                $query->andFilterWhere(['like', 'askm_izin_bermalam.realisasi_kembali',SUBSTR($this->realisasi_kembali,1,10)]);
            }

            if($this->dim_dosen!=""){
                $dosen_wali = (int)$this->dim_dosen;
                $dim_arr = Registrasi::find()->select(['adak_registrasi.dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->joinWith(['dosenWali' => function($query) use($dosen_wali){
                        $query->where(['hrdx_pegawai.pegawai_id' => $dosen_wali]);
                    }]);
                $query->andFilterWhere(['in', 'askm_izin_bermalam.dim_id', $dim_arr]);
            }

            if($this->dim_asrama!=""){
                $asrama = (int)$this->dim_asrama;
                $dim_arr = DimKamar::find()->select(['askm_dim_kamar.dim_id'])->where('askm_dim_kamar.deleted!=1')->joinWith(['kamar' => function($query) use($asrama){
                    $query->where(['askm_kamar.asrama_id' => $asrama]);
                }]);
                $query->andFilterWhere(['in', 'askm_izin_bermalam.dim_id', $dim_arr]);
            }

        return $dataProvider;
    }
}

<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\LogMahasiswa;
use backend\modules\askm\models\DimKamar;
use backend\modules\adak\models\Registrasi;

/**
 * LogMahasiswaSearch represents the model behind the search form about `backend\modules\askm\models\LogMahasiswa`.
 */
class LogMahasiswaSearch extends LogMahasiswa
{
    public $dim_nama;
    public $dim_prodi;
    public $dim_dosen;
    public $dim_asrama;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_mahasiswa_id', 'dim_id', 'deleted', 'dim_asrama'], 'integer'],
            [['realisasi_berangkat', 'realisasi_kembali', 'tanggal_keluar', 'tanggal_masuk', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'dim_nama', 'dim_prodi', 'dim_dosen'], 'safe'],
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
        $query = LogMahasiswa::find();
        $query->joinWith(['dim'/*, 'dim.dimAsrama.kamar.asrama', 'dim.registrasis.dosenWali'*/]);

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

        $dataProvider->sort->attributes['dim_nama'] = [
            'asc' => ['dimx_dim.nama' => SORT_ASC],
            'desc' => ['dimx_dim.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_prodi'] = [
            'asc' => ['dimx_dim.ref_kbk_id' => SORT_ASC],
            'desc' => ['dimx_dim.ref_kbk_id' => SORT_DESC],
        ];

        $query->andFilterWhere([
            'log_mahasiswa_id' => $this->log_mahasiswa_id,
            'dim_id' => $this->dim_id,
            //'askm_asrama.asrama_id' => $this->dim_asrama,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            //->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->dim_dosen])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            //->andFilterWhere(['like', 'askm_log_mahasiswa.tanggal_keluar',SUBSTR($this->tanggal_keluar,1,10)])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['askm_log_mahasiswa.deleted' => 1]]);

            if($this->tanggal_masuk!=""){
                $query->andFilterWhere(['like', 'askm_log_mahasiswa.tanggal_masuk',SUBSTR($this->tanggal_masuk,1,10)]);
            }

            // if($this->dim_asrama!=""){
            //     $asrama_id = (int)$this->dim_asrama;
            //     $dim_arr = DimKamar::find()->where('askm_dim_kamar.deleted!=1')->orderBy(['askm_dim_kamar.created_at' => SORT_DESC])/*->groupBy(['askm_dim_kamar.dim_id'])*/->joinWith(['kamar' => function($query) use($asrama_id) {
            //         $query->where('askm_kamar.deleted!=1')->andWhere(['asrama_id' => $asrama_id]);
            //     }])->all();
            //     $dim_arr = DimKamar::find()->where('askm_dim_kamar.deleted!=1')->orderBy(['askm_dim_kamar.created_at' => SORT_DESC])->distinct()->all();
            //     foreach($dim_arr as $d){
            //         echo $d->dim->nama.' - '.$d->kamar->asrama->name.'<br />';
            //     }
            //     die;
            //     $query->andFilterWhere(['in', 'askm_log_mahasiswa.dim_id', $dim_arr]);
            // }

        return $dataProvider;
    }

    public function searchCombinedLog($params)
    {
        $query = LogMahasiswa::find();
        $query->joinWith(['dim'/*, 'dim.dimAsrama.kamar.asrama'*/]);

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

        $dataProvider->sort->attributes['dim_nama'] = [
            'asc' => ['dimx_dim.nama' => SORT_ASC],
            'desc' => ['dimx_dim.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['dim_prodi'] = [
            'asc' => ['dimx_dim.ref_kbk_id' => SORT_ASC],
            'desc' => ['dimx_dim.ref_kbk_id' => SORT_DESC],
        ];

        $query->andFilterWhere([
            'log_mahasiswa_id' => $this->log_mahasiswa_id,
            'dim_id' => $this->dim_id,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['>', 'askm_log_mahasiswa.created_at', date('Y-m-d H:i:s', strtotime("-1 week"))])
            //->andFilterWhere(['like', 'askm_log_mahasiswa.tanggal_keluar',SUBSTR($this->tanggal_keluar,1,10)])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['askm_log_mahasiswa.deleted' => 1]]);

            if($this->tanggal_masuk!=""){
                $query->andFilterWhere(['like', 'askm_log_mahasiswa.tanggal_masuk',SUBSTR($this->tanggal_masuk,1,10)]);
            }

            if($this->dim_dosen!=""){
                $dosen_wali = (int)$this->dim_dosen;
                $dim_arr = Registrasi::find()->select(['adak_registrasi.dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->joinWith(['dosenWali' => function($query) use($dosen_wali){
                        $query->where(['hrdx_pegawai.pegawai_id' => $dosen_wali]);
                    }]);
                $query->andFilterWhere(['in', 'askm_log_mahasiswa.dim_id', $dim_arr]);
            }

            if($this->dim_asrama!=""){
                $asrama = (int)$this->dim_asrama;
                $dim_arr = DimKamar::find()->select(['askm_dim_kamar.dim_id'])->where('askm_dim_kamar.deleted!=1')->joinWith(['kamar' => function($query) use($asrama){
                    $query->where(['askm_kamar.asrama_id' => $asrama]);
                }]);
                $query->andFilterWhere(['in', 'askm_log_mahasiswa.dim_id', $dim_arr]);
            }

        return $dataProvider;
    }
}

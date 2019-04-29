<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\DimPenilaian;
use backend\modules\askm\models\DimKamar;
use backend\modules\adak\models\Registrasi;

/**
 * DimPenilaianSearch represents the model behind the search form about `backend\modules\askm\models\DimPenilaian`.
 */
class DimPenilaianSearch extends DimPenilaian
{
    public $dim_nama;
    public $dim_prodi;
    public $dim_angkatan;
    public $dim_dosen;
    public $dim_asrama;
    public $nilai_huruf;
    public $pembinaan;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penilaian_id', 'akumulasi_skor', 'dim_id', 'deleted', 'ta'], 'integer'],
            [['pembinaan', 'sem_ta', 'nilai_huruf', 'dim_asrama','dim_angkatan', 'dim_nama', 'dim_prodi', 'dim_dosen', 'desc', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = DimPenilaian::find();
        $query->joinWith(['dim'/*, 'dim.dimAsrama.kamar.asrama'*/]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]],
        ]);

        $dataProvider->sort->attributes['dim_nama'] = [
            'asc' => ['dimx_dim.nama' => SORT_ASC],
            'desc' => ['dimx_dim.nama' => SORT_DESC],
        ];

        // $dataProvider->sort->attributes['dim_asrama'] = [
        //     'asc' => ['askm_asrama.asrama_id' => SORT_ASC],
        //     'desc' => ['askm_asrama.asrama_id' => SORT_DESC],
        // ];

        $dataProvider->sort->attributes['dim_prodi'] = [
            'asc' => ['dimx_dim.ref_kbk_id' => SORT_ASC],
            'desc' => ['dimx_dim.ref_kbk_id' => SORT_DESC],
        ];

        // $dataProvider->sort->attributes['dim_angkatan'] = [
        //     'asc' => ['dimx_dim.thn_masuk' => SORT_ASC],
        //     'desc' => ['dimx_dim.thn_masuk' => SORT_DESC],
        // ];

        // $dataProvider->sort->attributes['nilai_huruf'] = [
        //     'asc' => ['nilai_huruf' => SORT_ASC],
        //     'desc' => ['nilai_huruf' => SORT_DESC],
        // ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'penilaian_id' => $this->penilaian_id,
            'akumulasi_skor' => $this->akumulasi_skor,
            'dim_id' => $this->dim_id,
            'ta' => $this->ta,
            'sem_ta' => $this->sem_ta,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dimx_dim.ref_kbk_id' => $this->dim_prodi,
            //'askm_asrama.asrama_id' => $this->dim_asrama,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['like', 'dimx_dim.thn_masuk', $this->dim_angkatan])
            ->andFilterWhere(['like', 'pembinaan', $this->pembinaan])
            ->andFilterWhere(['like', 'nilai_huruf', $this->nilai_huruf])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['askm_dim_penilaian.deleted' => 1]]);

        if($this->dim_dosen!=""){
                $dosen_wali = (int)$this->dim_dosen;
                $dim_arr = Registrasi::find()->select(['adak_registrasi.dim_id'])->where('adak_registrasi.deleted!=1')->andWhere(['status_akhir_registrasi' => 'Aktif'])->andWhere(['ta' => \Yii::$app->appConfig->get('tahun_ajaran', true), 'sem_ta' => \Yii::$app->appConfig->get('semester_tahun_ajaran', true)])->joinWith(['dosenWali' => function($query) use($dosen_wali){
                        $query->where(['hrdx_pegawai.pegawai_id' => $dosen_wali]);
                    }]);
                $query->andFilterWhere(['in', 'askm_dim_penilaian.dim_id', $dim_arr]);
            }

            if($this->dim_asrama!=""){
                $asrama = (int)$this->dim_asrama;
                $dim_arr = DimKamar::find()->select(['askm_dim_kamar.dim_id'])->where('askm_dim_kamar.deleted!=1')->joinWith(['kamar' => function($query) use($asrama){
                    $query->where(['askm_kamar.asrama_id' => $asrama]);
                }]);
                $query->andFilterWhere(['in', 'askm_dim_penilaian.dim_id', $dim_arr]);
            }

        return $dataProvider;
    }
}

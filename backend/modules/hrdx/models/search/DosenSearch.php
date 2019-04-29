<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Dosen;
use backend\modules\hrdx\models\Pegawai;
use backend\modules\prkl\models\Kelas;
use backend\modules\adak\models\Registrasi;
use yii\db\Query;
use yii\db\Expression;

/**
 * DosenSearch represents the model behind the search form about `backend\modules\hrdx\models\Dosen`.
 */
class DosenSearch extends Dosen
{
    public $sem;
    public $ta;
    public $kelas_id;
    public $nim;
    public $nama = '';
    public $thn_masuk = 0;
    public $nip = '';
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            // [['dosen_id',  'golongan_kepangkatan_id', 'jabatan_akademik_id', 'status_ikatan_kerja_dosen_id', 'gbk_id', 'deleted', 'pegawai_id'], 'integer'],
            [['dosen_id',  'golongan_kepangkatan_id',  'status_ikatan_kerja_dosen_id', 'deleted'], 'integer'],
            [['pegawai_id','jabatan_akademik_id','prodi_id','gbk_1', 'gbk_2'],'string'],
            [['sem','ta','prodi_id','aktif_start', 'aktif_end', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'nidn', 'status_aktif'], 'safe'],
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
        $query->joinWith(['pegawai','prodi', 'jabatanAkademik']);
        $query->join('LEFT JOIN', 'mref_r_status_aktif_pegawai', 'mref_r_status_aktif_pegawai.status_aktif_pegawai_id = hrdx_pegawai.status_aktif_pegawai_id');

        $query->andFilterWhere([
            'dosen_id' => $this->dosen_id,
            // 'prodi_id' => $this->prodi_id,
            'golongan_kepangkatan_id' => $this->golongan_kepangkatan_id,
            //'jabatan_akademik_id' => $this->jabatan_akademik_id,
            'status_aktif_pegawai_id' => '',
            // 'status_ikatan_kerja_dosen_id' => $this->status_ikatan_kerja_dosen_id,
            // 'gbk_id' => $this->gbk_id,
            'aktif_start' => $this->aktif_start,
            'aktif_end' => $this->aktif_end,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // 'pegawai_id' => $this->pegawai_id,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'nidn', $this->nidn])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama',$this->pegawai_id])
            ->andFilterWhere(['like', 'mref_r_jabatan_akademik.desc',$this->jabatan_akademik_id])
            ->andFilterWhere(['like', 'mref_r_status_aktif_pegawai.desc',$this->status_aktif])
            ->andFilterWhere(['like', 'inst_prodi.kbk_ind',$this->prodi_id])
            ->orderBy(['mref_r_status_aktif_pegawai.nama'=>SORT_ASC, 'hrdx_pegawai.nama'=>SORT_ASC]);
            // ->orderBy('mref_r_status_aktif_pegawai.nama');
        $query->andWhere([$this->tableName().'.deleted'=>0]);


        return $dataProvider;
    }


    public function getAnakWali($dosen_id, $sem_ta, $ta, $params){
        $query = Registrasi::find();

        $kelas = $this->_getKelasWali($dosen_id, $ta);
        if(empty($kelas)){
            return null;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // $query->select('prkl_krs_mhs.dim_id as test');

        $query->andFilterWhere([
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        $query->where(['kelas_id'=>$kelas->kelas_id, 'adak_registrasi.sem_ta'=>$sem_ta, 'adak_registrasi.ta'=> $ta]);

        $query->join('left join', 'prkl_krs_mhs', 'adak_registrasi.dim_id = prkl_krs_mhs.dim_id and adak_registrasi.ta = prkl_krs_mhs.ta and adak_registrasi.sem_ta = prkl_krs_mhs.sem_ta');

        return $dataProvider;
    }


    private function _getKelasWali($dosen_id, $ta)
    {
        $kelasWali = Kelas::find()
                            ->where(['dosen_wali_id'=>$dosen_id, 'ta'=>$ta])
                            ->one();
        return $kelasWali;
    }


    public function getKelasWali($dosen_id, $ta){
        return Kelas::find()
                        ->where(['dosen_wali_id'=>$dosen_id, 'ta'=>$ta])
                        ->one();
    }

    public function searchAnakWaliInTaSem($params){
        $status_mahasiswa_aktif = Yii::$app->appConfig->get('status_mahasiswa_aktif');
        $pegawai = Pegawai::find()->where(['user_id' =>Yii::$app->user->id])->one();


        $ta = Yii::$app->appConfig->get('krs_tahun_ajaran');
        $sem_ta=Yii::$app->appConfig->get('krs_sem_ta');

        $query = Registrasi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        if(empty($pegawai)){
            return $dataProvider;
        }

        $this->load($params);

        // echo '<pre>';
        // var_dump($this->sem_ta);
        // echo '</pre>';

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['dim']);

        $query->where(['dosen_wali_id' => $pegawai->pegawai_id, 'ta' => $ta, 'sem_ta' => $sem_ta]);

        $query->andFilterWhere([
            'adak_registrasi.sem' => $this->sem,
            'kelas_id' => $this->kelas_id,
            'adak_registrasi.deleted' => 0,
            'dimx_dim.deleted' => 0,
        ]);

        $query//->andFilterWhere(['like', 'dimx_dim.status_akhir', $status_mahasiswa_aktif])
            //->andFilterWhere(['like', 'adak_registrasi.status_akhir_registrasi', $status_mahasiswa_aktif])
            ->andFilterWhere(['like', 'adak_registrasi.nim', $this->nim])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->nama])
            ->orderBy(['adak_registrasi.ta' => SORT_DESC, 'adak_registrasi.sem_ta' => SORT_DESC, 'adak_registrasi.nim' => SORT_ASC]);

        return $dataProvider;
    }

     public function searchUnionByPengajar($pegawai_id, $params){
        // echo '<pre>';
        // var_dump($params);
        // echo '</pre>';
        $this->load($params);
        $pengajarQuery = (new Query)
                    ->select([
                        'krkm_kuliah.kode_mk as kode_mk',
                        'nama_kul_ind',
                        'nama_kul_ing',
                        new Expression('Null as pengajaran_id'),
                        'pengajar_id',
                        'kurikulum_id as kuliah_id',
                        'ta',
                        'IF(sem MOD 2 =0, 2, 1) as sem_ta',
                        new Expression('1 as is_fulltime'),
                        'mref_r_role_pengajar.nama as role_pengajar',
                        new Expression('Null as start_date'),
                        new Expression('Null as end_date')])
                    ->from('hrdx_pengajar')
                    ->join('inner join', 'hrdx_pegawai', 'hrdx_pegawai.profile_old_id = hrdx_pengajar.id')
                    ->join('left join', 'krkm_kuliah', 'kurikulum_id = kuliah_id')
                    ->join('left join', 'mref_r_role_pengajar', 'mref_r_role_pengajar.role_pengajar_id = hrdx_pengajar.role')
                    ->andWhere(['hrdx_pegawai.pegawai_id' => $pegawai_id])
                    ->groupBy(['kurikulum_id', 'ta']);
        $pengajarQuery->andFilterWhere([
            'ta' => $this->ta,
            // 'sem_ta' => $this->sem_ta,
        ]);
        $pengajarQuery->andFilterWhere(['like', 'krkm_kuliah.nama_kul_ind', $this->nama_kul_ind])
                    ->andFilterWhere(['like', 'krkm_kuliah.kode_mk', $this->kode_mk]);

        $penugasan_pengajaranQuery = (new Query)
                    ->select([
                        'krkm_kuliah.kode_mk as kode_mk',
                        'nama_kul_ind',
                        'nama_kul_ing',
                        'adak_pengajaran.pengajaran_id',
                        new Expression('NULL as pengajar_id'),
                        'adak_pengajaran.kuliah_id',
                        'adak_pengajaran.ta',
                        'sem_ta',
                        'is_fulltime',
                        'mref_r_role_pengajar.nama as role_pengajar',
                        'start_date',
                        'end_date'])
                    ->from('adak_penugasan_pengajaran')
                    ->join('inner join', 'hrdx_pegawai', 'hrdx_pegawai.pegawai_id = adak_penugasan_pengajaran.pegawai_id')
                    ->join('left join', 'mref_r_role_pengajar', 'mref_r_role_pengajar.role_pengajar_id = adak_penugasan_pengajaran.role_pengajar_id')
                    ->join('left join', 'adak_pengajaran', 'adak_pengajaran.pengajaran_id = adak_penugasan_pengajaran.pengajaran_id')
                    ->join('left join', 'krkm_kuliah', 'adak_pengajaran.kuliah_id = krkm_kuliah.kuliah_id')
                    ->andWhere(['hrdx_pegawai.pegawai_id' => $pegawai_id]);
        $penugasan_pengajaranQuery->andFilterWhere([
            'ta' => $this->ta,
            'sem_ta' => $this->sem_ta,
        ]);
        $penugasan_pengajaranQuery->andFilterWhere(['like', 'krkm_kuliah.nama_kul_ind', $this->nama_kul_ind])
                    ->andFilterWhere(['like', 'krkm_kuliah.kode_mk', $this->kode_mk]);

        $unionQuery = (new Query)
                    ->from(['pengajaran' => $penugasan_pengajaranQuery->union($pengajarQuery)])
                    ->orderBy(['ta' => SORT_DESC, 'sem_ta' => SORT_DESC, 'nama_kul_ind' => SORT_ASC]);

        $provider = new ActiveDataProvider([
            'query' => $unionQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $provider;
    }

/*
    public function searchAnakWaliInTaSem($dosen_wali_id, $ta, $sem_ta, $params){
        $status_mahasiswa_aktif = Yii::$app->appConfig->get('status_mahasiswa_aktif');

        $query = Registrasi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // echo '<pre>';
        // var_dump($this->sem_ta);
        // echo '</pre>';

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['dim']);

        $query->where(['dosen_wali_id' => $dosen_wali_id, 'ta' => $ta, 'sem_ta' => $sem_ta]);

        $query->andFilterWhere([
            'adak_registrasi.sem' => $this->sem,
            'kelas_id' => $this->kelas_id,
            'adak_registrasi.deleted' => 0,
            'dimx_dim.deleted' => 0,
        ]);

        $query//->andFilterWhere(['like', 'dimx_dim.status_akhir', $status_mahasiswa_aktif])
            //->andFilterWhere(['like', 'adak_registrasi.status_akhir_registrasi', $status_mahasiswa_aktif])
            ->andFilterWhere(['like', 'adak_registrasi.nim', $this->nim])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->nama])
            ->orderBy(['adak_registrasi.ta' => SORT_DESC, 'adak_registrasi.sem_ta' => SORT_DESC, 'adak_registrasi.nim' => SORT_ASC]);

        return $dataProvider;
    }*/


}

<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\Pegawai;

/**
 * PegawaiSearch represents the model behind the search form about `backend\modules\hrdx\models\Pegawai`.
 */
class PegawaiSearch extends Pegawai
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'ref_kbk_id', 'agama_id', 'jenis_kelamin_id', 'golongan_darah_id', 'kabupaten_id', 'jabatan_akademik_id', 'gbk_1', 'gbk_2', 'status_ikatan_kerja_pegawai_id', 'status_marital_id', 'user_id', 'deleted'], 'integer'],
            [['status_aktif_pegawai_id', ],'string'],
            [['profile_old_id', 'nama', 'nip', 'kpt_no', 'kbk_id', 'alias', 'posisi', 'tempat_lahir', 'tgl_lahir', 'hp', 'telepon', 'alamat', 'alamat_libur', 'kecamatan', 'kota', 'kode_pos', 'no_ktp', 'email', 'ext_num', 'study_area_1', 'study_area_2', 'jabatan', 'status_akhir', 'tanggal_masuk', 'tanggal_keluar', 'nama_bapak', 'nama_ibu', 'status', 'nama_p', 'tgl_lahir_p', 'tmp_lahir_p', 'pekerjaan_ortu', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = Pegawai::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['statusAktifPegawai']);
        $query->andFilterWhere([
            'pegawai_id' => $this->pegawai_id,
            'ref_kbk_id' => $this->ref_kbk_id,
            'tgl_lahir' => $this->tgl_lahir,
            'agama_id' => $this->agama_id,
            'jenis_kelamin_id' => $this->jenis_kelamin_id,
            'golongan_darah_id' => $this->golongan_darah_id,
            'kabupaten_id' => $this->kabupaten_id,
            'jabatan_akademik_id' => $this->jabatan_akademik_id,
            'gbk_1' => $this->gbk_1,
            'gbk_2' => $this->gbk_2,
            'status_ikatan_kerja_pegawai_id' => $this->status_ikatan_kerja_pegawai_id,
            //'status_aktif_pegawai_id' => $this->status_aktif_pegawai_id,
            'tanggal_masuk' => $this->tanggal_masuk,
            'tanggal_keluar' => $this->tanggal_keluar,
            'status_marital_id' => $this->status_marital_id,
            'tgl_lahir_p' => $this->tgl_lahir_p,
            'user_id' => $this->user_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'profile_old_id', $this->profile_old_id])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->nama])
            ->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'kpt_no', $this->kpt_no])
            ->andFilterWhere(['like', 'kbk_id', $this->kbk_id])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'posisi', $this->posisi])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'hp', $this->hp])
            ->andFilterWhere(['like', 'telepon', $this->telepon])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'alamat_libur', $this->alamat_libur])
            ->andFilterWhere(['like', 'kecamatan', $this->kecamatan])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'kode_pos', $this->kode_pos])
            ->andFilterWhere(['like', 'no_ktp', $this->no_ktp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ext_num', $this->ext_num])
            ->andFilterWhere(['like', 'study_area_1', $this->study_area_1])
            ->andFilterWhere(['like', 'study_area_2', $this->study_area_2])
            ->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'status_akhir', $this->status_akhir])
            ->andFilterWhere(['like', 'nama_bapak', $this->nama_bapak])
            ->andFilterWhere(['like', 'nama_ibu', $this->nama_ibu])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'nama_p', $this->nama_p])
            ->andFilterWhere(['like', 'mref_r_status_aktif_pegawai.desc',$this->status_aktif_pegawai_id])
            ->andFilterWhere(['like', 'tmp_lahir_p', $this->tmp_lahir_p])
            ->andFilterWhere(['like', 'pekerjaan_ortu', $this->pekerjaan_ortu])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->orderBy(['mref_r_status_aktif_pegawai.nama'=>SORT_ASC, 'nama'=>SORT_ASC]);
        $query->andWhere([$this->tableName().'.deleted'=>0]);
        return $dataProvider;
    }
}

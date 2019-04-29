<?php

namespace backend\modules\rakx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rakx\models\Program;

/**
 * ProgramSearch represents the model behind the search form about `backend\modules\rakx\models\Program`.
 */
class ProgramSearch extends Program
{
    public $tahun_anggaran;
    public $mata_anggaran;
    public $status_program;
    public $struktur_jabatan;
    public $struktur_jabatan_old;
    public $struktur_jabatan_list;
    public $total;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'struktur_jabatan_has_mata_anggaran_id', 'kode_program', 'rencana_strategis_id', 'volume', 'satuan_id', 'status_program_id', 'diusulkan_oleh', 'dilaksanakan_oleh', 'disetujui_oleh', 'ditolak_oleh', 'is_revisi', 'direvisi_oleh', 'deleted'], 'integer'],
            [['total', 'status_program', 'struktur_jabatan_list', 'struktur_jabatan', 'struktur_jabatan_old', 'tahun_anggaran', 'mata_anggaran', 'name', 'tujuan', 'sasaran', 'target', 'desc', 'harga_satuan', 'jumlah_sebelum_revisi', 'jumlah', 'tanggal_diusulkan', 'tanggal_disetujui', 'tanggal_ditolak', 'tanggal_direvisi', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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

    // $jabatans = jabatan yg dipegang sekaligus semua bawahan, array of struktur_jabatan
    // $jabatan_ids = jabatan yg dipegang sekaligus semua bawahan, array of struktur_jabatan_id
    public function searchByJabatan($params, $strukturJabatanIdList)
    {

        $this->load($params);

        if($this->struktur_jabatan != $this->struktur_jabatan_old){
            $this->struktur_jabatan_list = $strukturJabatanIdList;
            $this->struktur_jabatan_old = $this->struktur_jabatan;
        }

        if(empty($this->struktur_jabatan_list)){
            $this->struktur_jabatan_list = -999;
        }

        $query = Program::find()->where(['in', 'inst_struktur_jabatan.struktur_jabatan_id', $this->struktur_jabatan_list]);
        
        $query->joinWith(['strukturJabatanHasMataAnggaran.tahunAnggaran', 'strukturJabatanHasMataAnggaran.mataAnggaran', 'strukturJabatanHasMataAnggaran.strukturJabatan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['kode_program' => SORT_ASC]],
        ]);

        $dataProvider->sort->attributes['tahun_anggaran'] = [
            'asc' => ['rakx_r_tahun_anggaran.tahun' => SORT_ASC],
            'desc' => ['rakx_r_tahun_anggaran.tahun' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['mata_anggaran'] = [
            'asc' => ['rakx_mata_anggaran.name' => SORT_ASC],
            'desc' => ['rakx_mata_anggaran.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['struktur_jabatan'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'program_id' => $this->program_id,
            'rakx_program.struktur_jabatan_has_mata_anggaran_id' => $this->struktur_jabatan_has_mata_anggaran_id,
            'kode_program' => $this->kode_program,
            'rencana_strategis_id' => $this->rencana_strategis_id,
            'rakx_r_tahun_anggaran.tahun_anggaran_id' => $this->tahun_anggaran,
            'rakx_mata_anggaran.mata_anggaran_id' => $this->mata_anggaran,
            'volume' => $this->volume,
            'satuan_id' => $this->satuan_id,
            'status_program_id' => $this->status_program_id,
            'diusulkan_oleh' => $this->diusulkan_oleh,
            'tanggal_diusulkan' => $this->tanggal_diusulkan,
            'dilaksanakan_oleh' => $this->dilaksanakan_oleh,
            'disetujui_oleh' => $this->disetujui_oleh,
            'tanggal_disetujui' => $this->tanggal_disetujui,
            'ditolak_oleh' => $this->ditolak_oleh,
            'tanggal_ditolak' => $this->tanggal_ditolak,
            'is_revisi' => $this->is_revisi,
            'direvisi_oleh' => $this->direvisi_oleh,
            'tanggal_direvisi' => $this->tanggal_direvisi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'rakx_program.name', $this->name])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'sasaran', $this->sasaran])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'harga_satuan', $this->harga_satuan])
            ->andFilterWhere(['like', 'jumlah_sebelum_revisi', $this->jumlah_sebelum_revisi])
            ->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_program.deleted' => 1]])
            ->andWhere(['in', 'inst_struktur_jabatan.struktur_jabatan_id', $this->struktur_jabatan_list]);

        $this->_setTotal($query);

        return $dataProvider;
    }

    public function _setTotal($query){
        $temp = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $this->total = Program::getJumlah($temp->models, 'jumlah');
    }

    public function searchByUsulan($params, $jabatanNotPengusul, $pejabatPengusul)
    {
        $query = Program::find()->where(['in', 'diusulkan_oleh', $pejabatPengusul])->andWhere(['not in', 'inst_struktur_jabatan.struktur_jabatan_id', $jabatanNotPengusul]);
        
        $query->joinWith(['strukturJabatanHasMataAnggaran.tahunAnggaran', 'strukturJabatanHasMataAnggaran.mataAnggaran', 'strukturJabatanHasMataAnggaran.strukturJabatan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['kode_program' => SORT_ASC]],
        ]);

        $dataProvider->sort->attributes['tahun_anggaran'] = [
            'asc' => ['rakx_r_tahun_anggaran.tahun' => SORT_ASC],
            'desc' => ['rakx_r_tahun_anggaran.tahun' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['mata_anggaran'] = [
            'asc' => ['rakx_mata_anggaran.name' => SORT_ASC],
            'desc' => ['rakx_mata_anggaran.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['struktur_jabatan'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'program_id' => $this->program_id,
            'struktur_jabatan_has_mata_anggaran_id' => $this->struktur_jabatan_has_mata_anggaran_id,
            'kode_program' => $this->kode_program,
            'rencana_strategis_id' => $this->rencana_strategis_id,
            'rakx_r_tahun_anggaran.tahun_anggaran_id' => $this->tahun_anggaran,
            'rakx_mata_anggaran.mata_anggaran_id' => $this->mata_anggaran,
            'volume' => $this->volume,
            'satuan_id' => $this->satuan_id,
            'status_program_id' => $this->status_program_id,
            'diusulkan_oleh' => $this->diusulkan_oleh,
            'tanggal_diusulkan' => $this->tanggal_diusulkan,
            'dilaksanakan_oleh' => $this->dilaksanakan_oleh,
            'disetujui_oleh' => $this->disetujui_oleh,
            'tanggal_disetujui' => $this->tanggal_disetujui,
            'ditolak_oleh' => $this->ditolak_oleh,
            'tanggal_ditolak' => $this->tanggal_ditolak,
            'is_revisi' => $this->is_revisi,
            'direvisi_oleh' => $this->direvisi_oleh,
            'tanggal_direvisi' => $this->tanggal_direvisi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['inst_struktur_jabatan.struktur_jabatan_id' => $this->struktur_jabatan]);

        $query->andFilterWhere(['like', 'rakx_program.name', $this->name])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'sasaran', $this->sasaran])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'harga_satuan', $this->harga_satuan])
            ->andFilterWhere(['like', 'jumlah_sebelum_revisi', $this->jumlah_sebelum_revisi])
            ->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_program.deleted' => 1]]);

        $this->_setTotal($query);

        return $dataProvider;
    }

    public function search($params, $strukturJabatanIdList)
    {
        $this->load($params);
        if($this->struktur_jabatan != $this->struktur_jabatan_old){
            $this->struktur_jabatan_list = $strukturJabatanIdList;
            $this->struktur_jabatan_old = $this->struktur_jabatan;
        }
        if(empty($this->struktur_jabatan_list)){
            $this->struktur_jabatan_list = -999;
        }

        $query = Program::find();

        $query->joinWith(['strukturJabatanHasMataAnggaran.tahunAnggaran', 'strukturJabatanHasMataAnggaran.mataAnggaran', 'strukturJabatanHasMataAnggaran.strukturJabatan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['kode_program' => SORT_ASC]],
        ]);

        $dataProvider->sort->attributes['tahun_anggaran'] = [
            'asc' => ['rakx_r_tahun_anggaran.tahun' => SORT_ASC],
            'desc' => ['rakx_r_tahun_anggaran.tahun' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['mata_anggaran'] = [
            'asc' => ['rakx_mata_anggaran.name' => SORT_ASC],
            'desc' => ['rakx_mata_anggaran.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['struktur_jabatan'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'program_id' => $this->program_id,
            'struktur_jabatan_has_mata_anggaran_id' => $this->struktur_jabatan_has_mata_anggaran_id,
            'kode_program' => $this->kode_program,
            'rencana_strategis_id' => $this->rencana_strategis_id,
            'rakx_r_tahun_anggaran.tahun_anggaran_id' => $this->tahun_anggaran,
            'rakx_mata_anggaran.mata_anggaran_id' => $this->mata_anggaran,
            'volume' => $this->volume,
            'satuan_id' => $this->satuan_id,
            'status_program_id' => $this->status_program_id,
            'diusulkan_oleh' => $this->diusulkan_oleh,
            'tanggal_diusulkan' => $this->tanggal_diusulkan,
            'dilaksanakan_oleh' => $this->dilaksanakan_oleh,
            'disetujui_oleh' => $this->disetujui_oleh,
            'tanggal_disetujui' => $this->tanggal_disetujui,
            'ditolak_oleh' => $this->ditolak_oleh,
            'tanggal_ditolak' => $this->tanggal_ditolak,
            'is_revisi' => $this->is_revisi,
            'direvisi_oleh' => $this->direvisi_oleh,
            'tanggal_direvisi' => $this->tanggal_direvisi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'rakx_program.name', $this->name])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'sasaran', $this->sasaran])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'harga_satuan', $this->harga_satuan])
            ->andFilterWhere(['like', 'jumlah_sebelum_revisi', $this->jumlah_sebelum_revisi])
            ->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_program.deleted' => 1]])
            ->andFilterWhere(['in', 'inst_struktur_jabatan.struktur_jabatan_id', $this->struktur_jabatan_list]);

        $this->_setTotal($query);

        return $dataProvider;
    }

    public function searchToExport()
    {
        if(empty($this->struktur_jabatan_list)){
            $this->struktur_jabatan_list = -999;
        }
        if(empty($this->status_program)){
            $this->status_program = -999;
        }

        $query = Program::find();

        $query->joinWith(['strukturJabatanHasMataAnggaran.tahunAnggaran', 'strukturJabatanHasMataAnggaran.mataAnggaran', 'strukturJabatanHasMataAnggaran.strukturJabatan']);

        $query->andFilterWhere([
            'program_id' => $this->program_id,
            'struktur_jabatan_has_mata_anggaran_id' => $this->struktur_jabatan_has_mata_anggaran_id,
            'kode_program' => $this->kode_program,
            'rencana_strategis_id' => $this->rencana_strategis_id,
            'rakx_r_tahun_anggaran.tahun_anggaran_id' => $this->tahun_anggaran,
            'rakx_mata_anggaran.mata_anggaran_id' => $this->mata_anggaran,
            'volume' => $this->volume,
            'satuan_id' => $this->satuan_id,
            //'status_program_id' => $this->status_program_id,
            'diusulkan_oleh' => $this->diusulkan_oleh,
            'tanggal_diusulkan' => $this->tanggal_diusulkan,
            'dilaksanakan_oleh' => $this->dilaksanakan_oleh,
            'disetujui_oleh' => $this->disetujui_oleh,
            'tanggal_disetujui' => $this->tanggal_disetujui,
            'ditolak_oleh' => $this->ditolak_oleh,
            'tanggal_ditolak' => $this->tanggal_ditolak,
            'is_revisi' => $this->is_revisi,
            'direvisi_oleh' => $this->direvisi_oleh,
            'tanggal_direvisi' => $this->tanggal_direvisi,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'rakx_program.name', $this->name])
            ->andFilterWhere(['like', 'tujuan', $this->tujuan])
            ->andFilterWhere(['like', 'sasaran', $this->sasaran])
            ->andFilterWhere(['like', 'target', $this->target])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'harga_satuan', $this->harga_satuan])
            ->andFilterWhere(['like', 'jumlah_sebelum_revisi', $this->jumlah_sebelum_revisi])
            ->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['rakx_program.deleted' => 1]])
            ->andFilterWhere(['in', 'status_program_id', $this->status_program])
            ->andFilterWhere(['in', 'inst_struktur_jabatan.struktur_jabatan_id', $this->struktur_jabatan_list]);

        $this->_setTotal($query);

        return $query->all();
    }
}

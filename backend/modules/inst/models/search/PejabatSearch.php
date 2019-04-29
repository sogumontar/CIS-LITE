<?php

namespace backend\modules\inst\models\search;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use backend\modules\inst\models\Pejabat;
use backend\modules\inst\models\Instansi;

/**
 * PejabatSearch represents the model behind the search form about `backend\modules\inst\models\Pejabat`.
 */
class PejabatSearch extends Pejabat
{
    public $pegawai_nama;
    public $jabatan_nama;
    public $atasan;
    public $instansi;
    public $unit;
    public $nip;
    public $status_expired;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pejabat_id', 'pegawai_id', 'struktur_jabatan_id', 'deleted', 'status_aktif'], 'integer'],
            [['status_expired', 'jabatan_nama', 'pegawai_nama', 'nip', 'atasan', 'instansi', 'unit', 'awal_masa_kerja', 'akhir_masa_kerja', 'no_sk', 'file_sk', 'kode_file', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
        $query = Pejabat::find();

        $query->joinWith(['strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit', 'pegawai']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['jabatan_nama'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['atasan'] = [
            'asc' => ['parent.jabatan' => SORT_ASC],
            'desc' => ['parent.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['instansi'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unit'] = [
            'asc' => ['inst_unit.name' => SORT_ASC],
            'desc' => ['inst_unit.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nip'] = [
            'asc' => ['hrdx_pegawai.nip' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nip' => SORT_DESC],
        ];

        /*$dataProvider->sort->attributes['pegawai_id'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['struktur_jabatan_id'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];*/

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if(is_null($this->instansi)){
            $inst = Instansi::find()->where('deleted != 1')->orderBy(['instansi_id' => SORT_ASC])->one();
            if(!is_null($this->instansi))
                $this->instansi = $inst->inisial;
            //$query->orderBy(['inst_struktur_jabatan.parent' => SORT_ASC]);
        }

        $query->andFilterWhere([
            'pejabat_id' => $this->pejabat_id,
            'pegawai_id' => $this->pegawai_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'awal_masa_kerja' => $this->awal_masa_kerja,
            'akhir_masa_kerja' => $this->akhir_masa_kerja,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'no_sk', $this->no_sk])
            ->andFilterWhere(['like', 'file_sk', $this->file_sk])
            ->andFilterWhere(['like', 'kode_file', $this->kode_file])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.jabatan', $this->jabatan_nama])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'parent.jabatan', $this->atasan])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->instansi])
            ->andFilterWhere(['like', 'hrdx_pegawai.nip', $this->nip])
            ->andFilterWhere(['like', 'inst_unit.name', $this->unit])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_pejabat.deleted', 0]);

        if($this->status_expired == 1){
            $query->andFilterWhere(['<', 'akhir_masa_kerja', date('Y-m-d')])
                    ->andFilterWhere(['like', 'status_aktif', 1]);    
        }else if($this->status_expired == 2){
            $mbf = strtotime(date('Y-m-d') .' +2 months');
            $mbf=date('Y-m-d', $mbf);
            $query->andFilterWhere(['>', 'akhir_masa_kerja', date('Y-m-d')])
                    ->andFilterWhere(['<=', 'akhir_masa_kerja', $mbf])
                    ->andFilterWhere(['like', 'status_aktif', 1]);    
        }else if($this->status_expired == 3){
            $dbf = strtotime(date('Y-m-d') .' +1 days');
            $dbf=date('Y-m-d', $dbf);
            $query->andFilterWhere(['<=', 'awal_masa_kerja', $dbf])
                    ->andFilterWhere(['>', 'akhir_masa_kerja', date('Y-m-d')])
                    ->andFilterWhere(['like', 'status_aktif', 0]);    
        }

        return $dataProvider;
    }

    public function searchByPeriod($period='now', $params)
    {
        if($period == 'now'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit']);
            $query->where('inst_pejabat.deleted != 1');
            $query->andWhere(['status_aktif' => 1]);
            //$query->andWhere(['>=', 'akhir_masa_kerja', date('Y-m-d')]);
            //$query->andWhere(['<=', 'awal_masa_kerja', date('Y-m-d')]);
            $query->orderBy(['awal_masa_kerja' => SORT_DESC]);
        }elseif($period == 'old'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit']);
            $query->where('inst_pejabat.deleted != 1');
            //$query->andWhere(['<', 'akhir_masa_kerja', date('Y-m-d')]);
            $query->andWhere(['status_aktif' => 0]);
            $query->orderBy(['akhir_masa_kerja' => SORT_DESC]);
        }else{
            return false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /*$dataProvider->sort->attributes['atasan'] = [
            'asc' => ['parent.jabatan' => SORT_ASC],
            'desc' => ['parent.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['instansi'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unit'] = [
            'asc' => ['inst_unit.name' => SORT_ASC],
            'desc' => ['inst_unit.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nip'] = [
            'asc' => ['hrdx_pegawai.nip' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nip' => SORT_DESC],
        ];*/

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'pejabat_id' => $this->pejabat_id,
            'pegawai_id' => $this->pegawai_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'awal_masa_kerja' => $this->awal_masa_kerja,
            'akhir_masa_kerja' => $this->akhir_masa_kerja,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'no_sk', $this->no_sk])
            ->andFilterWhere(['like', 'file_sk', $this->file_sk])
            ->andFilterWhere(['like', 'kode_file', $this->kode_file])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.jabatan', $this->atasan])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->instansi])
            ->andFilterWhere(['like', 'inst_unit.name', $this->unit])
            ->andFilterWhere(['like', 'hrdx_pegawai.nip', $this->nip])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_pejabat.deleted', 0]);*/

        return $dataProvider;
    }

    public function searchByPeriodByPegawai($pegawai_id, $period='now', $params)
    {
        if($period == 'now'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit']);
            $query->where(['pegawai_id' => $pegawai_id]);
            $query->andWhere(['status_aktif' => 1]);
            //$query->andWhere(['>=', 'akhir_masa_kerja', date('Y-m-d')]);
            //$query->andWhere(['<=', 'awal_masa_kerja', date('Y-m-d')]);
            $query->andWhere('inst_pejabat.deleted != 1');
            $query->orderBy(['inst_struktur_jabatan.instansi_id' => SORT_ASC, 'awal_masa_kerja' => SORT_DESC, 'parent.jabatan' => SORT_ASC]);
        }elseif($period == 'old'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit']);
            $query->where(['pegawai_id' => $pegawai_id]);
            $query->andWhere(['status_aktif' => 0]);
            $query->andWhere(['<', 'akhir_masa_kerja', date('Y-m-d')]);
            $query->andWhere('inst_pejabat.deleted != 1');
            $query->orderBy(['inst_struktur_jabatan.instansi_id' => SORT_ASC, 'akhir_masa_kerja' => SORT_DESC, 'parent.jabatan' => SORT_ASC]);
        }else{
            return false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['jabatan_nama'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['atasan'] = [
            'asc' => ['parent.jabatan' => SORT_ASC],
            'desc' => ['parent.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['instansi'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unit'] = [
            'asc' => ['inst_unit.name' => SORT_ASC],
            'desc' => ['inst_unit.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nip'] = [
            'asc' => ['hrdx_pegawai.nip' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nip' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pejabat_id' => $this->pejabat_id,
            'pegawai_id' => $this->pegawai_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'awal_masa_kerja' => $this->awal_masa_kerja,
            'akhir_masa_kerja' => $this->akhir_masa_kerja,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'no_sk', $this->no_sk])
            ->andFilterWhere(['like', 'file_sk', $this->file_sk])
            ->andFilterWhere(['like', 'kode_file', $this->kode_file])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.jabatan', $this->jabatan_nama])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'parent.jabatan', $this->atasan])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->instansi])
            ->andFilterWhere(['like', 'inst_unit.name', $this->unit])
            ->andFilterWhere(['like', 'hrdx_pegawai.nip', $this->nip])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_pejabat.deleted', 0]);

        return $dataProvider;
    }

    public function searchByPeriodByJabatan($jabatan_id, $period='now', $params)
    {
        if($period == 'now'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit', 'pegawai']);
            $query->where(['inst_pejabat.struktur_jabatan_id' => $jabatan_id]);
            $query->andWhere(['status_aktif' => 1]);
            //$query->andWhere(['>=', 'akhir_masa_kerja', date('Y-m-d')]);
            //$query->andWhere(['<=', 'awal_masa_kerja', date('Y-m-d')]);
            $query->andWhere('inst_pejabat.deleted != 1');
            $query->orderBy(['awal_masa_kerja' => SORT_DESC, 'hrdx_pegawai.nama' => SORT_ASC]);
        }elseif($period == 'old'){
            $query = Pejabat::find();
            $query->joinWith(['strukturJabatan', 'strukturJabatan.parent0', 'strukturJabatan.instansi', 'strukturJabatan.unit', 'pegawai']);
            $query->where(['inst_pejabat.struktur_jabatan_id' => $jabatan_id]);
            $query->andWhere(['status_aktif' => 0]);
            $query->andWhere(['<', 'akhir_masa_kerja', date('Y-m-d')]);
            $query->andWhere('inst_pejabat.deleted != 1');
            $query->orderBy(['akhir_masa_kerja' => SORT_DESC, 'hrdx_pegawai.nama' => SORT_ASC]);
        }else{
            return false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['jabatan_nama'] = [
            'asc' => ['inst_struktur_jabatan.jabatan' => SORT_ASC],
            'desc' => ['inst_struktur_jabatan.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['atasan'] = [
        // The tables are the ones our relation are configured to
        // in my case they are prefixed with "tbl_"
            'asc' => ['parent.jabatan' => SORT_ASC],
            'desc' => ['parent.jabatan' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['instansi'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unit'] = [
            'asc' => ['inst_unit.name' => SORT_ASC],
            'desc' => ['inst_unit.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nip'] = [
            'asc' => ['hrdx_pegawai.nip' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nip' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pejabat_id' => $this->pejabat_id,
            'pegawai_id' => $this->pegawai_id,
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'awal_masa_kerja' => $this->awal_masa_kerja,
            'akhir_masa_kerja' => $this->akhir_masa_kerja,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'no_sk', $this->no_sk])
            ->andFilterWhere(['like', 'file_sk', $this->file_sk])
            ->andFilterWhere(['like', 'kode_file', $this->kode_file])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.jabatan', $this->jabatan_nama])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'parent.jabatan', $this->atasan])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->instansi])
            ->andFilterWhere(['like', 'inst_unit.name', $this->unit])
            ->andFilterWhere(['like', 'hrdx_pegawai.nip', $this->nip])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_pejabat.deleted', 0]);

        return $dataProvider;
    }
}

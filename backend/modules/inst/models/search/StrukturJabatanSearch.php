<?php

namespace backend\modules\inst\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\inst\models\StrukturJabatan;
use backend\modules\inst\models\Instansi;
use backend\modules\inst\models\Unit;

/**
 * StrukturJabatanSearch represents the model behind the search form about `backend\modules\inst\models\StrukturJabatan`.
 */
class StrukturJabatanSearch extends StrukturJabatan
{
    public $inisial_inst;
    public $instansi_name;
    public $unit_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['struktur_jabatan_id', 'instansi_id', 'parent', 'is_multi_tenant', 'mata_anggaran', 'laporan', 'unit_id', 'deleted'], 'integer'],
            [['instansi_name', 'unit_name', 'inisial_inst', 'jabatan', 'inisial', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
        $query = StrukturJabatan::find();

        $query->joinWith(['instansi']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['inisial_inst'] = [
            'asc' => ['inst_instansi.inisial' => SORT_ASC],
            'desc' => ['inst_instansi.inisial' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(is_null($this->inisial_inst)){
            $inst = Instansi::find()->where('deleted != 1')->orderBy(['instansi_id' => SORT_ASC])->one();
            $this->inisial_inst = $inst->inisial;
        }

        $query->andFilterWhere([
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'instansi_id' => $this->instansi_id,
            'parent' => $this->parent,
            'is_multi_tenant' => $this->is_multi_tenant,
            'mata_anggaran' => $this->mata_anggaran,
            'laporan' => $this->laporan,
            'unit_id' => $this->unit_id,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'inisial', $this->inisial])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->inisial_inst])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.deleted', 0]);

        $query->orderBy(['parent' => SORT_ASC, 'jabatan' => SORT_ASC]);

        return $dataProvider;
    }

    public function searchForHistoryView($params)
    {
        $query = StrukturJabatan::find();

        $query->joinWith(['instansi', 'unit']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['instansi_name'] = [
            'asc' => ['inst_instansi.name' => SORT_ASC],
            'desc' => ['inst_instansi.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['unit_name'] = [
            'asc' => ['inst_unit.name' => SORT_ASC],
            'desc' => ['inst_unit.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'instansi_id' => $this->instansi_id,
            'parent' => $this->parent,
            'is_multi_tenant' => $this->is_multi_tenant,
            'mata_anggaran' => $this->mata_anggaran,
            'laporan' => $this->laporan,
            'unit_id' => $this->unit_id,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'inisial', $this->inisial])
            ->andFilterWhere(['like', 'inst_instansi.inisial', $this->inisial_inst])
            ->andFilterWhere(['like', 'inst_instansi.name', $this->instansi_name])
            ->andFilterWhere(['like', 'inst_unit.name', $this->unit_name])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.deleted', 0]);

        return $dataProvider;
    }

    public function searchByInstansi($instansi, $params)
    {
        $query = StrukturJabatan::find();
        $query->where(['instansi_id' => $instansi]);
        $query->andWhere('inst_struktur_jabatan.deleted != 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /*if (!$this->validate()) {
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'instansi_id' => $this->instansi_id,
            'parent' => $this->parent,
            'is_multi_tenant' => $this->is_multi_tenant,
            'mata_anggaran' => $this->mata_anggaran,
            'laporan' => $this->laporan,
            'unit_id' => $this->unit_id,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'inisial', $this->inisial])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.deleted', 0]);

        $query->orderBy(['jabatan' => SORT_ASC]);

        return $dataProvider;
    }

    //untuk UnitMemberAdd
    public function searchByInstansiByNoUnit($instansi, $unit, $params)
    {
        //tidak boleh kepala unit jadi member unit lain, kepala suatu unit otomatis menjadi member unit itu
        $kepala = Unit::find()->where(['unit_id' => $unit])->andWhere('deleted != 1')->one();

        $query = StrukturJabatan::find();
        $query->where(['instansi_id' => $instansi, 'unit_id' => $unit]);
        $query->andWhere(['not', ['struktur_jabatan_id' => $kepala->kepala]]);
        $query->orWhere(['instansi_id' => $instansi, 'unit_id' => null]);
        $query->andWhere('inst_struktur_jabatan.deleted != 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /*if (!$this->validate()) {
            return $dataProvider;
        }*/

        $query->andFilterWhere([
            'struktur_jabatan_id' => $this->struktur_jabatan_id,
            'instansi_id' => $this->instansi_id,
            'parent' => $this->parent,
            'is_multi_tenant' => $this->is_multi_tenant,
            'mata_anggaran' => $this->mata_anggaran,
            'laporan' => $this->laporan,
            'unit_id' => $this->unit_id,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'jabatan', $this->jabatan])
            ->andFilterWhere(['like', 'inisial', $this->inisial])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'inst_struktur_jabatan.deleted', 0]);

        $query->orderBy(['jabatan' => SORT_ASC]);

        return $dataProvider;
    }
}

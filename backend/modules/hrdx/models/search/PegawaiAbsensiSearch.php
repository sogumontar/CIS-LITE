<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\PegawaiAbsensi;

/**
 * PegawaiAbsensiSearch represents the model behind the search form about `backend\modules\hrdx\models\PegawaiAbsensi`.
 */
class PegawaiAbsensiSearch extends PegawaiAbsensi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_absensi_id', 'pegawai_id', 'jenis_absen_id', 'jumlah_hari', 'approval_1', 'approval_2','status_approval_1', 'status_approval_2', 'deleted'], 'integer'],
            [['dari_tanggal','sampai_tanggal','alasan', 'pengalihan_tugas', 'deleted_by', 'deleted_at', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        
        $query = PegawaiAbsensi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pegawai_absensi_id' => $this->pegawai_absensi_id,
            'pegawai_id' => $this->pegawai_id,
            'jenis_absen_id' => $this->jenis_absen_id,
            'jumlah_hari' => $this->jumlah_hari,
            'approval_1' => $this->approval_1,
            'approval_2' => $this->approval_2,
            'dari_tanggal' => $this->dari_tanggal,
            'sampai_tanggal' => $this->sampai_tanggal,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function searchByStatusApproval($pegawai_id, $idStatus, $params)
    {
        $query = PegawaiAbsensi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->where(['approval_1'=> $pegawai_id])
              ->andWhere(['status_approval_1' => $idStatus])
              ->orWhere(['approval_2' => $pegawai_id,'status_approval_2' => $idStatus])
              ->andWhere(['deleted' =>0]);

        $query->andFilterWhere([
            'pegawai_absensi_id' => $this->pegawai_absensi_id,
            'pegawai_id' => $this->pegawai_id,
            'jenis_absen_id' => $this->jenis_absen_id,
            'jumlah_hari' => $this->jumlah_hari,
            'approval_1' => $this->approval_1,
            'approval_2' => $this->approval_2,
            'dari_tanggal' => $this->dari_tanggal,
            'sampai_tanggal' => $this->sampai_tanggal,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function searchById($id, $params)
    {
        $query = PegawaiAbsensi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->where(['pegawai_absensi_id'=> $id])
              ->andWhere(['deleted' => 0]);

        $query->andFilterWhere([
            'pegawai_absensi_id' => $this->pegawai_absensi_id,
            'pegawai_id' => $this->pegawai_id,
            'jenis_absen_id' => $this->jenis_absen_id,
            'jumlah_hari' => $this->jumlah_hari,
            'approval_1' => $this->approval_1,
            'approval_2' => $this->approval_2,
            'dari_tanggal' => $this->dari_tanggal,
            'sampai_tanggal' => $this->sampai_tanggal,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function searchByJenisAbsen($jenis_absen, $pegawai_id, $params)
    {
        $query = PegawaiAbsensi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->join('inner join', 'hrdx_jenis_absen', 'hrdx_pegawai_absensi.jenis_absen_id=hrdx_jenis_absen.jenis_absen_id');

        $query->where(['like','nama',$jenis_absen])
              ->andWhere(['hrdx_pegawai_absensi.pegawai_id' => $pegawai_id])
              ->andWhere(['hrdx_pegawai_absensi.deleted' => 0]);

        $query->andFilterWhere([
            'pegawai_absensi_id' => $this->pegawai_absensi_id,
            'pegawai_id' => $this->pegawai_id,
            'jenis_absen_id' => $this->jenis_absen_id,
            'jumlah_hari' => $this->jumlah_hari,
            'approval_1' => $this->approval_1,
            'approval_2' => $this->approval_2,
            'dari_tanggal' => $this->dari_tanggal,
            'sampai_tanggal' => $this->sampai_tanggal,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'alasan', $this->alasan])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->orderBy(['status_approval_1' => SORT_ASC, 'status_approval_2'=>SORT_ASC]);

        return $dataProvider;
    }
}

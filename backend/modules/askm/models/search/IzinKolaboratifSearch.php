<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\IzinKolaboratif;

/**
 * IzinKolaboratifSearch represents the model behind the search form about `backend\modules\askm\models\IzinKolaboratif`.
 */
class IzinKolaboratifSearch extends IzinKolaboratif
{
    public $dim_nama;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['izin_kolaboratif_id', 'dim_id', 'status_request_id', 'baak_id', 'deleted'], 'integer'],
            [['dim_nama', 'rencana_mulai', 'rencana_berakhir', 'batas_waktu', 'desc', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = IzinKolaboratif::find();
        $query->joinWith(['dim']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC,
                'status_request_id' => SORT_ASC,
            ]],

        ]);

        $dataProvider->sort->attributes['dim_nama'] = [
            'asc' => ['dimx_dim.nama' => SORT_ASC],
            'desc' => ['dimx_dim.nama' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izin_kolaboratif_id' => $this->izin_kolaboratif_id,
            'rencana_mulai' => $this->rencana_mulai,
            'rencana_berakhir' => $this->rencana_berakhir,
            'batas_waktu' => $this->batas_waktu,
            'dim_id' => $this->dim_id,
            'status_request_id' => $this->status_request_id,
            'baak_id' => $this->baak_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'dimx_dim.nama', $this->dim_nama])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_kolaboratif.deleted' => 1]]);

        return $dataProvider;
    }

    public function searchByMahasiswa($params)
    {
        $query = IzinKolaboratif::find();
        $query->joinWith(['dim']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => ['defaultOrder' => [
                'created_at' => SORT_DESC,
                'status_request_id' => SORT_ASC,
            ]],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izin_kolaboratif_id' => $this->izin_kolaboratif_id,
            'rencana_mulai' => $this->rencana_mulai,
            'rencana_berakhir' => $this->rencana_berakhir,
            'batas_waktu' => $this->batas_waktu,
            'dimx_dim.user_id' => Yii::$app->user->identity->user_id,
            'status_request_id' => $this->status_request_id,
            'baak_id' => $this->baak_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'dimx_dim.status_akhir', 'Aktif'])
            ->andFilterWhere(['not', ['askm_izin_kolaboratif.deleted' => 1]]);

        return $dataProvider;
    }
}

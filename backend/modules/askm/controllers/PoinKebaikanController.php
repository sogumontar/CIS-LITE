<?php

namespace backend\modules\askm\controllers;

use Yii;
use backend\modules\askm\models\PoinKebaikan;
use backend\modules\askm\models\DimPenilaian;
use backend\modules\askm\models\DimPelanggaran;
use backend\modules\askm\models\search\PoinKebaikanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoinKebaikanController implements the CRUD actions for PoinKebaikan model.
  * controller-id: poin-kebaikan
 * controller-desc: Controller untuk me-manage data poin kebaikan
 */
class PoinKebaikanController extends Controller
{
    public function behaviors()
    {
        return [
            //TODO: crud controller actions are bypassed by default, set the appropriate privilege
            'privilege' => [
                 'class' => \Yii::$app->privilegeControl->getAppPrivilegeControlClass(),
                 'skipActions' => [],
                ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PoinKebaikan models.
     * @return mixed
     */
    /*
    * action-id: index
    * action-desc: Display All Data
    */
    public function actionIndex()
    {
        $searchModel = new PoinKebaikanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists single PoinKebaikan models.
     * @return mixed
     */
    /*
    * action-id: view
    * action-desc: Display Single Data
    */
    public function actionView($id, $penilaian_id)
    {
        $dim = DimPenilaian::find()->where('deleted!=1')->andWhere(['penilaian_id' => $penilaian_id])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dim' => $dim->dim->nama,
        ]);
    }

    /**
     * Add new PoinKebaikan models.
     * @return mixed
     */
    /*
    * action-id: add
    * action-desc: Add Single Data
    */
    public function actionAdd($penilaian_id)
    {

        $model = new PoinKebaikan();
        $dim = DimPenilaian::find()->where('deleted!=1')->andWhere(['penilaian_id' => $penilaian_id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $pelanggaran = DimPelanggaran::find()->where('deleted!=1')->andWhere(['penilaian_id' => $penilaian_id])->andWhere(['pelanggaran_id' => $model->pelanggaran_id])->one();
            $model->penilaian_id  = $penilaian_id;

            if ($model->pelanggaran_id != NULL) {
                $new_skor = $dim->akumulasi_skor - $pelanggaran->poin->poin;
                if ($new_skor < 0) {
                    $dim->akumulasi_skor = 0;
                } else {
                    $dim->akumulasi_skor = $new_skor;
                }
                $pelanggaran->status_pelanggaran = 1;
                $dim->save();
                $pelanggaran->save();
                $model->save();
                return $this->redirect(['dim-penilaian/view', 'id' => $penilaian_id, 'penilaian_id' => $penilaian_id]);
            }

            if ($model->save()) {
                return $this->redirect(['dim-penilaian/view', 'id' => $penilaian_id, 'penilaian_id' => $penilaian_id]);
            } else {
                return $this->render('add', [
                    'model' => $model,
                    'dim' => $dim->dim->nama,
                ]);
            }
        } else {
            return $this->render('add', [
                'model' => $model,
                'dim' => $dim->dim->nama,
            ]);
        }
    }

    /**
     * Edit existing PoinKebaikan models.
     * @return mixed
     */
    /*
    * action-id: edit
    * action-desc: Edit single existing data
    */
    public function actionEdit($id, $penilaian_id)
    {
        $model = $this->findModel($id);
        $dim = DimPenilaian::find()->where('deleted!=1')->andWhere(['penilaian_id' => $penilaian_id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $pelanggaran = DimPelanggaran::find()->where('deleted!=1')->andWhere(['penilaian_id' => $penilaian_id])->andWhere(['pelanggaran_id' => $model->pelanggaran_id])->one();
            $model->penilaian_id  = $id;

            if ($model->pelanggaran_id != NULL) {
                $new_skor = $dim->akumulasi_skor - $pelanggaran->poin->poin;
                $pelanggaran->status_pelanggaran = 1;
                $dim->akumulasi_skor = $new_skor;
                $dim->save();
                $pelanggaran->save();
                $model->save();
                return $this->redirect(['dim-penilaian/view', 'id' => $penilaian_id, 'penilaian_id' => $penilaian_id]);
            }

            if ($model->save()) {
                return $this->redirect(['dim-penilaian/view', 'id' => $penilaian_id, 'penilaian_id' => $penilaian_id]);
            } else {
                return $this->render('edit', [
                    'model' => $model,
                    'dim' => $dim->dim->nama,
                ]);
            }
        } else {
            return $this->render('edit', [
                'model' => $model,
                'dim' => $dim->dim->nama,
            ]);
        }
    }

    /**
     * Deletes an existing PoinKebaikan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /*
    * action-id: del
    * action-desc: Menghapus Kebaikan
    */
    public function actionDel($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PoinKebaikan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PoinKebaikan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoinKebaikan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

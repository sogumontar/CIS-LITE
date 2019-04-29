<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\admin\models\search\AuthenticationMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authentication Methods';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authentication-method-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Authentication Method', ['auth-method-add'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'authentication_method_id',
            'name',
            'server_address',
            'authentication_string',
            'redirected',
            'redirect_to',
            'desc',

            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'common\components\ToolsColumn',
             'template' => '{edit} {del}',
             'urlCreator' => function ($action, $model, $key, $index) {
                if($action === 'view'){
                    return Url::toRoute(['user/auth-method-view', 'id' => $key]);
                }elseif ($action === 'edit') {
                    return Url::toRoute(['user/auth-method-edit', 'id' => $key]);
                }elseif ($action === 'del') {
                    return Url::toRoute(['user/auth-method-del', 'id' => $key]);
                }
             }
            ],
        ],
    ]); ?>

</div>
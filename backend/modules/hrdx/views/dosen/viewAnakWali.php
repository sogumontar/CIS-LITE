<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\DetailView;
use common\helpers\LinkHelper;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Dosen */

$this->title = 'List Anak Wali';
$this->params['breadcrumbs'][] = ['label' => 'Dosen Wali', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title .' Tahun Ajaran '. $ta .' Sem '. $sem_ta;


$uiHelper=\Yii::$app->uiHelper;
?>
<div class="assign-anak-wali">

    <?= $uiHelper->renderContentSubHeader('Data Dosen Wali', ['icon' => 'fa fa-user']);?>
    <?=$uiHelper->renderLine(); ?>	
	    <div class="pull-right">
	        <?php
	            echo $uiHelper->renderButtonSet([
	                'template' => ['history'],
	                'buttons' => [
	                    'history' => [
	                        'url' => Url::to(['/adak/dosen-wali/history-anak-wali', 'dosen_wali_id' => $modelPegawai->pegawai_id]),
	                        'label' => 'History Anak Wali',
	                        'icon' => 'fa fa-list'
	                    ]
	                ]
	            ]);
	        ?>
	    </div>
	    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-menu-kelas',
	            // 'width' => 4,
	            // 'icon' => 'fa fa-user',
	            // 'header' => 'Data Dosen Wali',
	        ]) ?>
	   
	    <?= DetailView::widget([
	        'model' => $modelPegawai,
	        'attributes' => [
	            //'asal_sekolah_id',
	            'nama',
	            isset($modelPegawai->nip)?'nip':'',
	            // 'kabupaten_id',
	            // 'kodepos',
	            // 'telepon',
	            // 'email:email',
	            // 'deleted',
	            // 'created_by',
	            // 'created_at',
	            // 'updated_by',
	            // 'updated_at',
	        ],
	    ]) ?>   
	    <?=$uiHelper->endSingleRowBlock()?>



	    <?= $uiHelper->renderContentSubHeader('Data Mahasiswa', ['icon' => 'fa fa-users']);?>
	    <?= $uiHelper->renderLine(); ?>
	    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-menu-kelas',
	            // 'width' => 4,
	            // 'icon' => 'fa fa-users',
	            // 'header' => 'Data Mahasiswa',
	        ]) ?>
	    <div>
	        <p>
	            <?php 
	                echo "List Mahasiswa anak wali.";
	            ?>
	        </p>
	    </div>
	    <?php 
		    echo GridView::widget([
		        'dataProvider' => $dataProvider,
		        // 'filterModel' => $searchModel,
		        'columns' => [
		            ['class' => 'yii\grid\SerialColumn'],

		            // 'registrasi_id',
		            [
		                'header' => '<a href="#">Nama</a>',
		                'attribute' => 'nama',
		                'format' => 'html',
		                'value' => function($data){
		                    return LinkHelper::renderLink([
		                        'label' => $data->dim->nama, 
		                        'url' => Url::to(['/prkl/krs-mhs/view-by-dim', 'id' => $data->dim_id]),
		                    ]);
		                },
		            ],
		            [
		                'label' => 'Nim',
		                'attribute' => 'nim',
		                'contentOptions' => ['class' => 'col-xs-2']
		            ],
		           	[
		                'label' => 'TA',
		                'attribute' => 'ta',
		                'filter' => ArrayHelper::map($listThnAjaran, 'ta', 'ta'),
		                // 'filterInputOptions' => ['class' => 'form-control', 'readOnly' => true],
		                'contentOptions' => ['class' => 'col-xs-2']
		            ],
		            [
		                'label' => 'Semester TA',
		                'attribute' => 'sem_ta',
		                'filter' => ArrayHelper::map($listSemTa, 'sem_ta', 'sem_ta'),
		                // 'filterInputOptions' => ['class' => 'form-control', 'readOnly' => true],
		                'contentOptions' => ['class' => 'col-xs-2']
		            ],
		            [
		                'label' => 'Semester',
		                'attribute' => 'sem',
		                'filter' => ArrayHelper::map($listSem, 'sem', 'sem'),
		                'contentOptions' => ['class' => 'col-xs-1']
		            ],
		        ],
		    ]); 
		    ?>
		    <?=$uiHelper->endSingleRowBlock()?>
</div>

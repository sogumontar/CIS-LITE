<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\JqueryTreegridAsset;
use yii\widgets\Pjax;

JqueryTreegridAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Import Structure Definition';
$this->params['breadcrumbs'][] = ['label' => 'Institusi Manager', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>

<div class="user-create">

    <div class="user-form">

	    <?php $form = ActiveForm::begin([
	    		'layout' => 'horizontal',
			    'id' => 'upload-form',
			    'options' => ['enctype' => 'multipart/form-data'],
	    ]); ?>
	    <?= $form->field($model, 'file')->fileInput()?>
		<div class="form-group field-userform-autoactive">
			<div class="col-sm-6 col-sm-offset-3">
					<?= Html::submitButton('Upload Definition File', ['class' => 'btn btn-primary']) ?>
			</div>

		</div>

	    <?php ActiveForm::end(); ?>

	</div>
	<?php if ($structure): ?>
			<?= $uiHelper->renderContentSubHeader('Structure Import Status') ?>
			<table class="tree table">
				<thead>
					<tr>
				      <th class="col-md-7">Jabatan</th>
				      <th class="col-md-3">Is Multi Tenant</th>
				      <th class="col-md-1">Status</th>
				      <th class="col-md-3">Message</th>
				    </tr>
				</thead>
				<tbody>
		      
		      <?php
		        
		        function recursiveStrukturJabatan($structure, $parent)
		        {
		          ?>
		            <tr class="treegrid-<?=$structure['struktur_jabatan_id'] ?> treegrid-parent-<?=$parent ?>">
				        <td><?=$structure['jabatan'] ?></td>
				        <td><?=$structure['is_multi_tenant'] ?></td>
				        <td>
				        	<?=$structure['isExisted']?'<i class="fa fa-ban text-red"></i>': '<i class="fa fa-check-circle text-green"></i>'; ?>
				        </td>
				        <td><?=$structure['msg'] ?></td>
				    </tr>
		          <?php
		          foreach ($structure['strukturJabatans'] as $s) {
		      		recursiveStrukturJabatan($s, $structure['struktur_jabatan_id']);
		        	}
		        }

		        foreach ($structure as $s) {
		      		recursiveStrukturJabatan($s, $structure);
		      }

		    ?>
		  </tbody>
		</table>
	<?php endif ?>
</div>

<?php 
	//$this->registerJs('alert("hi");', View::POS_END);
  	$this->registerJs(
  		'$(".tree").treegrid({
          		expanderExpandedClass: "fa fa-caret-down",
          		expanderCollapsedClass: "fa fa-caret-right",
          		initialState: "expanded"
        	});', 
    View::POS_END);

<?php

use yii\helpers\Html;
use yii\web\View;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\modules\rakx\models\Program;
use backend\assets\JqueryTreegridAsset;

JqueryTreegridAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\ProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Export';
$this->params['breadcrumbs'][] = ['label' => 'Kompilasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$uiHelper = \Yii::$app->uiHelper;

?>

<div class="program-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'post',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'wrapper' => 'col-sm-8',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>

    <?=$uiHelper->beginContentRow() ?>

        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1', 'width' => 6, ]) ?>
                <?= $form->field($model, 'tahun_anggaran', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]])->label("Tahun Anggaran")->dropDownList(
                    ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'),
                        ['options' => [$tahun_anggaran[0]->tahun_anggaran_id => ['Selected'=>'selected']],
                        'onchange' => 'this.form.submit()'])
                ?>
                <?= $form->field($model, 'status_program')->checkboxList(ArrayHelper::map($status_program, 'status_program_id', 'name'))->label('Status Program') ?>
        <?=$uiHelper->endContentBlock()?>

        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1', 'width' => 6]) ?>
                    <?php //$form->field($searchModel, 'struktur_jabatan_list')->label('Struktur')->checkboxList(ArrayHelper::map($struktur_jabatan_list, 'struktur_jabatan_id', 'jabatan')) ?>
                    <div style="height:auto; width:auto; padding-left:10px; padding-bottom:10px; margin-bottom:10px; margin-right:15px; border-style:solid;border-width:1px;border-color:rgba(0,0,0,0.09);background-color:rgba(0,0,0,0.03);-webkit-border-radius:5px;-moz-border-radius:5px; border-radius:5px;">
                    <label class="control-label" for="programsearch-struktur_jabatan_list">Struktur</label>
                    <table class="tree">
                    <?= $form->field($model, 'struktur_jabatan_list')->label(false)->checkboxList(ArrayHelper::map($struktur_jabatan_list, 'struktur_jabatan_id', 'jabatan'), [
                            'item' => function($index, $label, $name, $checked, $value) use($model, $struktur_jabatan_list){
                                if($index==0){
                                    if(is_array($model['struktur_jabatan_list'])){
                                        return '<tr id="node-'.$value.'" class="treegrid-'.$value.' treegrid-parent-" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$value.'" '.(in_array($value, $model['struktur_jabatan_list'])?"checked" : "").' />
                                                         '.$label.'
                                                    </td>
                                                </tr>';
                                    }else {
                                        return '<tr id="node-'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].'" class="treegrid-'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].' treegrid-parent-" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].'" />
                                                         '.$struktur_jabatan_list[$index]['jabatan'].'
                                                    </td>
                                            </tr>';
                                    }
                                }else{
                                    if(is_array($model['struktur_jabatan_list'])){
                                        return '<tr id="node-'.$value.'" class="treegrid-'.$value.' treegrid-parent-'.$struktur_jabatan_list[$index]->parent.'" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$value.'" '.(in_array($value, $model['struktur_jabatan_list'])?"checked" : "").' />
                                                         '.$label.'
                                                    </td>
                                                </tr>';
                                    }else {
                                        return '<tr id="node-'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].'" class="treegrid-'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].' treegrid-parent-'.$struktur_jabatan_list[$index]['parent'].'" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$struktur_jabatan_list[$index]['struktur_jabatan_id'].'" />
                                                         '.$struktur_jabatan_list[$index]['jabatan'].'
                                                    </td>
                                            </tr>';
                                    }
                                }
                            }
                        ])
                    ?>
                </table>
            </div>
        <?=$uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-1">
            <?= Html::submitButton('Export', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
  $this->registerJs("
    $(document).ready(function() {
        $('.tree').treegrid({
          expanderExpandedClass: 'fa fa-caret-down',
          expanderCollapsedClass: 'fa fa-caret-right',
          initialState: 'expand',
          saveState: 'true'
        });
    });
  ", 
    View::POS_END);
?>
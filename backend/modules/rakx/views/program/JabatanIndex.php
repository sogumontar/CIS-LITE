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
use backend\modules\rakx\assets\RakxAsset;

$rakxAsset = RakxAsset::register($this);

JqueryTreegridAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\ProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Program';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Program';
$this->params['layout'] = 'full';

$uiHelper = \Yii::$app->uiHelper;

?>

<div class="program-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'post',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-md-4',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-0',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>

    <?=$uiHelper->beginContentRow() ?>

        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1', 'width' => 6, ]) ?>
                <?= $form->field($searchModel, 'tahun_anggaran', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]])->label("Tahun Anggaran")->dropDownList(
                    ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'),
                        ['options' => [$tahun_anggaran[0]->tahun_anggaran_id => ['Selected'=>'selected']],
                        'onchange' => 'this.form.submit()'])
                ?>                 
                <?= $form->field($searchModel, 'struktur_jabatan', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]])->label('Jabatan')->dropDownList(
                         ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),
                         ['options' => [$struktur_jabatan[0]->struktur_jabatan_id => ['Selected'=>'selected']],
                          'onchange' => 'this.form.submit()'])
                ?>
                <?= $form->field($searchModel, 'mata_anggaran', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]])->label('Mata Anggaran')->dropDownList(
                         ArrayHelper::map($mata_anggaran, 'mata_anggaran_id', function($mata_anggaran){ return $mata_anggaran->kode_anggaran.' '.$mata_anggaran->name;}),
                         ['prompt' => "ALL",
                          'onchange' => 'this.form.submit()'])
                ?>
                <?= $form->field($searchModel, 'struktur_jabatan_has_mata_anggaran_id', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]])->label('Penugasan Anggaran')->dropDownList(
                         ArrayHelper::map($penugasan_anggaran, 'struktur_jabatan_has_mata_anggaran_id', function($penugasan_anggaran){ return $penugasan_anggaran->strukturJabatan->jabatan.' - '.$penugasan_anggaran->mataAnggaran->kode_anggaran.' '.$penugasan_anggaran->mataAnggaran->name; }),
                         ['prompt' => "ALL",
                          'onchange' => 'this.form.submit()'])
                ?>
                <?= $form->field($searchModel, 'status_program_id', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-6',]])->label('Status Program')->dropDownList(
                         ArrayHelper::map($status_program, 'status_program_id', 'name'),
                         ['prompt' => "ALL",
                          'onchange' => 'this.form.submit()'])
                ?>
                <?= $form->field($searchModel, 'struktur_jabatan_old')->hiddenInput()->label(false) ?>
        <?=$uiHelper->endContentBlock()?>

        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1', 'width' => 6]) ?>
                    <?php //$form->field($searchModel, 'struktur_jabatan_list')->label('Struktur')->checkboxList(ArrayHelper::map($struktur_jabatan_list, 'struktur_jabatan_id', 'jabatan')) ?>
                    <div style="height:168px; width:auto; overflow-x:auto ; overflow-y: auto; padding-left:10px; padding-bottom:10px; margin-bottom:10px; margin-right:15px; border-style:solid;border-width:1px;border-color:rgba(0,0,0,0.09);background-color:rgba(0,0,0,0.03);-webkit-border-radius:5px;-moz-border-radius:5px; border-radius:5px;">
                    <label class="control-label" for="programsearch-struktur_jabatan_list">Struktur</label>
                    <table class="tree">
                    <?= $form->field($searchModel, 'struktur_jabatan_list')->label(false)->checkboxList(ArrayHelper::map($struktur_jabatan_list, 'struktur_jabatan_id', 'jabatan'), [
                            'item' => function($index, $label, $name, $checked, $value) use($searchModel, $struktur_jabatan_list){
                                if($index==0){
                                    if(is_array($searchModel['struktur_jabatan_list'])){
                                        return '<tr id="node-'.$value.'" class="treegrid-'.$value.' treegrid-parent-" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$value.'" '.(in_array($value, $searchModel['struktur_jabatan_list'])?"checked" : "").' />
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
                                    if(is_array($searchModel['struktur_jabatan_list'])){
                                        return '<tr id="node-'.$value.'" class="treegrid-'.$value.' treegrid-parent-'.$struktur_jabatan_list[$index]->parent.'" >
                                                    <td>
                                                        <input type="checkbox" name="'.$name.'" value="'.$value.'" '.(in_array($value, $searchModel['struktur_jabatan_list'])?"checked" : "").' />
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
                    <div class="form-group" style="margin-left: 0px;">
                            <?= Html::submitButton('Browse', ['class' => 'btn btn-primary'], []); ?>
                    </div>
        <?=$uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

<?=$uiHelper->renderContentSubHeader(' Total: <b>'.$searchModel->total.'</b>') ?>
<?=$uiHelper->renderLine() ?>

<div class="program-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showFooter' => true,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'struktur_jabatan',
                'value' => 'strukturJabatanHasMataAnggaran.strukturJabatan.jabatan',
                'label' => 'Jabatan',
                'enableSorting' => false
            ],
            [
                'attribute' => 'mata_anggaran',
                'value' => 'strukturJabatanHasMataAnggaran.mataAnggaran.name',
                'label' => 'Mata Anggaran',
                'enableSorting' => false
            ],
            [
                'attribute' => 'kode_program',
                'enableSorting' => false
            ],
            [
                'attribute' => 'name',
                'footer' => '<div style="text-align:right;font-weight:bold;">Total</div>',
                'enableSorting' => false
            ],
            [
                'attribute' => 'diusulkan_oleh',
                'value' => function($model){
                    if($model->diusulkanOleh->struktur_jabatan_id != $model->strukturJabatanHasMataAnggaran->struktur_jabatan_id){ 
                        return '<p style="background-color:yellow;">'.$model->diusulkanOleh->strukturJabatan->jabatan.'</p>'; 
                    }else return /*$model->diusulkanOleh->pegawai->nama.' - '.*/$model->diusulkanOleh->strukturJabatan->jabatan;
                },
                'format' => 'html',
                'enableSorting' => false
            ],
            [
                'attribute' => 'jumlah',
                'filter' => '',
                'value' => function($model){
                    return "Rp".number_format($model->jumlah,2,',','.');
                },
                'footer' => Program::getJumlah($dataProvider->models, 'jumlah'),
                'enableSorting' => false
            ],
            [
                'attribute' => 'status_program_id',
                'value' => function ($model) use ($uiHelper){
                    if(!isset($model->statusProgram)) return '';
                    if($model->statusProgram->status_program_id==0 || $model->statusProgram->status_program_id==6)
                        return '<p style="color:black;font-weight:bold;font-style:italic">'.$model->statusProgram->name.' <a href="#" data-toggle="tooltip" data-placement="top" title="'.\yii\helpers\HtmlPurifier::process(Program::isProgramValid($model->program_id)[0]).'"><i class="fa fa-info-circle"></i></a></p>';
                    else if($model->statusProgram->status_program_id==2 || $model->statusProgram->status_program_id==7)
                        return '<p style="color:blue;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==3)
                        return '<p style="color:green;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==5)
                        return '<p style="background-color:green;color:white;font-weight:bold;text-align:center">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==4)
                        return '<p style="color:red;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==8)
                        return '<p style="color:purple;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else
                        return '<p style="color:orange;font-weight:bold">'.$model->statusProgram->name.'</p>';
                },
                'format' => 'html',
                'label' => 'Status Program',
                'enableSorting' => false
            ],
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit} {dana} {breakdown} {review} {accept} {reject} {del}',
                'buttons' => [
                    'del' => function ($url, $model){
                        if($model->auth_del){
                            return "<li>".Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                 'data-pjax' => '0',
                            ])."</li>";
                        }
                    },
                    'edit' => function ($url, $model){
                        if($model->auth_edit){
                            return ToolsColumn::renderCustomButton($url, $model, $model->status_program_id==0 || $model->status_program_id==6?'Waktu Pelaksanaan':'Edit', 'fa fa-pencil');
                        }
                    },
                    'review' => function ($url, $model){
                        if($model->auth_review){
                            return ToolsColumn::renderCustomButton($url, $model, 'Review', 'fa fa-comment');
                        }
                    },
                    'dana' => function ($url, $model){
                        if($model->auth_dana){
                            return ToolsColumn::renderCustomButton($url, $model, 'Sumber Dana', 'fa fa-money');
                        }
                    },
                    'breakdown' => function ($url, $model){
                        if($model->auth_detil){
                            return ToolsColumn::renderCustomButton($url, $model, 'Breakdown', 'fa fa-puzzle-piece');
                        }
                    },
                    'accept' => function ($url, $model){
                        if($model->auth_accept){
                            return "<li>".Html::a('<span class="fa fa-check"></span> Accept', $url, [
                                'title' => Yii::t('yii', 'Accept'),
                                'data-confirm' => Yii::t('yii', 'Are you sure to accept the program ?'),
                                'data-method' => 'post',
                                 'data-pjax' => '0',
                            ])."</li>";
                        }
                    },
                    'reject' => function ($url, $model){
                        if($model->auth_reject){
                            return "<li>".Html::a('<span class="fa fa-times"></span> Reject', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure to reject the program ?'),
                                'data-method' => 'post',
                                 'data-pjax' => '0',
                            ])."</li>";
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) use($pejabat_id){
                    if ($action === 'view') {
                        return Url::toRoute(['program-view', 'id' => $key, 'pejabat_id' => $pejabat_id]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['program-edit', 'id' => $key]);
                    }else if ($action === 'del') {
                        return Url::toRoute(['program-del', 'id' => $key]);
                    }else if ($action === 'review') {
                        return Url::toRoute(['program-view', 'id' => $key, 'pejabat_id' => $pejabat_id, 'tab' => 'data_review']);
                    }else if ($action === 'dana') {
                        return Url::toRoute(['program-view', 'id' => $key, 'tab' => 'data_dana']);
                    }else if ($action === 'breakdown') {
                        return Url::toRoute(['program-view', 'id' => $key, 'tab' => 'data_detil']);
                    }else if ($action === 'accept') {
                        return Url::toRoute(['program-accept', 'id' => $key, 'pejabat_id' => $pejabat_id]);
                    }else if ($action === 'reject') {
                        return Url::toRoute(['program-reject', 'id' => $key, 'pejabat_id' => $pejabat_id]);
                    }
                }
            ],
        ],
    ]);
    Pjax::end() ?>

</div>

<?php 
  $this->registerJs("
    $(document).ready(function() {
        $('[data-toggle=\"tooltip\"]').tooltip();

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
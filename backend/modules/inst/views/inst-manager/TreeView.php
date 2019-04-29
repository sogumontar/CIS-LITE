<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use backend\modules\inst\assets\InstAsset;

InstAsset::register($this);
$uiHelper=\Yii::$app->uiHelper;

$this->title = 'TreeView Institusi';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "TreeView Institusi";

$insts = '';
foreach($instansis as $i){
    if($i->instansi_id==$instansi_id)
        $insts .= '<option value='.$i->instansi_id.' selected>'.$i->name.'</option>';
    else
        $insts .= '<option value='.$i->instansi_id.'>'.$i->name.'</option>';
    //$insts .= '<li><a href="'.\Yii::$app->urlManager->createUrl(['inst/inst-manager/trees', 'instansi_id' => $i->instansi_id]), 'target' =>'#tree-container']).'">'.$i->name.'</a></li>';
}

?>

<div class="callout callout-info">
    <?php
        echo '<b>Pilih Instansi</b> untuk melihat Struktur Jabatan dalam bentuk Tree<br />';
        echo 'Klik <b>Node pada Tree</b> untuk melihat Pejabat yang tengah menjabat pada Jabatan tersebut';
    ?>
</div>

<?=$uiHelper->renderToolbar([
    'pull-right' => true,
    'groupTemplate' => ['group1'],
    'groups' => [
        'group1' => [
            'template' => ['filter'],
            'buttons' => [
                'filter' => '<div class="btn-group btn-group-sm" role="group">
                            Pilih Instansi: <select onchange="trees(this)">'.$insts.'</select>
                          </div>',
            ]
        ],
    ]
]) ?>

<?= $uiHelper->renderContentSubHeader('Institusi '.$instansi_name) ?>

<div id="tree-container">
    <?php
    function _addNode($struktur, &$arr, $i){
        //$arr[$i]['innerHTML'] = array('name' => $struktur);
        $arr[$i]['innerHTML'] = '<div id="show_peg-'.$struktur->struktur_jabatan_id.'" style="text-align:center; cursor: pointer;" onclick="nodeColEx(this)"><div class="jabatan" style="width:auto;white-space:nowrap;overflow:hidden;">'.$struktur->jabatan.'</div>'.
        (isset($struktur->unit_id)?'<div class="unit" style="width:auto;white-space:nowrap;overflow:hidden;">Unit: '.$struktur->unit['name'].'</div>':'').
        '<div id="pegawai-'.$struktur->struktur_jabatan_id.'" style="text-align:left; cursor: pointer; display: none;" onclick="nodeCol(this)">Pejabat :';
        $isExist = _isPejabatAktifExist($struktur->pejabats);
        if($isExist)
            $arr[$i]['innerHTML'] .= '<div class="pegawai"><ul>';
        foreach($struktur->pejabats as $p){
            if($p->status_aktif==1){
                $arr[$i]['innerHTML'] .= '<li><div class="nama">'.$p->pegawai->nama.'</div>'.
                ((isset($p->pegawai->hp) && $p->pegawai->hp!='')?('<div class="hp">tel: '.$p->pegawai->hp.'</div>'):'').
                ((isset($p->pegawai->email) && $p->pegawai->email!='')?('<div class="email">email: '.$p->pegawai->email.'</div>'):'').
                '</li>';
            }
        }
        if($isExist)
            $arr[$i]['innerHTML'] .= '</ul></div>';
        else $arr[$i]['innerHTML'] .= ' -';
        $arr[$i]['innerHTML'] .= '</div></div>';
    }

    function _recursiveStrukturJabatanTreeView($struktur_key, $struktur, &$result, $i)
    {
        _addNode($struktur, $result, $i);
        if(isset($struktur->strukturJabatans)){
            $j=0;
            foreach($struktur->strukturJabatans as $s){
                if($s->deleted!=1 && $s->instansi_id==$struktur->instansi_id){
                    _recursiveStrukturJabatanTreeView($struktur_key, $struktur_key[$s->struktur_jabatan_id], $result[$i]['children'], $j++);
                }
            }
        }
    }

    function _isPejabatAktifExist($pejabats){
        if(count($pejabats)==0)
            return false;
        else{
            foreach($pejabats as $p){
                if($p->status_aktif == 1)
                    return true;
            }
            return false;
        }
    }

    function _pairStructure($instansi_id, $attr, &$result, $strukturs){

        foreach($strukturs as $s)
        {
            $result[$s->getAttribute($attr)] = $s;
        }
    }

    function _getTreeStruct($instansi_id, $strukturs, $strukturs_pair){
        
        $struct_key = array();
        _pairStructure($instansi_id, 'struktur_jabatan_id', $struct_key, $strukturs_pair);
        $struct_result = array();
        $i=0;
        foreach($strukturs as $s){
            //echo $s->jabatan."<br />";
            if(isset($s->parent))
                break;
            //$struct_result[] = array('text' => array('name' => $s->jabatan));
            $struct_result[$i]['innerHTML'] = '<div id="show_peg-'.$s->struktur_jabatan_id.'" style="text-align:center; cursor: pointer;" onclick="nodeColEx(this)"><div class="jabatan" style="width:auto;white-space:nowrap;overflow:hidden;">'.$s->jabatan.'</div>'.
                (isset($s->unit_id)?'<div class="unit" style="width:auto;white-space:nowrap;overflow:hidden;">Unit: '.$s->unit['name'].'</div>':'').
                '<div id="pegawai-'.$s->struktur_jabatan_id.'" style="text-align:left; cursor: pointer; display: none;" onclick="nodeCol(this)">Pejabat :';
                //<a href="#" onclick="tss(this)" id="show_peg-'.$s->struktur_jabatan_id.'">Show Pegawai</a>
            $isExist = _isPejabatAktifExist($s->pejabats);
            if($isExist)
                $struct_result[$i]['innerHTML'] .= '<div class="pegawai"><ul>';
            foreach($s->pejabats as $p){
                if($p->status_aktif==1){
                    $struct_result[$i]['innerHTML'] .= '<li><div class="nama">'.$p->pegawai->nama.'</div>'.
                        ((isset($p->pegawai->hp) && $p->pegawai->hp!='')?('<div class="hp">tel: '.$p->pegawai->hp.'</div>'):'').
                        ((isset($p->pegawai->email) && $p->pegawai->email!='')?('<div class="email">email: '.$p->pegawai->email.'</div>'):'').
                        '</li>';
                }
            }
            if($isExist)
                $struct_result[$i]['innerHTML'] .= '</ul></div>';
            else $struct_result[$i]['innerHTML'] .= ' -';
            $struct_result[$i]['innerHTML'] .= '</div></div>';
            //\Yii::$app->debugger->print_array($struct_result, true);
            if(isset($s->strukturJabatans)){
                $j=0;
                foreach($s->strukturJabatans as $t){
                    if($t->deleted!=1 && $t->instansi_id==$s->instansi_id){
                        _recursiveStrukturJabatanTreeView($struct_key, $struct_key[$t->struktur_jabatan_id], $struct_result[$i]['children'], $j++);
                    }
                }
            }
            $i++;
        }
        return $struct_result;
    }

    $strukturs = _getTreeStruct($instansi_id, $strukturs, $strukturs_pair);
    /*echo "<pre>";
    print_r($strukturs);
    die;*/
        $i=0;
            foreach($strukturs as $s)
            {                   
                ?><div class="pohon" id="tree-simple-<?=$i ?>" style="border: 3px solid #DDD; border-radius: 3px; margin-bottom: 20px;"></div><?php
                $this->registerJs("
                    simple_chart_config = 
                        {
                            chart: {
                                container: '#tree-simple-".$i++."',
                                levelSeparation: 100,
                                siblingSeparation: 50,
                                subTeeSeparation: 50,
                                animateOnInit: true,
                                node: {
                                    HTMLclass: 'nodeExample1'
                                },
                                animation: {
                                    nodeAnimation: 'easeOutBounce',
                                    nodeSpeed: 700,
                                    connectorsAnimation: 'bounce',
                                    connectorsSpeed: 700
                                },
                                nodeAlign: 'BOTTOM',
                                connectors: {
                                    type: 'step'
                                }
                            },    
                            nodeStructure: ".json_encode($s)."
                        };
                        var chartw = new Treant(simple_chart_config);
                ", View::POS_END);
            }
            if($i==0)
                echo "Tidak ada Struktur";
        
    ?>
</div>

<?php 
  $this->registerJs("
    function trees(elem){
        $('.content-sub-header').find('h2').text('Institusi '+elem.options[elem.selectedIndex].innerText);
        var instansi_id = elem.options[elem.selectedIndex].value;
        $.ajax({
            url: '".\Yii::$app->urlManager->createUrl(['inst/inst-manager/trees'])."',
            type: 'POST',
             data: { instansi_id: instansi_id },
             success: function(data) {
                //console.log(jQuery.parseJSON(data));
                 //console.log($(jQuery.parseJSON(data)[1]));
                buildTrees(jQuery.parseJSON(data));
             }
        });
    }

    function buildTrees(trees){
            $('#tree-container').html('');
            trees = htmlTree(trees);
            console.log(trees);
            for(var i=0;i<trees.length;i++)
            {
                $('#tree-container').append('<div class=\"pohon\" id=\"tree-simple-'+i+'\" style=\"border: 3px solid #DDD; border-radius: 3px; margin-bottom: 20px; overflow-y:auto; max-height: '+($(window).height()-50)+'px;\"></div>');

                simple_chart_config = 
                        {
                            chart: {
                                container: '#tree-simple-'+i,
                                levelSeparation: 100,
                                siblingSeparation: 50,
                                subTeeSeparation: 50,
                                animateOnInit: true,
                                node: {
                                    HTMLclass: 'nodeExample1'
                                },
                                animation: {
                                    nodeAnimation: 'easeOutBounce',
                                    nodeSpeed: 700,
                                    connectorsAnimation: 'bounce',
                                    connectorsSpeed: 700
                                },
                                nodeAlign: 'BOTTOM',
                                connectors: {
                                    type: 'step'
                                }
                            },    
                            nodeStructure: trees[i]
                        };
                        var chartw = new Treant(simple_chart_config);
            }
            if(trees.length==0){
                $('#tree-container').html('Tidak ada Struktur');
            }
    }

    function htmlTree(trees){
        for(var i=0;i<trees.length;i++){
            var temp = '';
            temp = '<div id=\"show_peg-'+trees[i]['innerHTML']['struktur_jabatan_id']+'\" style=\"text-align:center; cursor: pointer;\" onclick=\"nodeColEx(this)\"><div class=\"jabatan\" style=\"width:auto;white-space:nowrap;overflow:hidden;\">'+trees[i]['innerHTML']['jabatan']+'</div>'+
                    (trees[i]['innerHTML']['unit_id']!=null && trees[i]['innerHTML']['unit_id']!=\"\"?'<div class=\"unit\" style=\"width:auto;white-space:nowrap;overflow:hidden;\">Unit: '+trees[i]['innerHTML']['unit_name']+'</div>':'')+
                    '<div id=\"pegawai-'+trees[i]['innerHTML']['struktur_jabatan_id']+'\" style=\"text-align:left; cursor: pointer; display: none;\" onclick=\"nodeCol(this)\">Pejabat :';
            if(trees[i]['innerHTML']['pejabats']){
                temp += '<div class=\"pegawai\"><ul>';
                for(var j=0;j<trees[i]['innerHTML']['pejabats'].length;j++){
                    temp += '<li><div class=\"nama\">'+trees[i]['innerHTML']['pejabats'][j]['nama']+'</div>'+
                    (trees[i]['innerHTML']['pejabats'][j]['hp']!=\"\"?'<div class=\"hp\">tel: '+trees[i]['innerHTML']['pejabats'][j]['hp']+'</div>':'')+
                    (trees[i]['innerHTML']['pejabats'][j]['email']!=''?'<div class=\"email\">email: '+trees[i]['innerHTML']['pejabats'][j]['email']+'</div>':'')+
                    '</li>';
                }
                temp += '</ul></div>';
            }else{
                temp += ' -';
            }
            temp += '</div></div>';
            trees[i]['innerHTML'] = temp;
            if(trees[i]['children']){
                for(var j=0;j<trees[i]['children'].length;j++){
                    trees[i]['children'][j] = recurHtmlTree(trees[i]['children'][j]);
                }
            }
        }
        return trees;
    }

    function recurHtmlTree(trees){
        var temp = '';
        temp = '<div id=\"show_peg-'+trees['innerHTML']['struktur_jabatan_id']+'\" style=\"text-align:center; cursor: pointer;\" onclick=\"nodeColEx(this)\"><div class=\"jabatan\" style=\"width:auto;white-space:nowrap;overflow:hidden;\">'+trees['innerHTML']['jabatan']+'</div>'+
            (trees['innerHTML']['unit_id']!=null && trees['innerHTML']['unit_id']!=\"\"?'<div class=\"unit\" style=\"width:auto;white-space:nowrap;overflow:hidden;\">Unit: '+trees['innerHTML']['unit_name']+'</div>':'')+
            '<div id=\"pegawai-'+trees['innerHTML']['struktur_jabatan_id']+'\" style=\"text-align:left; cursor: pointer; display: none;\" onclick=\"nodeCol(this)\">Pejabat :';
        if(trees['innerHTML']['pejabats']){
            temp += '<div class=\"pegawai\"><ul>';
            for(var j=0;j<trees['innerHTML']['pejabats'].length;j++){
                temp += '<li><div class=\"nama\">'+trees['innerHTML']['pejabats'][j]['nama']+'</div>'+
                (trees['innerHTML']['pejabats'][j]['hp']!=\"\"?'<div class=\"hp\">tel: '+trees['innerHTML']['pejabats'][j]['hp']+'</div>':'')+
                (trees['innerHTML']['pejabats'][j]['email']!=''?'<div class=\"email\">email: '+trees['innerHTML']['pejabats'][j]['email']+'</div>':'')+
                '</li>';
            }
            temp += '</ul></div>';
        }else{
            temp += ' -';
        }
        temp += '</div></div>';
        trees['innerHTML'] = temp;
        if(trees['children']){
            for(var j=0;j<trees['children'].length;j++){
                trees['children'][j] = recurHtmlTree(trees['children'][j]);
            }
        }
        return trees;
    }

    $(document).ready(function() {
        for(i=0;i<$('.pohon').length;i++){
            $('.pohon')[i].style.cssText += ' overflow-y:auto; max-height: '+($(window).height()-50)+'px;';
        }
    });

    function nodeColEx(ele){
        if($('#pegawai-'+ele.id.split('-')[1]).is(':visible'))
            $('#pegawai-'+ele.id.split('-')[1]).hide(500);
        else{
            $('#pegawai-'+ele.id.split('-')[1]).show(500);
            //$('#pegawai-'+ele.id.split('-')[1]).css('z-index', 99999999999);
            //console.log($('#pegawai-'+ele.id.split('-')[1]));
        }
    }
    function nodeCol(ele){
        $('#'+ele.id).hide(500);
    }
  ", 
    View::POS_END);
?>
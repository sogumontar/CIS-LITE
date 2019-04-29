<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * 
 *
 * @author Marojahan Sigiro
 */
class ToolsColumn extends \yii\grid\ActionColumn
{
    public $header = '';
    public $template = '{view} {edit} {del}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    /**
     * Initializes the default button rendering callbacks
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return  "<li>".Html::a('<span class="glyphicon glyphicon-eye-open"></span> View', $url, [
                    'title' => Yii::t('yii', 'View'),
                     'data-pjax' => '0',
                ])."</li>";
            };
        }
        if (!isset($this->buttons['edit'])) {
            $this->buttons['edit'] = function ($url, $model) {
                return "<li>".Html::a('<span class="glyphicon glyphicon-pencil"></span> Edit', $url, [
                    'title' => Yii::t('yii', 'Edit'),
                     'data-pjax' => '0',
                ])."</li>";
            };
        }
        if (!isset($this->buttons['del'])) {
            $this->buttons['del'] = function ($url, $model) {
                return "<li>".Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                     'data-pjax' => '0',
                ])."</li>";
            };
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {   
        $content = parent::renderDataCellContent($model, $key, $index);
        $wrapper = "<div class='btn-group'>".
                  "<button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>".
                    "<span class='glyphicon glyphicon-wrench'></span> Tools".
                  "</button>".
                  "<ul class='dropdown-menu dropdown-menu-right' role='menu'>".
                    $content.
                  "</ul>".
                "</div>";
        
        return $wrapper;
    }

    /**
     * render custom button selain button default (view, update, delete)
     * @param  $url   button url (from creator, or supplied)
     * @param  $model rendered row model
     * @param  $label button label
     * @param  $icon button icon
     * @return mixed
     */
    public static function renderCustomButton($url, $model, $label, $icon=null){
        if ($icon == null) {
            $icon = "glyphicon glyphicon-asterisk";
        }
        
        return "<li>".Html::a('<span class="'.$icon.'"></span> '.$label, $url, [
                    'title' => $label,
                    'data-pjax' => '0',
                ])."</li>";
    }
}

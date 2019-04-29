<?php
namespace backend\themes\v2\helpers;

use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonDropdown;  
use yii\bootstrap\Dropdown; 
use yii\helpers\StringHelper;
use yii\helpers\Url;

use common\helpers\ArrayHelper;
/**
 * @author: Marojahan Sigiro <marojahan@gmail.com>
 */
class MenuRenderer {
	public static function renderContentMenu($menu){
		
		//process/convert clean menu array
		foreach ($menu as $menuItem) {
			//add icon
			if(ArrayHelper::getValueNotNull($menuItem, 'icon', false)){
				$menuItem['label'] = "<i class='".$menuItem['icon']."'></i>".$menuItem['label'];
			}
				
			if($menuItem['childs']){
				foreach ($menuItem['childs'] as $menuChild) {

					$item = ['label' => $menuChild['label'],
							  'url' => Url::toRoute($menuChild['url']),
							];

					//add icon if any
					if(ArrayHelper::getValueNotNull($menuChild, 'icon', false)){
						$item['label'] = "<i class='".$menuChild['icon']."'></i>".$item['label'];
					}

					//handle ajax link with pjax
					if(ArrayHelper::getValueNotNull($menuChild, 'is_ajax', false)){
						$container = ArrayHelper::getValueNotNull($menuChild, 'container_id', '#main-content');
						$item['linkOptions'] = ['data-pjax' => $container];
					}

					$dropdownItems[] = $item;
				}

				echo ButtonDropdown::widget(['label' => $menuItem['label'],
										   'dropdown' => [
										   					'encodeLabels' => false,
										   					'items' => $dropdownItems,
										   				 ],
										   	'encodeLabel' => false,
								  			]);
				$dropdownItems = [];
			}else {
				if(ArrayHelper::getValueNotNull($menuItem, 'is_ajax', false)){
					$container = ArrayHelper::getValueNotNull($menuItem, 'container_id', '#main-content');

					echo "<a href='".Url::toRoute($menuItem['url'])."' class='btn ' data-pjax='".$container."'>".$menuItem['label']."</a>";
				}else{
					echo "<a href='".Url::toRoute($menuItem['url'])."' class='btn '>".$menuItem['label']."</a>";
				}
			}
		}	
	}

	public static function renderSidebarMenu($menu){
		$currentUrl = \Yii::$app->request->url;
		$menuTree = '<ul class="sidebar-menu">';
		foreach ($menu as $menuItem) {
			$isTree = true;
			$root = '';
			if(ArrayHelper::getValueNotNull($menuItem, 'childs', false)){
				$root = '<li class="treeview">';
			}else{
				$isTree = false;
				$root = '<li>';
			}
			$rootContent = '<a href="'.Url::toRoute(ArrayHelper::getValueNotNull($menuItem, 'url', '#')).'">';
			$rootContent .= '<i class="'.ArrayHelper::getValueNotNull($menuItem, 'icon', 'fa fa-plus-square').'"></i>';
			$rootContent .= '<span>'.ArrayHelper::getValueNotNull($menuItem, 'label', 'error:no-label').'</span>';
			
			$rootContent .= ($isTree)?'<i class="fa fa-angle-left pull-right"></i>':'';
			
			$rootContent .= '</a>';
			$childRoot = '';
			$isActive = false;

			if(ArrayHelper::getValueNotNull($menuItem, 'childs', false)){
				$childRoot = '<ul class="treeview-menu">';
				foreach ($menuItem['childs'] as $childItem) {
					$child = '';
					if(StringHelper::startsWith($currentUrl, ArrayHelper::getValueNotNull($childItem, 'url', '#'))){
						$isActive = true;
					}
					$child .= '<li>';

					$child .= '<a href="'.Url::toRoute(ArrayHelper::getValueNotNull($childItem, 'url', '#')).'">';
					$child .= '<i class="'.ArrayHelper::getValueNotNull($childItem, 'icon', 'fa fa-angle-double-right').'"></i>';
					$child .= ArrayHelper::getValueNotNull($childItem, 'label', 'error:no-label');
					$child .= '</a></li>';
					$childRoot .= $child;
				}

				$childRoot .= '</ul>';
			}
			if($isActive){
				$root = '<li class="treeview active">';
			}

			$root .= $rootContent.$childRoot.'</li>';
			$menuTree .= $root;
		}
		$menuTree .= "</ul>";
		echo $menuTree;

		return;
	}
}
?>
<?php
namespace backend\themes\admin_lte\helpers;

use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonDropdown;  
use yii\bootstrap\Dropdown; 
use yii\helpers\StringHelper;
use yii\helpers\Url;

use common\helpers\ArrayHelper;

class MenuRenderer {

	/**
	 * Menerima input dalam bentuk array yang berisi struktur menu (output dari MenuControl)
	 * output, me-render menu controller dengan terlebih dahulu meng-convert array inpun menjadi 
	 * array dengan structure berikut (struktur input array widget ButtonDropdown) 
	 * ```php
	 * ['label' => 'root menu 1 label',
     *        'dropdown' => [
     *           'items' => [
     *               ['label' => 'child 1.1 label', 'url' => '#'],
     *               ['label' => 'child 1.2 label', 'url' => '#'],
     *           ],
	 *		 ],
     * ],
     * ['label' => 'Mroot menu 2 label',
     *        'dropdown' => [
     *           'items' => [
     *               ['label' => 'child 1.1 label', 'url' => '#'],
     *               ['label' => 'child 1.2 label', 'url' => '#'],
     *           ],
	 *		 ],
     * ],
     * ```
	 * @param  array $menu [array menu yang digenerate oleh MenuControl]
	 */
	
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

	/**
	 *Menerima input dalam bentuk array yang berisi struktur menu (output dari MenuControl)
	 * output, me-render menu sidebar, max 2 level menu
	 * ```
	 * @param  array 	menu  Menu yang dihasilkan oleh class MenuControl
	 * @return mixed
	 */
	public static function renderSidebarMenu($menu){
		$currentUrl = \Yii::$app->request->url;
		$menuTree = '<ul class="sidebar-menu">';
		foreach ($menu as $menuItem) {
			$isTree = true;
			$root = '';
			if(ArrayHelper::getValueNotNull($menuItem, 'childs', false)){
				//TODO: set active menu.. by adding class active, how do we get it?
				$root = '<li class="treeview">';
			}else{
				$isTree = false;
				$root = '<li>';
			}
			$root .= '<a href="'.Url::toRoute(ArrayHelper::getValueNotNull($menuItem, 'url', '#')).'">';
			$root .= '<i class="'.ArrayHelper::getValueNotNull($menuItem, 'icon', 'fa fa-plus-square').'"></i>';
			$root .= '<span>'.ArrayHelper::getValueNotNull($menuItem, 'label', 'error:no-label').'</span>';
			
			$root .= ($isTree)?'<i class="fa fa-angle-left pull-right"></i>':'';
			
			$root .= '</a>';

			if(ArrayHelper::getValueNotNull($menuItem, 'childs', false)){
				$root .= '<ul class="treeview-menu">';
				foreach ($menuItem['childs'] as $childItem) {
					$child = '';
					if(StringHelper::startsWith($currentUrl, ArrayHelper::getValueNotNull($childItem, 'url', '#'))){
						$child .= '<li class="pactive">';
					}else{
						$child .= '<li>';

					}
					$child .= '<a href="'.Url::toRoute(ArrayHelper::getValueNotNull($childItem, 'url', '#')).'">';
					$child .= '<i class="'.ArrayHelper::getValueNotNull($childItem, 'icon', 'fa fa-angle-double-right').'"></i>';
					$child .= ArrayHelper::getValueNotNull($childItem, 'label', 'error:no-label');
					$child .= '</a></li>';
					$root .= $child;
				}

				$root .= '</ul>';
			}

			$root .= '</li>';
			$menuTree .= $root;
		}
		$menuTree .= "</ul>";
		echo $menuTree;

		return;
	}
}
?>
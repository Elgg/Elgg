<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Menu\PreparedMenu;

/**
 * Register menu items to the site menu
 *
 * @since 4.0
 * @internal
 */
class Site {

	/**
	 * Registers custom menu items
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|MenuItems
	 */
	public static function registerAdminConfiguredItems(\Elgg\Hook $hook) {
		$custom_menu_items = elgg_get_config('site_custom_menu_items');
		if (empty($custom_menu_items)) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		// add custom menu items
		$n = 1;
		foreach ($custom_menu_items as $title => $url) {
			$return[] = \ElggMenuItem::factory([
				'name' => "custom{$n}",
				'text' => $title,
				'href' => $url,
			]);
			$n++;
		}
		
		return $return;
	}
	
	/**
	 * Reorder the site menu based on custom order and move excess to dropdown
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'menu:site'
	 *
	 * @return void|PreparedMenu
	 */
	public static function reorderItems(\Elgg\Hook $hook) {
		/* @var $menu PreparedMenu */
		$menu = $hook->getValue();
		
		$featured_menu_names = array_values((array) elgg_get_config('site_featured_menu_names'));
		
		$registered = $menu->getItems('default');
		if (empty($registered)) {
			return;
		}
		
		$has_selected = false;
		$priority = 500;
		
		foreach ($registered as $item) {
			if (in_array($item->getName(), $featured_menu_names)) {
				$featured_index = array_search($item->getName(), $featured_menu_names);
				$item->setPriority($featured_index);
			} else {
				$item->setPriority($priority);
				$priority++;
			}
			if ($item->getSelected()) {
				$has_selected = true;
			}
		}
		
		if (!$has_selected) {
			// no selected item found, try to match on context
			$site_url = elgg_get_site_url();
			
			$is_selected = function (\ElggMenuItem $item) use ($site_url) {
				if (elgg_strpos($item->getHref(), $site_url) !== 0) {
					// not an url on the site
					return false;
				}
				
				if ($item->getName() == elgg_get_context()) {
					// menu name matches current context
					return true;
				}
				
				return false;
			};
			
			foreach ($registered as $item) {
				if ($is_selected($item)) {
					$item->setSelected(true);
					break;
				}
			}
		}
		
		usort($registered, [\ElggMenuBuilder::class, 'compareByPriority']);
		
		$max_display_items = $hook->getParam('max_display_items', 5);
		
		$num_menu_items = count($registered);
		
		$more = [];
		if ($max_display_items && $num_menu_items > ($max_display_items + 1)) {
			$more = array_splice($registered, $max_display_items);
		}
		
		if (!empty($more)) {
			$dropdown = \ElggMenuItem::factory([
				'name' => 'more',
				'text' => elgg_echo('more'),
				'href' => false,
				'icon_alt' => 'angle-down',
				'priority' => 999,
			]);
			
			foreach ($more as &$item) {
				$item->setParentName('more');
			}
			
			$dropdown->setChildren($more);
			
			$registered[] = $dropdown;
		}
		
		$menu->getSection('default')->fill($registered);
		
		return $menu;
	}
}

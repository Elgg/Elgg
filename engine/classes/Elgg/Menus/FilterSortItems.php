<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add generic sort_by menu items to a filter menu
 *
 * @since 4.2
 */
class FilterSortItems {
	
	/**
	 * Register sorting menu items based on the time_created attribute
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerTimeCreatedSorting(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:time_created:desc',
			'icon' => 'sort-numeric-down-alt',
			'text' => elgg_echo('sort:newest'),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'time_created',
					'property_type' => 'attribute',
					'direction' => 'desc',
				],
			]),
			'parent_name' => 'sort:parent',
			'priority' => 100,
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:time_created:asc',
			'icon' => 'sort-numeric-down',
			'text' => elgg_echo('sort:oldest'),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'time_created',
					'property_type' => 'attribute',
					'direction' => 'asc',
				],
			]),
			'parent_name' => 'sort:parent',
			'priority' => 110,
		]);
		
		return $result;
	}
	
	/**
	 * Register sorting menu items based on the last_action attribute
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerLastActionSorting(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:last_action:desc',
			'icon' => 'sort-numeric-down-alt',
			'text' => elgg_echo('table_columns:fromView:last_action'),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'last_action',
					'property_type' => 'attribute',
					'direction' => 'desc',
				],
			]),
			'parent_name' => 'sort:parent',
			'priority' => 115,
		]);
		
		return $result;
	}
	
	/**
	 * Register sorting menu items based on the last_login metadata
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerLastLoginSorting(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:last_login:asc',
			'icon' => 'sort-numeric-down',
			'text' => elgg_echo('table_columns:fromView:last_login'),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'last_login',
					'direction' => 'asc',
				],
			]),
			'parent_name' => 'sort:parent',
			'priority' => 116,
		]);
		
		return $result;
	}
	
	/**
	 * Register sorting menu items based on the name metadata
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerNameSorting(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:name:asc',
			'icon' => 'sort-alpha-down',
			'text' => elgg_echo('sort:az', [elgg_echo('table_columns:fromProperty:name')]),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'name',
					'property_type' => 'metadata',
					'direction' => 'asc',
				],
			]),
			'title' => elgg_echo('sort:alpha'),
			'parent_name' => 'sort:parent',
			'priority' => 120,
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:name:desc',
			'icon' => 'sort-alpha-down-alt',
			'text' => elgg_echo('sort:za', [elgg_echo('table_columns:fromProperty:name')]),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'name',
					'property_type' => 'metadata',
					'direction' => 'desc',
				],
			]),
			'title' => elgg_echo('sort:alpha'),
			'parent_name' => 'sort:parent',
			'priority' => 130,
		]);
		
		return $result;
	}
	
	/**
	 * Register sorting menu items based on the title metadata
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerTitleSorting(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:title:asc',
			'icon' => 'sort-alpha-down',
			'text' => elgg_echo('sort:az', [elgg_echo('title')]),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'title',
					'property_type' => 'metadata',
					'direction' => 'asc',
				],
			]),
			'title' => elgg_echo('sort:alpha'),
			'parent_name' => 'sort:parent',
			'priority' => 140,
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'sort:title:desc',
			'icon' => 'sort-alpha-down-alt',
			'text' => elgg_echo('sort:za', [elgg_echo('title')]),
			'href' => elgg_http_add_url_query_elements(elgg_get_current_url(), [
				'sort_by' => [
					'property' => 'title',
					'property_type' => 'metadata',
					'direction' => 'desc',
				],
			]),
			'title' => elgg_echo('sort:alpha'),
			'parent_name' => 'sort:parent',
			'priority' => 150,
		]);
		
		return $result;
	}
	
	/**
	 * Setup the sorting options in a dropdown menu, This should be used in combination with the other register function in this class.
	 * This function should have a very high priority to make sure not to interfere with other register events
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter<:some filter_id>'
	 *
	 * @return MenuItems|null
	 */
	public static function registerSortingDropdown(\Elgg\Event $event): ?MenuItems {
		
		if (!(bool) $event->getParam('filter_sorting', true)) {
			// sorting is disabled for this menu
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$first_menu_name = $event->getParam('filter_sorting_selected');
		if (isset($first_menu_name) && !$result->has($first_menu_name)) {
			$first_menu_name = null;
		}
		
		if (!isset($first_menu_name)) {
			$result->sort([\ElggMenuBuilder::class, 'compareByPriority']);
			
			/* @var $menu_item \ElggMenuItem */
			foreach ($result as $menu_item) {
				if ($menu_item->getParentName() !== 'sort:parent') {
					continue;
				}
				
				if (!isset($first_menu_name)) {
					$first_menu_name = $menu_item->getID();
				}
				
				if (!$menu_item->getSelected()) {
					continue;
				}
				
				$first_menu_name = $menu_item->getID();
				break;
			}
		}
		
		if (!empty($first_menu_name)) {
			$menu_item = $result->get($first_menu_name);
			
			$menu_item->setPriority(99999);
			$menu_item->setName('sort:parent');
			$menu_item->setParentName('');
			$menu_item->setChildMenuOptions([
				'display' => 'dropdown',
				'data-position' => json_encode([
					'at' => 'right bottom',
					'my' => 'right top',
					'collision' => 'fit fit',
				]),
			]);
			$menu_item->setSelected(false);
			$menu_item->setHref(false);
		}
		
		return $result;
	}
}

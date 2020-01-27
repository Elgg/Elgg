<?php

namespace Elgg\Discussions;

/**
 * Menu functions
 */
class Menus {
	
	/**
	 * Adds discussions menu item to site menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 *
	 * @since 3.3
	 */
	public static function registerSiteMenuItem(\Elgg\Hook $hook) {
		
		if (!elgg_get_plugin_setting('enable_global_discussions', 'discussions')) {
			return;
		}
		
		$return = $hook->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'discussions',
			'text' => elgg_echo('collection:object:discussion'),
			'href' => elgg_generate_url('collection:object:discussion:all'),
		]);
	
		return $return;
	}
	
	/**
	 * Add / remove tabs from the filter menu on the discussion pages
	 *
	 * @param \Elgg\Hook $hook 'filter_tabs', 'discussion'
	 *
	 * @return void|\ElggMenuItem[]
	 * @since 3.3
	 */
	public static function filterTabs(\Elgg\Hook $hook) {
		
		/* @var $items \ElggMenuItem[] */
		$items = $hook->getValue();
		
		// remove friends
		foreach ($items as $index => $item) {
			if ($item->getName() !== 'friend') {
				continue;
			}
			
			unset($items[$index]);
			break;
		}
		
		// add discussions in my groups
		$user = $hook->getUserParam();
		if ($user instanceof \ElggUser && elgg_is_active_plugin('groups')) {
			$selected = $hook->getParam('selected');
			
			$items[] = \ElggMenuItem::factory([
				'name' => 'my_groups',
				'text' => elgg_echo('collection:object:discussion:my_groups'),
				'href' => elgg_generate_url('collection:object:discussion:my_groups', [
					'username' => $user->username,
				]),
				'selected' => $selected === 'my_groups',
				'priority' => 400,
			]);
		}
		
		return $items;
	}
}

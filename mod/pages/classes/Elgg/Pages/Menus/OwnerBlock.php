<?php

namespace Elgg\Pages\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class OwnerBlock {

	/**
	 * Register user item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerUserItem(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'pages',
			'text' => elgg_echo('collection:object:page'),
			'href' => elgg_generate_url('collection:object:page:owner', [
				'username' => $entity->username,
			]),
		]);
		
		return $return;
	}

	/**
	 * Register group item to menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerGroupItem(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		if (!$entity->isToolEnabled('pages')) {
			return;
		}
		
		$return = $hook->getValue();
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'pages',
			'text' => elgg_echo('collection:object:page:group'),
			'href' => elgg_generate_url('collection:object:page:group', [
				'guid' => $entity->guid,
				'subpage' => 'all',
			]),
		]);
		
		return $return;
	}
}

<?php

namespace Elgg\Blog\Menus;

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
			'name' => 'blog',
			'text' => elgg_echo('collection:object:blog'),
			'href' => elgg_generate_url('collection:object:blog:owner', [
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
		
		if (!$entity->isToolEnabled('blog')) {
			return;
		}
		
		$return = $hook->getValue();
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'blog',
			'text' => elgg_echo('collection:object:blog:group'),
			'href' => elgg_generate_url('collection:object:blog:group', [
				'guid' => $entity->guid,
				'subpage' => 'all',
			]),
		]);
		
		return $return;
	}
}

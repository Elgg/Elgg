<?php

namespace Elgg\Pages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class OwnerBlock {

	/**
	 * Register user item to menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerUserItem(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		$return = $event->getValue();
		
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
	 * @param \Elgg\Event $event 'register', 'menu:owner_block'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerGroupItem(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		if (!$entity->isToolEnabled('pages')) {
			return;
		}
		
		$return = $event->getValue();
	
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

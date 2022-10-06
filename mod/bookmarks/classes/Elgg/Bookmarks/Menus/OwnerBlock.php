<?php

namespace Elgg\Bookmarks\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
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
			'name' => 'bookmarks',
			'text' => elgg_echo('collection:object:bookmarks'),
			'href' => elgg_generate_url('collection:object:bookmarks:owner', [
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
		
		if (!$entity->isToolEnabled('bookmarks')) {
			return;
		}
		
		$return = $event->getValue();
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'bookmarks',
			'text' => elgg_echo('collection:object:bookmarks:group'),
			'href' => elgg_generate_url('collection:object:bookmarks:group', [
				'guid' => $entity->guid,
			]),
		]);

		return $return;
	}
}

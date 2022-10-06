<?php

namespace Elgg\File\Menus;

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
			'name' => 'file',
			'text' => elgg_echo('collection:object:file'),
			'href' => elgg_generate_url('collection:object:file:owner', [
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
		
		if (!$entity->isToolEnabled('file')) {
			return;
		}
		
		$return = $event->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'file',
			'text' => elgg_echo('collection:object:file:group'),
			'href' => elgg_generate_url('collection:object:file:group', [
				'guid' => $entity->guid,
			]),
		]);

		return $return;
	}
}

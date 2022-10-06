<?php

namespace Elgg\Activity\Menus;

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
			'name' => 'activity:owner',
			'text' => elgg_echo('activity:owner'),
			'href' => elgg_generate_url('collection:river:owner', ['username' => $entity->username]),
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
		
		if (!$entity->isToolEnabled('activity')) {
			return;
		}
		
		$return = $event->getValue();
	
		$return[] = \ElggMenuItem::factory([
			'name' => 'collection:river:group',
			'text' => elgg_echo('collection:river:group'),
			'href' => elgg_generate_url('collection:river:group', ['guid' => $entity->guid]),
		]);
		
		return $return;
	}
}

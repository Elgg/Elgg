<?php

namespace Elgg\Pages\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {

	/**
	 * Registers menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity:object:page'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggPage) {
			return;
		}
		
		if (!$entity->canEdit()) {
			return;
		}
		
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'history',
			'icon' => 'history',
			'text' => elgg_echo('pages:history'),
			'href' => elgg_generate_url('history:object:page', [
				'guid' => $entity->guid,
			]),
		]);
	
		return $return;
	}
}

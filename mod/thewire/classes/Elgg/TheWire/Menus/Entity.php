<?php

namespace Elgg\TheWire\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {

	/**
	 * Sets up the entity menu for thewire
	 *
	 * Adds reply and thread links. Removes edit and access.
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggWire) {
			return;
		}
		
		$menu = $event->getValue();
		$menu->remove('edit');
	
		if (elgg_is_logged_in()) {
			$menu->add(\ElggMenuItem::factory([
				'name' => 'reply',
				'icon' => 'reply',
				'text' => elgg_echo('reply'),
				'href' => elgg_generate_entity_url($entity, 'reply'),
			]));
		}
	
		$menu->add(\ElggMenuItem::factory([
			'name' => 'thread',
			'icon' => 'comments-regular',
			'text' => elgg_echo('thewire:thread'),
			'href' => elgg_generate_url('collection:object:thewire:thread', [
				'guid' => $entity->wire_thread,
			]),
		]));
	
		return $menu;
	}
}

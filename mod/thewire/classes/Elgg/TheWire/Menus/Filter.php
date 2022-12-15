<?php

namespace Elgg\TheWire\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the filter menu
 *
 * @since 5.0
 */
class Filter {
	
	/**
	 * Adds mentions to the filter menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:filter'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function registerMentions(\Elgg\Event $event): ?MenuItems {
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser) {
			return null;
		}
		
		$entity_type = $event->getParam('entity_type', '');
		$entity_subtype = $event->getParam('entity_subtype', '');
		if (empty($entity_type) || empty($entity_subtype)) {
			$route_name = elgg_get_current_route_name();
			if (!empty($route_name)) {
				// assume route name as '<purpose>:<entity type>:<entity subtype>:<sub>'
				// eg collection:object:blog:owner or view:group:group
				// @see http://learn.elgg.org/en/stable/guides/routing.html#routes-names
				$route_parts = explode(':', $route_name);
				$entity_type = elgg_extract(1, $route_parts, '');
				$entity_subtype = elgg_extract(2, $route_parts, '');
			}
		}
		
		if ($entity_type !== 'object' || $entity_subtype !== 'thewire') {
			return null;
		}
		
		// register mentions tab
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'mentions',
			'text' => elgg_echo('thewire:menu:filter:mentions'),
			'href' => elgg_generate_url('collection:object:thewire:mentions', [
				'username' => $user->username,
			]),
			'priority' => 310,
		]);
		
		return $result;
	}
}

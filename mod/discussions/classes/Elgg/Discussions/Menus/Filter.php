<?php

namespace Elgg\Discussions\Menus;

use Elgg\Menu\MenuItems;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Filter {
	
	/**
	 * Add / remove tabs from the filter menu on the discussion pages
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:filter'
	 *
	 * @return MenuItems|null
	 */
	public static function filterTabsForDiscussions(\Elgg\Event $event): ?MenuItems {
		
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
		
		if ($entity_type !== 'object' || $entity_subtype !== 'discussion') {
			return null;
		}
		
		$user = elgg_get_logged_in_user_entity();
		if (!$user instanceof \ElggUser || !elgg_is_active_plugin('groups')) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		// add discussions in my groups
		$result[] = \ElggMenuItem::factory([
			'name' => 'my_groups',
			'text' => elgg_echo('collection:object:discussion:my_groups'),
			'href' => elgg_generate_url('collection:object:discussion:my_groups', [
				'username' => $user->username,
			]),
			'priority' => 400,
		]);
		
		return $result;
	}
}

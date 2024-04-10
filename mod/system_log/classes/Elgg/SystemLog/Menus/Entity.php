<?php

namespace Elgg\SystemLog\Menus;

/**
 * Event callbacks for entity menus
 *
 * @since 4.3
 */
class Entity {
	
	/**
	 * Add to the entity menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity|menu:entity:trash'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$entity = $event->getEntityParam();
		$options = ['object_id' => $entity->guid];
		if ($entity instanceof \ElggUser) {
			$options = ['user_guid' => $entity->guid];
		}
		
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:logbrowser',
			'icon' => 'search',
			'text' => elgg_echo('logbrowser:explore'),
			'href' => elgg_http_add_url_query_elements('admin/administer_utilities/logbrowser', $options),
		]);
	
		return $return;
	}
}

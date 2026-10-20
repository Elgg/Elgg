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
		$entity = $event->getEntityParam();
		if (!elgg_is_admin_logged_in() || !$entity instanceof \ElggEntity) {
			return;
		}
		
		$options = [
			'segments' => 'administer_utilities/logbrowser',
		];
		
		if ($entity instanceof \ElggUser) {
			$options['user_guid'] = $entity->guid;
		} else {
			$options['object_id'] = $entity->guid;
		}
		
		$return = $event->getValue();
		$return[] = \ElggMenuItem::factory([
			'name' => 'administer_utilities:logbrowser',
			'icon' => 'search',
			'parent_name' => 'admin',
			'text' => elgg_echo('logbrowser:explore'),
			'href' => elgg_generate_url('admin', $options),
		]);
	
		return $return;
	}
}

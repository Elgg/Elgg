<?php

namespace Elgg\Likes\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Social {

	/**
	 * Add likes to social menu
	 *
	 * @param \Elgg\Event $event 'register' 'menu:social'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		if (!$entity->hasCapability('likable')) {
			return;
		}
		
		$return = $event->getValue();
	
		if ($entity->canAnnotate(0, 'likes')) {
			$return[] = _likes_menu_item($entity);
		}
		
		$return[] = _likes_count_menu_item($entity);
	
		return $return;
	}
}

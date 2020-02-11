<?php

namespace Elgg\Likes\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 *
 * @internal
 */
class Social {

	/**
	 * Add likes to social menu
	 *
	 * @param \Elgg\Hook $hook 'register' 'menu:social'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity) {
			return;
		}
		
		$type = $entity->type;
		$subtype = $entity->getSubtype();
	
		$supports_likes = (bool) elgg_trigger_plugin_hook('likes:is_likable', "$type:$subtype", [], false);
		if (!$supports_likes) {
			return;
		}
		
		$return = $hook->getValue();
	
		if ($entity->canAnnotate(0, 'likes')) {
			$return[] = _likes_menu_item($entity);
		}
		
		$return[] = _likes_count_menu_item($entity);
	
		return $return;
	}
}

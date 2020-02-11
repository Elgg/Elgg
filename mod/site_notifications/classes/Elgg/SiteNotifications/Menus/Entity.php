<?php

namespace Elgg\SiteNotifications\Menus;

/**
 * Hook callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {
	
	/**
	 * Fixes unwanted menu items on the entity menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \SiteNotification) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $hook->getValue();
		
		$return->remove('edit');
		
		$delete = $return->get('delete');
		if ($delete instanceof \ElggMenuItem) {
			$delete->setLinkClass('site-notifications-delete');
			$delete->{"data-entity-ref"} = 'elgg-object-' . $entity->guid;
		}
		
		return $return;
	}
}

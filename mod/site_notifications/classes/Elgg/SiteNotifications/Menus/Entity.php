<?php

namespace Elgg\SiteNotifications\Menus;

/**
 * Event callbacks for menus
 *
 * @since 4.0
 * @internal
 */
class Entity {
	
	/**
	 * Fixes unwanted menu items on the entity menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity'
	 *
	 * @return void|\Elgg\Menu\MenuItems
	 */
	public static function register(\Elgg\Event $event) {
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \SiteNotification) {
			return;
		}
		
		/* @var $return \Elgg\Menu\MenuItems */
		$return = $event->getValue();
		
		$return->remove('edit');
		
		$delete = $return->get('delete');
		if ($delete instanceof \ElggMenuItem) {
			$delete->setLinkClass('site-notifications-delete');
			$delete->{'data-entity-ref'} = "elgg-object-{$entity->guid}";
		}
		
		return $return;
	}
}

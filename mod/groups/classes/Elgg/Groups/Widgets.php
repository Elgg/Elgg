<?php

namespace Elgg\Groups;

/**
 * Widget related functions
 */
class Widgets {
	
	/**
	 * Get the widget URL for the a_users_groups widget
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function usersGroupsWidgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'a_users_groups') {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return null;
		}
		
		return elgg_generate_url('collection:group:group:member', [
			'username' => $owner->username,
		]);
	}
}

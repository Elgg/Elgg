<?php

namespace Elgg\Friends;

/**
 * Handle friends widgets
 *
 * @since 4.0
 * @internal
 */
class Widgets {

	/**
	 * Returns widget URLS used in widget titles
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function setWidgetUrl(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// // someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return null;
		}
		
		switch ($widget->handler) {
			case 'friends':
				return elgg_generate_url('collection:friends:owner', ['username' => $owner->username]);
			case 'friends_of':
				return elgg_generate_url('collection:friends_of:owner', ['username' => $owner->username]);
		}
		
		return null;
	}
}

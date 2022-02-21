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
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return void|string
	 * @internal
	 */
	public static function setWidgetUrl(\Elgg\Hook $hook) {
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget) {
			return;
		}
		
		$owner = $widget->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return;
		}
		
		switch ($widget->handler) {
			case 'friends':
				return elgg_generate_url('collection:friends:owner', ['username' => $owner->username]);
			case 'friends_of':
				return elgg_generate_url('collection:friends_of:owner', ['username' => $owner->username]);
		}
	}
}

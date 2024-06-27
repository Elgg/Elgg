<?php

namespace Elgg\MessageBoard;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the messageboard widgets
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function widgetURL(\Elgg\Event $event): ?string {
		if (!empty($event->getValue())) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'messageboard') {
			return null;
		}
		
		$owner = $widget->getOwnerEntity();
		if (!$owner instanceof \ElggUser) {
			return null;
		}
		
		return elgg_generate_url('collection:annotation:messageboard:owner', [
			'username' => $owner->username,
		]);
	}
}

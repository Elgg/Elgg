<?php

namespace Elgg\MessageBoard;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the messageboard widgets
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object'
	 *
	 * @return void|string
	 */
	public static function widgetURL(\Elgg\Event $event) {
		
		$return_value = $event->getValue();
		if (!empty($return_value)) {
			// someone already set an url
			return;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'messageboard') {
			return;
		}
		
		$owner = $widget->getOwnerEntity();
		return elgg_generate_url('collection:annotation:messageboard:owner', [
			'username' => $owner->username,
		]);
	}
}

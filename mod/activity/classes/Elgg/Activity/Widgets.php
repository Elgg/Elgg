<?php

namespace Elgg\Activity;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the activity widget
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
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'river_widget') {
			return;
		}
		
		return elgg_generate_url('default:river');
	}
}

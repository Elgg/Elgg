<?php

namespace Elgg\Activity;

/**
 * Widget related functions
 */
class Widgets{

	/**
	 * Set the title URL for the activity widget
	 *
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return void|string
	 */
	public static function widgetURL(\Elgg\Hook $hook) {
		
		$return_value = $hook->getValue();
		if (!empty($return_value)) {
			// someone already set an url
			return;
		}
		
		$widget = $hook->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'river_widget') {
			return;
		}
		
		return elgg_generate_url('default:river');
	}
}

<?php

namespace Elgg\MessageBoard;

/**
 * Widget related functions
 */
class Widgets{

	/**
	 * Set the title URL for the messageboard widgets
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
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'messageboard') {
			return;
		}
		
		$owner = $widget->getOwnerEntity();
		return elgg_generate_url('collection:annotation:messageboard:owner', [
			'username' => $owner->username,
		]);
	}
}

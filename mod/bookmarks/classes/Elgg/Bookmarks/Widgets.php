<?php

namespace Elgg\Bookmarks;

/**
 * Widget related functions
 */
class Widgets{

	/**
	 * Set the title URL for the bookmarks widgets
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
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'bookmarks') {
			return;
		}
		
		$owner = $widget->getOwnerEntity();
		if ($owner instanceof \ElggGroup) {
			return elgg_generate_url('collection:object:bookmarks:group', [
				'guid' => $owner->guid,
			]);
		} elseif ($owner instanceof \ElggUser) {
			return elgg_generate_url('collection:object:bookmarks:owner', [
				'username' => $owner->username,
			]);
		}
	}
}

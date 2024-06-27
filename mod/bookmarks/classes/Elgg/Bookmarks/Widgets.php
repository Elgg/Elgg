<?php

namespace Elgg\Bookmarks;

/**
 * Widget related functions
 */
class Widgets {

	/**
	 * Set the title URL for the bookmarks widgets
	 *
	 * @param \Elgg\Event $event 'entity:url', 'object:widget'
	 *
	 * @return null|string
	 */
	public static function widgetURL(\Elgg\Event $event): ?string {
		$return_value = $event->getValue();
		if (!empty($return_value)) {
			// someone already set an url
			return null;
		}
		
		$widget = $event->getEntityParam();
		if (!$widget instanceof \ElggWidget || $widget->handler !== 'bookmarks') {
			return null;
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
		
		return elgg_generate_url('collection:object:bookmarks:all');
	}
}

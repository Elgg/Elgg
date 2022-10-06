<?php

namespace Elgg\Pages;

/**
 * Event callbacks for icons
 *
 * @since 4.0
 * @internal
 */
class Icons {

	/**
	 * Override the default entity icon for pages
	 *
	 * @param \Elgg\Event $event 'entity:icon:url', 'object'
	 *
	 * @return string
	 */
	public static function getIconUrl(\Elgg\Event $event) {
		if ($event->getEntityParam() instanceof \ElggPage) {
			return elgg_get_simplecache_url('pages/images/pages.gif');
		}
	}
}

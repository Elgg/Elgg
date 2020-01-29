<?php

namespace Elgg\Pages;

/**
 * Hook callbacks for icons
 *
 * @since 4.0
 * @internal
 */
class Icons {

	/**
	 * Override the default entity icon for pages
	 *
	 * @param \Elgg\Hook $hook 'entity:icon:url', 'object'
	 *
	 * @return string
	 */
	public static function getIconUrl(\Elgg\Hook $hook) {
		if ($hook->getEntityParam() instanceof \ElggPage) {
			return elgg_get_simplecache_url('pages/images/pages.gif');
		}
	}
}

<?php

namespace Elgg\Bookmarks;

/**
 * Hook callbacks for ECML
 *
 * @since 4.0
 *
 * @internal
 */
class ECML {

	/**
	 * Register views for ECML
	 *
	 * @param \Elgg\Hook $hook 'get_views', 'ecml'
	 *
	 * @return array
	 */
	public static function getViews(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
	
		$return_value['object/bookmarks'] = elgg_echo('item:object:bookmarks');
	
		return $return_value;
	}
}

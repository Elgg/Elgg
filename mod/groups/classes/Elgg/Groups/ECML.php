<?php

namespace Elgg\Groups;

/**
 * Handle ECML related hooks
 *
 * @since 4.0
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
	
		$return_value['groups/groupprofile'] = elgg_echo('groups:ecml:groupprofile');
	
		return $return_value;
	}
}

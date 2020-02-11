<?php

namespace Elgg\Messages;

/**
 * Hook callbacks for ECML
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
	
		$return_value['messages/messages'] = elgg_echo('messages');
	
		return $return_value;
	}
}

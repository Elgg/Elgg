<?php

namespace Elgg\Friends;

/**
 * Handle access
 *
 * @since 4.0
 * @internal
 */
class Access {

	/**
	 * Register friends to the write access array
	 *
	 * @param \Elgg\Hook $hook 'access:collections:write:subtypes', 'user'
	 *
	 * @return array
	 */
	public static function registerAccessCollectionType(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return[] = 'friends';
		return $return;
	}
}

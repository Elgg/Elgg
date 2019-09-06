<?php

namespace Elgg\Friends\Collections;

use Elgg\Hook;

/**
 * Handles write access subtype registration
 */
class WriteAccess {

	/**
	 * Register subtype
	 *
	 * @param \Elgg\Hook $hook 'access:collections:write:subtypes' 'user'
	 *
	 * @return array
	 *
	 * @since 3.2
	 */
	public function __invoke(Hook $hook) {

		$return = $hook->getValue();
		$return[] = 'friends_collection';
		return $return;
	}
}

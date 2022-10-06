<?php

namespace Elgg\Friends\Collections;

/**
 * Handles write access subtype registration
 */
class WriteAccess {

	/**
	 * Register subtype
	 *
	 * @param \Elgg\Event $event 'access:collections:write:subtypes' 'user'
	 *
	 * @return array
	 *
	 * @since 3.2
	 */
	public function __invoke(\Elgg\Event $event) {

		$return = $event->getValue();
		$return[] = 'friends_collection';
		return $return;
	}
}

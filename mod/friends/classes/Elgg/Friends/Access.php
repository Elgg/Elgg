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
	 * @param \Elgg\Event $event 'access:collections:write:subtypes', 'user'
	 *
	 * @return array
	 */
	public static function registerAccessCollectionType(\Elgg\Event $event) {
		$return = $event->getValue();
		$return[] = 'friends';
		return $return;
	}
}

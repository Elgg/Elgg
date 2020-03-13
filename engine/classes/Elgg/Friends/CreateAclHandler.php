<?php

namespace Elgg\Friends;

/**
 * Creates ACL for friends
 *
 * @since 4.0
 */
class CreateAclHandler {
	
	/**
	 * Creates a Friends ACL for a user
	 *
	 * @param \Elgg\Event $event 'create', 'user'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		create_access_collection('friends', $user->guid, 'friends');
	}
}

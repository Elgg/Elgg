<?php

namespace Elgg\Friends;

/**
 * Modifies ACL membership
 *
 * @since 4.0
 */
class AddToAclHandler {
	
	/**
	 * Adds the friend to the user friend ACL
	 *
	 * @param \Elgg\Event $event 'create', 'relationship'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$relationship_object = $event->getObject();
		if (!$relationship_object instanceof \ElggRelationship) {
			return;
		}
		
		if ($relationship_object->relationship !== 'friend') {
			return;
		}
		
		$user = get_user($relationship_object->guid_one);
		$friend = get_user($relationship_object->guid_two);
		
		if (!$user || !$friend) {
			return;
		}
		
		$acl = $user->getOwnedAccessCollection('friends');
		if (empty($acl)) {
			return;
		}
		
		$acl->addMember($friend->guid);
	}
}

<?php

namespace Elgg\InviteFriends;

/**
 * Hook callbacks for users
 *
 * @since 4.0
 * @internal
 */
class Users {

	/**
	 * Add friends if invite code was set
	 *
	 * @param \Elgg\Hook $hook 'register', 'user'
	 *
	 * @return void
	 */
	public static function addFriendsOnRegister(\Elgg\Hook $hook) {
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$friend_guid = $hook->getParam('friend_guid');
		$invite_code = $hook->getParam('invitecode');
		
		if (!$friend_guid) {
			return;
		}
		
		$friend_user = get_user($friend_guid);
		if (!($friend_user instanceof \ElggUser)) {
			return;
		}
	
		if (!elgg_validate_invite_code($friend_user->username, $invite_code)) {
			return;
		}
		
		// Make mutual friends
		$user->addFriend($friend_guid, true);
		$friend_user->addFriend($user->guid, true);
	}
}

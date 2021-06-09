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
	 * Track future friends if invite code was set
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
		
		$friend_user = get_user((int) $hook->getParam('friend_guid'));
		if (!$friend_user instanceof \ElggUser) {
			return;
		}
			
		if (!elgg_validate_invite_code($friend_user->username, $hook->getParam('invitecode'))) {
			return;
		}
		
		// keep track of user being registered with an invite code for later (during validation)
		$user->addRelationship($friend_user->guid, 'invited_friend');
	}

	/**
	 * Add friends if invite code was set
	 *
	 * @param \Elgg\Event $event 'validate:after', 'user'
	 *
	 * @return void
	 */
	public static function addFriendsAfterValidation(\Elgg\Event $event) {
		$user = $event->getObject();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		$future_friends = $user->getEntitiesFromRelationship([
			'type' => 'user',
			'relationship' => 'invited_friend',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);
		
		/* @var $friend \ElggUser */
		foreach ($future_friends as $friend) {
			// Make mutual friends
			$user->addFriend($friend->guid, true);
			$friend->addFriend($user->guid, true);
		
			$friend->removeRelationship($user->guid, 'invited_friend');
		}
	}
}

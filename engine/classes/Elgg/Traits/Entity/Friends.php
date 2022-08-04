<?php

namespace Elgg\Traits\Entity;

/**
 * ElggEntity functions with helper functions (currently only applied on users)
 *
 * @since 4.0
 */
trait Friends {
	
	/**
	 * Adds a user as a friend
	 *
	 * @param int  $friend_guid       The GUID of the user to add
	 * @param bool $create_river_item Create the river item announcing this friendship
	 *
	 * @return bool
	 */
	public function addFriend(int $friend_guid, bool $create_river_item = false) {
		if (!get_user($friend_guid)) {
			return false;
		}

		if (!$this->addRelationship($friend_guid, 'friend')) {
			return false;
		}

		if ($create_river_item) {
			elgg_create_river_item([
				'view' => 'river/relationship/friend/create',
				'action_type' => 'friend',
				'subject_guid' => $this->guid,
				'object_guid' => $friend_guid,
			]);
		}

		return true;
	}

	/**
	 * Removes a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to remove
	 *
	 * @return bool
	 */
	public function removeFriend(int $friend_guid): bool {
		return $this->removeRelationship($friend_guid, 'friend');
	}

	/**
	 * Determines whether this user is friends with another user
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendsWith(int $user_guid): bool {
		return $this->hasRelationship($user_guid, 'friend');
	}

	/**
	 * Determines whether or not this user is another user's friend
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendOf(int $user_guid): bool {
		return (bool) _elgg_services()->relationshipsTable->check($user_guid, 'friend', $this->guid);
	}
}

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

		if (!add_entity_relationship($this->guid, 'friend', $friend_guid)) {
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
	 * Determines whether or not this user is a friend of the currently logged in user
	 *
	 * @return bool
	 */
	public function isFriend(): bool {
		return $this->isFriendOf(_elgg_services()->session->getLoggedInUserGuid());
	}

	/**
	 * Determines whether this user is friends with another user
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendsWith(int $user_guid): bool {
		return (bool) check_entity_relationship($this->guid, 'friend', $user_guid);
	}

	/**
	 * Determines whether or not this user is another user's friend
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendOf(int $user_guid): bool {
		return (bool) check_entity_relationship($user_guid, 'friend', $this->guid);
	}

	/**
	 * Gets this entity's friends
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend' and type to 'user'.
	 *
	 * @return \ElggUser[]|int|mixed
	 */
	public function getFriends(array $options = []) {
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->guid;
		$options['type'] = 'user';

		return elgg_get_entities($options);
	}

	/**
	 * Gets users who have made this entity a friend
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend', type to 'user'
	 *                       and inverse_relationship to true.
	 *
	 * @return \ElggUser[]|int|mixed
	 */
	public function getFriendsOf(array $options = []) {
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->guid;
		$options['inverse_relationship'] = true;
		$options['type'] = 'user';

		return elgg_get_entities($options);
	}
	
	/**
	 * Get an array of \ElggObjects owned by this entity's friends.
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, type to 'object', relationship name to 'friend'
	 *                       and relationship_join_on to 'container_guid'.
	 *
	 * @return \ElggObject[]|int|mixed
	 */
	public function getFriendsObjects(array $options = []) {
		$options['type'] = 'object';
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->guid;
		$options['relationship_join_on'] = 'container_guid';

		return elgg_get_entities($options);
	}
}

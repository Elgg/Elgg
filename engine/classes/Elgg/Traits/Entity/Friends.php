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
	 * Determines whether or not this user is a friend of the currently logged in user
	 *
	 * @return bool
	 * @deprecated 4.3 use \ElggUser->isFriendOf()
	 */
	public function isFriend(): bool {
		elgg_deprecated_notice(__CLASS__ . '->' . __FUNCTION__ . '() has been deprecated. Use ' . __CLASS__ . '->isFriendOf()', '4.3');

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

	/**
	 * Gets this entity's friends
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend' and type to 'user'.
	 *
	 * @return \ElggUser[]|int|mixed
	 * @deprecated 4.3 use \ElggEntity->getEntitiesFromRelationship()
	 */
	public function getFriends(array $options = []) {
		elgg_deprecated_notice(__CLASS__ . '->' . __FUNCTION__ . '() has been deprecated. Use ' . __CLASS__ . '->getEntitiesFromRelationship()', '4.3');

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
	 * @deprecated 4.3 use \ElggEntity->getEntitiesFromRelationship()
	 */
	public function getFriendsOf(array $options = []) {
		elgg_deprecated_notice(__CLASS__ . '->' . __FUNCTION__ . '() has been deprecated. Use ' . __CLASS__ . '->getEntitiesFromRelationship()', '4.3');

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
	 * @deprecated 4.3 use \ElggEntity->getEntitiesFromRelationship()
	 */
	public function getFriendsObjects(array $options = []) {
		elgg_deprecated_notice(__CLASS__ . '->' . __FUNCTION__ . '() has been deprecated. Use ' . __CLASS__ . '->getEntitiesFromRelationship()', '4.3');

		$options['type'] = 'object';
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->guid;
		$options['relationship_join_on'] = 'container_guid';

		return elgg_get_entities($options);
	}
}

<?php
/**
 * An interface for objects that behave as elements within a social network that have a profile.
 *
 * @package    Elgg.Core
 * @subpackage SocialModel.Friendable
 */
interface Friendable {
	/**
	 * Adds a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to add
	 *
	 * @return bool
	 */
	public function addFriend($friend_guid);

	/**
	 * Removes a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to remove
	 *
	 * @return bool
	 */
	public function removeFriend($friend_guid);

	/**
	 * Determines whether or not the current user is a friend of this entity
	 *
	 * @return bool
	 */
	public function isFriend();

	/**
	 * Determines whether or not this entity is friends with a particular entity
	 *
	 * @param int $user_guid The GUID of the entity this entity may or may not be friends with
	 *
	 * @return bool
	 */
	public function isFriendsWith($user_guid);

	/**
	 * Determines whether or not a foreign entity has made this one a friend
	 *
	 * @param int $user_guid The GUID of the foreign entity
	 *
	 * @return bool
	 */
	public function isFriendOf($user_guid);

	/**
	 * Gets this entity's friends
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend' and type to 'user'.
	 *
	 * @return array|false Array of \ElggUser, or false, depending on success
	 */
	public function getFriends(array $options = []);

	/**
	 * Gets users who have made this entity a friend
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend', type to 'user'
	 *                       and inverse_relationship to true.
	 *
	 * @return array|false Array of \ElggUser, or false, depending on success
	 */
	public function getFriendsOf(array $options = []);

	/**
	 * Get an array of \ElggObject owned by this entity.
	 *
	 * @param array $options Options array. See elgg_get_entities() for a list of options.
	 *                       'type' is set to object and owner_guid to this entity.
	 *
	 * @return array|false
	 */
	public function getObjects(array $options = []);

	/**
	 * Get an array of \ElggObjects owned by this entity's friends.
	 *
	 * @param array $options Options array. See elgg_get_entities()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, type to 'object', relationship name to 'friend'
	 *                       and relationship_join_on to 'container_guid'.
	 *
	 * @return array|false
	 */
	public function getFriendsObjects(array $options = []);
}

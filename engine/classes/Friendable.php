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
	 * Returns this entity's friends
	 *
	 * @param string $subtype The subtype of entity to return
	 * @param int    $limit   The number of entities to return
	 * @param int    $offset  Indexing offset
	 *
	 * @return array|false
	 */
	public function getFriends($subtype = "", $limit = 10, $offset = 0);

	/**
	 * Returns entities that have made this entity a friend
	 *
	 * @param string $subtype The subtype of entity to return
	 * @param int    $limit   The number of entities to return
	 * @param int    $offset  Indexing offset
	 *
	 * @return array|false
	 */
	public function getFriendsOf($subtype = "", $limit = 10, $offset = 0);

	/**
	 * Returns objects in this entity's container
	 *
	 * @param string $subtype The subtype of entity to return
	 * @param int    $limit   The number of entities to return
	 * @param int    $offset  Indexing offset
	 *
	 * @return array|false
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0);

	/**
	 * Returns objects in the containers of this entity's friends
	 *
	 * @param string $subtype The subtype of entity to return
	 * @param int    $limit   The number of entities to return
	 * @param int    $offset  Indexing offset
	 *
	 * @return array|false
	 */
	public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0);

	/**
	 * Returns the number of object entities in this entity's container
	 *
	 * @param string $subtype The subtype of entity to count
	 *
	 * @return int
	 */
	public function countObjects($subtype = "");
}

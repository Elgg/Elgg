<?php
/**
 * A user entity
 *
 * @property      string $name             The display name that the user will be known by in the network
 * @property      string $username         The short, reference name for the user in the network
 * @property      string $email            The email address to which Elgg will send email notifications
 * @property      string $language         The language preference of the user (ISO 639-1 formatted)
 * @property      string $banned           'yes' if the user is banned from the network, 'no' otherwise
 * @property      string $admin            'yes' if the user is an administrator of the network, 'no' otherwise
 * @property-read string $password         The legacy (salted MD5) password hash of the user
 * @property-read string $salt             The salt used to create the legacy password hash
 * @property-read string $password_hash    The hashed password of the user
 * @property-read int    $prev_last_action A UNIX timestamp of the previous last action
 * @property-read int    $last_login       A UNIX timestamp of the last login
 * @property-read int    $prev_last_login  A UNIX timestamp of the previous login
 */
class ElggUser extends \ElggEntity
	implements Friendable {

	/**
	 * {@inheritdoc}
	 */
	public function getType() {
		return 'user';
	}

	/**
	 * Get user language or default to site language
	 *
	 * @param string $fallback If this is provided, it will be returned if the user doesn't have a language set.
	 *                         If null, the site language will be returned.
	 *
	 * @return string
	 */
	public function getLanguage($fallback = null) {
		if (!empty($this->language)) {
			return $this->language;
		}
		if ($fallback !== null) {
			return $fallback;
		}
		return _elgg_config()->language;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		if (!array_key_exists($name, $this->attributes)) {
			parent::__set($name, $value);
			return;
		}

		switch ($name) {
			case 'prev_last_action':
			case 'last_login':
			case 'prev_last_login':
				if ($value !== null) {
					$this->attributes[$name] = (int) $value;
				} else {
					$this->attributes[$name] = null;
				}
				break;

			case 'salt':
			case 'password':
				_elgg_services()->logger->error("User entities no longer contain salt/password");
				break;

			// setting this not supported
			case 'password_hash':
				_elgg_services()->logger->error("password_hash is a readonly attribute.");
				break;

			default:
				parent::__set($name, $value);
				break;
		}
	}

	/**
	 * Ban this user.
	 *
	 * @param string $reason Optional reason
	 *
	 * @return bool
	 */
	public function ban($reason = "") {
		return ban_user($this->guid, $reason);
	}

	/**
	 * Unban this user.
	 *
	 * @return bool
	 */
	public function unban() {
		return unban_user($this->guid);
	}

	/**
	 * Is this user banned or not?
	 *
	 * @return bool
	 */
	public function isBanned() {
		return $this->banned == 'yes';
	}

	/**
	 * Is this user admin?
	 *
	 * @return bool
	 */
	public function isAdmin() {

		// for backward compatibility we need to pull this directly
		// from the attributes instead of using the magic methods.
		// this can be removed in 1.9
		// return $this->admin == 'yes';
		return $this->attributes['admin'] == 'yes';
	}

	/**
	 * Make the user an admin
	 *
	 * @return bool
	 */
	public function makeAdmin() {
		
		if ($this->isAdmin()) {
			return true;
		}

		// If already saved, use the standard function.
		if ($this->guid && !make_user_admin($this->guid)) {
			return false;
		}

		// need to manually set attributes since they've already been loaded.
		$this->attributes['admin'] = 'yes';

		return true;
	}

	/**
	 * Remove the admin flag for user
	 *
	 * @return bool
	 */
	public function removeAdmin() {

		if (!$this->isAdmin()) {
			return true;
		}

		// If already saved, use the standard function.
		if ($this->guid && !remove_user_admin($this->guid)) {
			return false;
		}

		// need to manually set attributes since they've already been loaded.
		$this->attributes['admin'] = 'no';

		return true;
	}

	/**
	 * Adds a user as a friend
	 *
	 * @param int  $friend_guid       The GUID of the user to add
	 * @param bool $create_river_item Create the river item announcing this friendship
	 *
	 * @return bool
	 */
	public function addFriend($friend_guid, $create_river_item = false) {
		if (!get_user($friend_guid)) {
			return false;
		}

		if (!add_entity_relationship($this->guid, "friend", $friend_guid)) {
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
	 * @return bool
	 */
	public function removeFriend($friend_guid) {
		return $this->removeRelationship($friend_guid, 'friend');
	}

	/**
	 * Determines whether or not this user is a friend of the currently logged in user
	 *
	 * @return bool
	 */
	public function isFriend() {
		return $this->isFriendOf(_elgg_services()->session->getLoggedInUserGuid());
	}

	/**
	 * Determines whether this user is friends with another user
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendsWith($user_guid) {
		return (bool) check_entity_relationship($this->guid, "friend", $user_guid);
	}

	/**
	 * Determines whether or not this user is another user's friend
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendOf($user_guid) {
		return (bool) check_entity_relationship($user_guid, "friend", $this->guid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFriends(array $options = []) {
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->getGUID();
		$options['type'] = 'user';

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFriendsOf(array $options = []) {
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->getGUID();
		$options['inverse_relationship'] = true;
		$options['type'] = 'user';

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Gets the user's groups
	 *
	 * @param array $options Options array.
	 *
	 * @return array|false Array of \ElggGroup, or false, depending on success
	 */
	public function getGroups(array $options = []) {
		$options['type'] = 'group';
		$options['relationship'] = 'member';
		$options['relationship_guid'] = $this->guid;

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjects(array $options = []) {
		$options['type'] = 'object';
		$options['owner_guid'] = $this->getGUID();

		return elgg_get_entities($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFriendsObjects(array $options = []) {
		$options['type'] = 'object';
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->getGUID();
		$options['relationship_join_on'] = 'container_guid';

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Get a user's owner GUID
	 *
	 * Returns it's own GUID if the user is not owned.
	 *
	 * @return int
	 */
	public function getOwnerGUID() {
		if ($this->owner_guid == 0) {
			return $this->guid;
		}

		return $this->owner_guid;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject($object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->username = $this->username;
		$object->language = $this->language;
		unset($object->read_access);
		return $object;
	}

	/**
	 * Can a user comment on this user?
	 *
	 * @see \ElggEntity::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0, $default = null) {
		$result = parent::canComment($user_guid, $default);
		if ($result !== null) {
			return $result;
		}
		return false;
	}

	/**
	 * Set the necessary attribute to store a hash of the user's password.
	 *
	 * @tip You must save() to persist the attribute
	 *
	 * @param string $password The password to be hashed
	 * @return void
	 * @since 1.10.0
	 */
	public function setPassword($password) {
		$this->attributes['password_hash'] = _elgg_services()->passwords->generateHash($password);
	}

	/**
	 * Enable or disable a notification delivery method
	 *
	 * @param string $method  Method name
	 * @param bool   $enabled Enabled or disabled
	 * @return bool
	 */
	public function setNotificationSetting($method, $enabled = true) {
		$this->{"notification:method:$method"} = (int) $enabled;
		return (bool) $this->save();
	}

	/**
	 * Returns users's notification settings
	 * <code>
	 *    [
	 *       'email' => true, // enabled
	 *       'ajax' => false, // disabled
	 *    ]
	 * </code>
	 *
	 * @return array
	 */
	public function getNotificationSettings() {

		$settings = [];

		$methods = _elgg_services()->notifications->getMethods();
		foreach ($methods as $method) {
			$settings[$method] = (bool) $this->{"notification:method:$method"};
		}

		return $settings;
	
	}
}

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
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'user';
		
		// Before Elgg 3.0 this was handled by database logic
		$this->banned = 'no';
		$this->admin = 'no';
		$this->language = elgg_get_config('language');
		$this->prev_last_action = 0;
		$this->last_login = 0;
		$this->prev_last_login = 0;
	}

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
		return elgg_get_config('language');
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'salt':
			case 'password':
				_elgg_services()->logger->error("User entities no longer contain {$name}");
				return;
			case 'password_hash':
				_elgg_services()->logger->error("password_hash is a readonly attribute.");
				return;
			case 'email':
				try {
					elgg()->accounts->assertValidEmail($value);
				} catch (RegistrationException $ex) {
					throw new InvalidParameterException($ex->getCode());
				}
				break;
			case 'username':
				try {
					elgg()->accounts->assertValidUsername($value);
				} catch (RegistrationException $ex) {
					throw new InvalidParameterException($ex->getCode());
				}
				$existing_user = get_user_by_username($value);
				if ($existing_user && ($existing_user->guid !== $this->guid)) {
					throw new InvalidParameterException("{$name} is supposed to be unique for ElggUser");
				}
				break;
			case 'admin':
			case 'banned':
				if (!in_array($value, ['yes', 'no'], true)) {
					throw new InvalidArgumentException("{$name} only supports 'yes' or 'no' value");
				}
				break;
		}
		
		parent::__set($name, $value);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getURL() {
		
		$result = parent::getURL();
		if ($result !== '') {
			return $result;
		}
		
		return elgg_normalize_url("user/view/{$this->guid}");
	}

	/**
	 * Ban this user.
	 *
	 * @param string $reason Optional reason
	 *
	 * @return bool
	 */
	public function ban($reason = '') {

		if (!$this->canEdit()) {
			return false;
		}
		
		if (!_elgg_services()->events->trigger('ban', 'user', $this)) {
			return false;
		}

		$this->ban_reason = $reason;
		$this->banned = 'yes';
				
		$this->invalidateCache();

		return true;
	}

	/**
	 * Unban this user.
	 *
	 * @return bool
	 */
	public function unban() {
		
		if (!$this->canEdit()) {
			return false;
		}

		if (!_elgg_services()->events->trigger('unban', 'user', $this)) {
			return false;
		}

		unset($this->ban_reason);
		$this->banned = 'no';
				
		$this->invalidateCache();

		return true;
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
		$ia = _elgg_services()->session->setIgnoreAccess(true);
		$is_admin = ($this->admin == 'yes');
		_elgg_services()->session->setIgnoreAccess($ia);
		
		return $is_admin;
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

		if (!_elgg_services()->events->trigger('make_admin', 'user', $this)) {
			return false;
		}

		$this->admin = 'yes';

		$this->invalidateCache();
		
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

		if (!_elgg_services()->events->trigger('remove_admin', 'user', $this)) {
			return false;
		}

		$this->admin = 'no';

		$this->invalidateCache();
		
		return true;
	}
	
	/**
	 * Sets the last logon time of the user to right now.
	 *
	 * @return void
	 */
	public function setLastLogin() {
		
		$time = $this->getCurrentTime()->getTimestamp();
		
		if ($this->last_login == $time) {
			// no change required
			return;
		}
		
		// these writes actually work, we just type hint read-only.
		$this->prev_last_login = $this->last_login;
		$this->last_login = $time;
	}
	
	/**
	 * Sets the last action time of the given user to right now.
	 *
	 * @see _elgg_session_boot() The session boot calls this at the beginning of every request
	 *
	 * @return void
	 */
	public function setLastAction() {
		
		$time = $this->getCurrentTime()->getTimestamp();
		
		if ($this->last_action == $time) {
			// no change required
			return;
		}
		
		$user = $this;
		
		elgg_register_event_handler('shutdown', 'system', function () use ($user, $time) {
			// these writes actually work, we just type hint read-only.
			$user->prev_last_action = $user->last_action;
		
			$user->updateLastAction($time);
		});
	}
	
	/**
	 * Gets the validation status of a user.
	 *
	 * @return bool|null Null means status was not set for this user.
	 */
	public function isValidated() {
		if (!isset($this->validated)) {
			return null;
		}
		return (bool) $this->validated;
	}
	
	/**
	 * Set the validation status for a user.
	 *
	 * @param bool   $status    Validated (true) or unvalidated (false)
	 * @param string $method    Optional method to say how a user was validated
	 * @return void
	 */
	public function setValidationStatus($status, $method = '') {
		
		$this->validated = $status;
		$this->validated_method = $method;
		
		if ((bool) $status) {
			// make sure the user is enabled
			if (!$this->isEnabled()) {
				$this->enable();
			}
			
			// let the system know the user is validated
			_elgg_services()->events->triggerAfter('validate', 'user', $this);
		} else {
			_elgg_services()->events->triggerAfter('invalidate', 'user', $this);
		}
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

		return elgg_get_entities($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFriendsOf(array $options = []) {
		$options['relationship'] = 'friend';
		$options['relationship_guid'] = $this->getGUID();
		$options['inverse_relationship'] = true;
		$options['type'] = 'user';

		return elgg_get_entities($options);
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

		return elgg_get_entities($options);
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

		return elgg_get_entities($options);
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
	protected function prepareObject(\Elgg\Export\Entity $object) {
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
		return false;
	}

	/**
	 * Set the necessary metadata to store a hash of the user's password.
	 *
	 * @param string $password The password to be hashed
	 * @return void
	 * @since 1.10.0
	 */
	public function setPassword($password) {
		$this->setMetadata('password_hash', _elgg_services()->passwords->generateHash($password));
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

	/**
	 * Cache the entity in a session and persisted caches
	 *
	 * @param bool $persist Store in persistent cache
	 *
	 * @return void
	 * @access private
	 * @internal
	 */
	public function cache($persist = true) {
		if ($persist && $this->username) {
			$tmp = $this->volatile;

			// don't store volatile data
			$this->volatile = [];

			_elgg_services()->dataCache->usernames->save($this->username, $this);

			$this->volatile = $tmp;
		}

		parent::cache($persist);
	}

	/**
	 * Invalidate cache for entity
	 *
	 * @return void
	 * @internal
	 */
	public function invalidateCache() {
		if ($this->username) {
			_elgg_services()->dataCache->usernames->delete($this->username);
		}

		parent::invalidateCache();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function delete($recursive = true) {
		$result = parent::delete($recursive);
		if ($result) {
			// cleanup remember me cookie records
			_elgg_services()->persistentLogin->removeAllHashes($this);
		}
		
		return $result;
	}
}

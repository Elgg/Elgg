<?php
/**
 * \ElggUser
 *
 * Representation of a "user" in the system.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.User
 * 
 * @property      string $name          The display name that the user will be known by in the network
 * @property      string $username      The short, reference name for the user in the network
 * @property      string $email         The email address to which Elgg will send email notifications
 * @property      string $language      The language preference of the user (ISO 639-1 formatted)
 * @property      string $banned        'yes' if the user is banned from the network, 'no' otherwise
 * @property      string $admin         'yes' if the user is an administrator of the network, 'no' otherwise
 * @property-read string $password      The legacy (salted MD5) password hash of the user
 * @property-read string $salt          The salt used to create the legacy password hash
 * @property-read string $password_hash The hashed password of the user
 */
class ElggUser extends \ElggEntity
	implements Friendable {

	/**
	 * Initialize the attributes array.
	 * This is vital to distinguish between metadata and base attributes.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "user";
		$this->attributes += self::getExternalAttributes();
		$this->tables_split = 2;
	}

	/**
	 * Get default values for attributes stored in a separate table
	 *
	 * @return array
	 * @access private
	 *
	 * @see \Elgg\Database\EntityTable::getEntities
	 */
	final public static function getExternalAttributes() {
		return [
			'name' => null,
			'username' => null,
			'password' => null,
			'salt' => null,
			'password_hash' => null,
			'email' => null,
			'language' => null,
			'banned' => "no",
			'admin' => 'no',
			'prev_last_action' => null,
			'last_login' => null,
			'prev_last_login' => null,
		];
	}

	/**
	 * Construct a new user entity
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * @param \stdClass $row Database row result. Default is null to create a new user.
	 *
	 * @throws IOException|InvalidParameterException if there was a problem creating the user.
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($row)) {
			// Is $row is a DB entity row
			if ($row instanceof \stdClass) {
				// Load the rest
				if (!$this->load($row)) {
					$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
					throw new \IOException($msg);
				}
			} else if (is_string($row)) {
				// $row is a username
				elgg_deprecated_notice('Passing a username to constructor is deprecated. Use get_user_by_username()', 1.9);
				$user = get_user_by_username($row);
				if ($user) {
					foreach ($user->attributes as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			} else if ($row instanceof \ElggUser) {
				// $row is an \ElggUser so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the \ElggUser constructor was deprecated. Please use the clone method.', 1.7);
				foreach ($row->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if (is_numeric($row)) {
				// $row is a GUID so load entity
				elgg_deprecated_notice('Passing a GUID to constructor is deprecated. Use get_entity()', 1.9);
				if (!$this->load($row)) {
					throw new \IOException("Failed to load new " . get_class() . " from GUID:" . $row);
				}
			} else {
				throw new \InvalidParameterException("Unrecognized value passed to constuctor.");
			}
		}
	}

	/**
	 * Load the \ElggUser data from the database
	 *
	 * @param mixed $guid \ElggUser GUID or \stdClass database row from entity table
	 *
	 * @return bool
	 */
	protected function load($guid) {
		$attr_loader = new \Elgg\AttributeLoader(get_class(), 'user', $this->attributes);
		$attr_loader->secondary_loader = 'get_user_entity_as_row';

		$attrs = $attr_loader->getRequiredAttributes($guid);
		if (!$attrs) {
			return false;
		}

		$this->attributes = $attrs;
		$this->tables_loaded = 2;
		$this->loadAdditionalSelectValues($attr_loader->getAdditionalSelectValues());
		_elgg_cache_entity($this);

		return true;
	}


	/**
	 * {@inheritdoc}
	 */
	protected function create() {
		global $CONFIG;
	
		$guid = parent::create();
		$name = sanitize_string($this->name);
		$username = sanitize_string($this->username);
		$password = sanitize_string($this->password);
		$salt = sanitize_string($this->salt);
		$password_hash = sanitize_string($this->password_hash);
		$email = sanitize_string($this->email);
		$language = sanitize_string($this->language);

		$query = "INSERT into {$CONFIG->dbprefix}users_entity
			(guid, name, username, password, salt, password_hash, email, language)
			values ($guid, '$name', '$username', '$password', '$salt', '$password_hash', '$email', '$language')";

		$result = $this->getDatabase()->insertData($query);
		if ($result === false) {
			// TODO(evan): Throw an exception here?
			return false;
		}
		
		return $guid;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function update() {
		global $CONFIG;
		
		if (!parent::update()) {
			return false;
		}
		
		$guid = (int)$this->guid;
		$name = sanitize_string($this->name);
		$username = sanitize_string($this->username);
		$password = sanitize_string($this->password);
		$salt = sanitize_string($this->salt);
		$password_hash = sanitize_string($this->password_hash);
		$email = sanitize_string($this->email);
		$language = sanitize_string($this->language);

		$query = "UPDATE {$CONFIG->dbprefix}users_entity
			SET name='$name', username='$username', password='$password', salt='$salt',
			password_hash='$password_hash', email='$email', language='$language'
			WHERE guid = $guid";

		return $this->getDatabase()->updateData($query) !== false;
	}

	/**
	 * User specific override of the entity delete method.
	 *
	 * @return bool
	 */
	public function delete() {
		global $USERNAME_TO_GUID_MAP_CACHE;

		// clear cache
		if (isset($USERNAME_TO_GUID_MAP_CACHE[$this->username])) {
			unset($USERNAME_TO_GUID_MAP_CACHE[$this->username]);
		}

		// Delete entity
		return parent::delete();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayName($displayName) {
		$this->name = $displayName;
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
					$this->attributes[$name] = (int)$value;
				} else {
					$this->attributes[$name] = null;
				}
				break;

			case 'salt':
			case 'password':
				elgg_deprecated_notice("Setting salt/password directly is deprecated. Use ElggUser::setPassword().", "1.10");
				$this->attributes[$name] = $value;

				// this is emptied so that the user is not left with two usable hashes
				$this->attributes['password_hash'] = '';

				break;

			// setting this not supported
			case 'password_hash':
				_elgg_services()->logger->error("password_hash is now an attribute of ElggUser and cannot be set.");
				return;
				break;

			default:
				parent::__set($name, $value);
				break;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function set($name, $value) {
		elgg_deprecated_notice("Use -> instead of set()", 1.9);
		$this->__set($name, $value);

		return true;
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
		// If already saved, use the standard function.
		if ($this->guid && !remove_user_admin($this->guid)) {
			return false;
		}

		// need to manually set attributes since they've already been loaded.
		$this->attributes['admin'] = 'no';

		return true;
	}

	/**
	 * Get sites that this user is a member of
	 *
	 * @param array $options Options array. Used to be $subtype
	 * @param int   $limit   The number of results to return (deprecated)
	 * @param int   $offset  Any indexing offset (deprecated)
	 *
	 * @return array
	 */
	public function getSites($options = "", $limit = 10, $offset = 0) {
		if (is_string($options)) {
			elgg_deprecated_notice('\ElggUser::getSites() takes an options array', 1.9);
			return get_user_sites($this->getGUID(), $limit, $offset);
		}

		return parent::getSites($options);
	}

	/**
	 * Add this user to a particular site
	 *
	 * @param \ElggSite $site The site to add this user to. This used to be the
	 *                       the site guid (still supported by deprecated)
	 * @return bool
	 */
	public function addToSite($site) {
		if (is_numeric($site)) {
			elgg_deprecated_notice('\ElggUser::addToSite() takes a site entity', 1.9);
			return add_site_user($site, $this->getGUID());
		}

		return parent::addToSite($site);
	}

	/**
	 * Remove this user from a particular site
	 *
	 * @param \ElggSite $site The site to remove the user from. Used to be site GUID
	 *
	 * @return bool
	 */
	public function removeFromSite($site) {
		if (is_numeric($site)) {
			elgg_deprecated_notice('\ElggUser::removeFromSite() takes a site entity', 1.9);
			return remove_site_user($site, $this->guid);
		}

		return parent::removeFromSite($site);
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
			elgg_create_river_item(array(
				'view' => 'river/relationship/friend/create',
				'action_type' => 'friend',
				'subject_guid' => $this->guid,
				'object_guid' => $friend_guid,
			));
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
	public function removeFriend($friend_guid) {
		if (!get_user($friend_guid)) {
			return false;
		}

		// @todo this should be done with a plugin hook handler on the delete relationship
		// perform cleanup for access lists.
		$collections = get_user_access_collections($this->guid);
		if ($collections) {
			foreach ($collections as $collection) {
				remove_user_from_access_collection($friend_guid, $collection->id);
			}
		}

		return remove_entity_relationship($this->guid, "friend", $friend_guid);
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
		return (bool)check_entity_relationship($this->guid, "friend", $user_guid);
	}

	/**
	 * Determines whether or not this user is another user's friend
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	public function isFriendOf($user_guid) {
		return (bool)check_entity_relationship($user_guid, "friend", $this->guid);
	}

	/**
	 * Gets this user's friends
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationship()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend' and type to 'user'.
	 * @param int   $limit   The number of users to retrieve (deprecated)
	 * @param int   $offset  Indexing offset, if any (deprecated)
	 *
	 * @return array|false Array of \ElggUser, or false, depending on success
	 */
	public function getFriends($options = array(), $limit = 10, $offset = 0) {
		if (is_array($options)) {
			$options['relationship'] = 'friend';
			$options['relationship_guid'] = $this->getGUID();
			$options['type'] = 'user';
			return elgg_get_entities_from_relationship($options);
		} else {
			elgg_deprecated_notice("\ElggUser::getFriends takes an options array", 1.9);
			return elgg_get_entities_from_relationship(array(
				'relationship' => 'friend',
				'relationship_guid' => $this->guid,
				'type' => 'user',
				'subtype' => $options,
				'limit' => $limit,
				'offset' => $offset,
			));
		}
	}

	/**
	 * Gets users who have made this user a friend
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationship()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, relationship name to 'friend', type to 'user'
	 *                       and inverse_relationship to true.
	 * @param int   $limit   The number of users to retrieve (deprecated)
	 * @param int   $offset  Indexing offset, if any (deprecated)
	 *
	 * @return array|false Array of \ElggUser, or false, depending on success
	 */
	public function getFriendsOf($options = array(), $limit = 10, $offset = 0) {
		if (is_array($options)) {
			$options['relationship'] = 'friend';
			$options['relationship_guid'] = $this->getGUID();
			$options['inverse_relationship'] = true;
			$options['type'] = 'user';
			return elgg_get_entities_from_relationship($options);
		} else {
			elgg_deprecated_notice("\ElggUser::getFriendsOf takes an options array", 1.9);
			return elgg_get_entities_from_relationship(array(
				'relationship' => 'friend',
				'relationship_guid' => $this->guid,
				'type' => 'user',
				'subtype' => $options,
				'limit' => $limit,
				'offset' => $offset,
			));
		}
	}

	/**
	 * Lists the user's friends
	 *
	 * @param string $subtype Optionally, the user subtype (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param array  $vars    Display variables for the user view
	 *
	 * @return string Rendered list of friends
	 * @since 1.8.0
	 * @deprecated 1.9 Use elgg_list_entities_from_relationship()
	 */
	public function listFriends($subtype = "", $limit = 10, array $vars = array()) {
		elgg_deprecated_notice('\ElggUser::listFriends() is deprecated. Use elgg_list_entities_from_relationship()', 1.9);
		$defaults = array(
			'type' => 'user',
			'relationship' => 'friend',
			'relationship_guid' => $this->guid,
			'limit' => $limit,
			'full_view' => false,
		);

		$options = array_merge($defaults, $vars);

		if ($subtype) {
			$options['subtype'] = $subtype;
		}

		return elgg_list_entities_from_relationship($options);
	}

	/**
	 * Gets the user's groups
	 *
	 * @param array $options Options array. Used to be the subtype string.
	 * @param int   $limit   The number of groups to retrieve (deprecated)
	 * @param int   $offset  Indexing offset, if any (deprecated)
	 *
	 * @return array|false Array of \ElggGroup, or false, depending on success
	 */
	public function getGroups($options = "", $limit = 10, $offset = 0) {
		if (is_string($options)) {
			elgg_deprecated_notice('\ElggUser::getGroups() takes an options array', 1.9);
			$subtype = $options;
			$options = array(
				'type' => 'group',
				'relationship' => 'member',
				'relationship_guid' => $this->guid,
				'limit' => $limit,
				'offset' => $offset,
			);

			if ($subtype) {
				$options['subtype'] = $subtype;
			}
		} else {
			$options['type'] = 'group';
			$options['relationship'] = 'member';
			$options['relationship_guid'] = $this->guid;
		}

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Lists the user's groups
	 *
	 * @param string $subtype Optionally, the user subtype (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return string
	 * @deprecated 1.9 Use elgg_list_entities_from_relationship()
	 */
	public function listGroups($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice('Elgg::listGroups is deprecated. Use elgg_list_entities_from_relationship()', 1.9);
		$options = array(
			'type' => 'group',
			'relationship' => 'member',
			'relationship_guid' => $this->guid,
			'limit' => $limit,
			'offset' => $offset,
			'full_view' => false,
		);

		if ($subtype) {
			$options['subtype'] = $subtype;
		}

		return elgg_list_entities_from_relationship($options);
	}

	/**
	 * Get an array of \ElggObject owned by this user.
	 *
	 * @param array $options Options array. See elgg_get_entities() for a list of options.
	 *                       'type' is set to object and owner_guid to this entity.
	 * @param int   $limit   Number of results to return (deprecated)
	 * @param int   $offset  Any indexing offset (deprecated)
	 *
	 * @return array|false
	 */
	public function getObjects($options = array(), $limit = 10, $offset = 0) {
		if (is_array($options)) {
			$options['type'] = 'object';
			$options['owner_guid'] = $this->getGUID();
			return elgg_get_entities($options);
		} else {
			elgg_deprecated_notice("\ElggUser::getObjects takes an options array", 1.9);
			return elgg_get_entities(array(
				'type' => 'object',
				'subtype' => $options,
				'owner_guid' => $this->getGUID(),
				'limit' => $limit,
				'offset' => $offset
			));
		}
	}

	/**
	 * Get an array of \ElggObjects owned by this user's friends.
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationship()
	 *                       for a list of options. 'relationship_guid' is set to
	 *                       this entity, type to 'object', relationship name to 'friend'
	 *                       and relationship_join_on to 'container_guid'.
	 * @param int   $limit   Number of results to return (deprecated)
	 * @param int   $offset  Any indexing offset (deprecated)
	 *
	 * @return array|false
	 */
	public function getFriendsObjects($options = array(), $limit = 10, $offset = 0) {
		if (is_array($options)) {
			$options['type'] = 'object';
			$options['relationship'] = 'friend';
			$options['relationship_guid'] = $this->getGUID();
			$options['relationship_join_on'] = 'container_guid';
			return elgg_get_entities_from_relationship($options);
		} else {
			elgg_deprecated_notice("\ElggUser::getFriendsObjects takes an options array", 1.9);
			return elgg_get_entities_from_relationship(array(
				'type' => 'object',
				'subtype' => $options,
				'limit' => $limit,
				'offset' => $offset,
				'relationship' => 'friend',
				'relationship_guid' => $this->getGUID(),
				'relationship_join_on' => 'container_guid',
			));
		}
	}

	/**
	 * Counts the number of \ElggObjects owned by this user
	 *
	 * @param string $subtype The subtypes of the objects, if any
	 *
	 * @return int The number of \ElggObjects
	 * @deprecated 1.9 Use elgg_get_entities()
	 */
	public function countObjects($subtype = "") {
		elgg_deprecated_notice("\ElggUser::countObjects() is deprecated. Use elgg_get_entities()", 1.9);
		return count_user_objects($this->getGUID(), $subtype);
	}

	/**
	 * Get the collections associated with a user.
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int    $limit   The number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array|false
	 */
	public function getCollections($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("\ElggUser::getCollections() has been deprecated", 1.8);
		return false;
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
	 * If a user's owner is blank, return its own GUID as the owner
	 *
	 * @return int User GUID
	 * @deprecated 1.8 Use getOwnerGUID()
	 */
	public function getOwner() {
		elgg_deprecated_notice("\ElggUser::getOwner deprecated for \ElggUser::getOwnerGUID", 1.8);
		$this->getOwnerGUID();
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

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 * @deprecated 1.9 Use toObject()
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'username',
			'language',
		));
	}

	/**
	 * Can a user comment on this user?
	 *
	 * @see \ElggEntity::canComment()
	 * 
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		$result = parent::canComment($user_guid);
		if ($result !== null) {
			return $result;
		}
		return false;
	}

	/**
	 * Set the necessary attributes to store a hash of the user's password. Also removes
	 * the legacy hash/salt values.
	 *
	 * @tip You must save() to persist the attributes
	 *
	 * @param string $password The password to be hashed
	 * @return void
	 * @since 1.10.0
	 */
	public function setPassword($password) {
		$this->attributes['salt'] = "";
		$this->attributes['password'] = "";
		$this->attributes['password_hash'] = _elgg_services()->passwords->generateHash($password);
	}
}

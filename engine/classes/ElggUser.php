<?php
/**
 * ElggUser
 *
 * Representation of a "user" in the system.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.User
 * 
 * @property string $name     The display name that the user will be known by in the network
 * @property string $username The short, reference name for the user in the network
 * @property string $email    The email address to which Elgg will send email notifications
 * @property string $language The language preference of the user (ISO 639-1 formatted)
 * @property string $banned   'yes' if the user is banned from the network, 'no' otherwise
 * @property string $admin    'yes' if the user is an administrator of the network, 'no' otherwise
 * @property string $password The hashed password of the user
 * @property string $salt     The salt used to secure the password before hashing
 */
class ElggUser extends ElggEntity
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
		$this->attributes['name'] = null;
		$this->attributes['username'] = null;
		$this->attributes['password'] = null;
		$this->attributes['salt'] = null;
		$this->attributes['email'] = null;
		$this->attributes['language'] = null;
		$this->attributes['banned'] = "no";
		$this->attributes['admin'] = 'no';
		$this->attributes['prev_last_action'] = null;
		$this->attributes['last_login'] = null;
		$this->attributes['prev_last_login'] = null;
		$this->tables_split = 2;
	}

	/**
	 * Construct a new user entity
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * @param stdClass $row Database row result. Default is null to create a new user.
	 *
	 * @throws IOException|InvalidParameterException if there was a problem creating the user.
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($row)) {
			// Is $row is a DB entity row
			if ($row instanceof stdClass) {
				// Load the rest
				if (!$this->load($row)) {
					$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
					throw new IOException($msg);
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
			} else if ($row instanceof ElggUser) {
				// $row is an ElggUser so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the ElggUser constructor was deprecated. Please use the clone method.', 1.7);
				foreach ($row->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if (is_numeric($row)) {
				// $row is a GUID so load entity
				elgg_deprecated_notice('Passing a GUID to constructor is deprecated. Use get_entity()', 1.9);
				if (!$this->load($row)) {
					throw new IOException("Failed to load new " . get_class() . " from GUID:" . $row);
				}
			} else {
				throw new InvalidParameterException("Unrecognized value passed to constuctor.");
			}
		}
	}

	/**
	 * Load the ElggUser data from the database
	 *
	 * @param mixed $guid ElggUser GUID or stdClass database row from entity table
	 *
	 * @return bool
	 */
	protected function load($guid) {
		$attr_loader = new Elgg_AttributeLoader(get_class(), 'user', $this->attributes);
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
		$email = sanitize_string($this->email);
		$language = sanitize_string($this->language);

		$query = "INSERT into {$CONFIG->dbprefix}users_entity
			(guid, name, username, password, salt, email, language)
			values ($guid, '$name', '$username', '$password', '$salt', '$email', '$language')";

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
		$email = sanitize_string($this->email);
		$language = sanitize_string($this->language);

		$query = "UPDATE {$CONFIG->dbprefix}users_entity
			SET name='$name', username='$username', password='$password', salt='$salt',
			email='$email', language='$language'
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

		clear_user_files($this);

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
		if (array_key_exists($name, $this->attributes)) {
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
				default:
					parent::__set($name, $value);
					break;
			}
		} else {
			parent::__set($name, $value);
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
			elgg_deprecated_notice('ElggUser::getSites() takes an options array', 1.9);
			return get_user_sites($this->getGUID(), $limit, $offset);
		}

		return parent::getSites($options);
	}

	/**
	 * Add this user to a particular site
	 *
	 * @param ElggSite $site The site to add this user to. This used to be the
	 *                       the site guid (still supported by deprecated)
	 * @return bool
	 */
	public function addToSite($site) {
		if (is_numeric($site)) {
			elgg_deprecated_notice('ElggUser::addToSite() takes a site entity', 1.9);
			return add_site_user($site, $this->getGUID());
		}

		return parent::addToSite($site);
	}

	/**
	 * Remove this user from a particular site
	 *
	 * @param ElggSite $site The site to remove the user from. Used to be site GUID
	 *
	 * @return bool
	 */
	public function removeFromSite($site) {
		if (is_numeric($site)) {
			elgg_deprecated_notice('ElggUser::removeFromSite() takes a site entity', 1.9);
			return remove_site_user($site, $this->guid);
		}

		return parent::removeFromSite($site);
	}

	/**
	 * Adds a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to add
	 *
	 * @return bool
	 * @todo change to accept ElggUser
	 */
	public function addFriend($friend_guid) {
		if (!get_user($friend_guid)) {
			return false;
		}

		return add_entity_relationship($this->guid, "friend", $friend_guid);
	}

	/**
	 * Removes a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to remove
	 *
	 * @return bool
	 * @todo change to accept ElggUser
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
		return $this->isFriendOf(elgg_get_logged_in_user_guid());
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
	 * @param string $subtype Optionally, the user subtype (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return array|false Array of ElggUser, or false, depending on success
	 */
	public function getFriends($subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10, $offset = 0) {
		return elgg_get_entities_from_relationship(array(
			'relationship' => 'friend',
			'relationship_guid' => $this->guid,
			'type' => 'user',
			'subtype' => $subtype,
			'limit' => $limit,
			'offset' => $offset,
		));
	}

	/**
	 * Gets users who have made this user a friend
	 *
	 * @param string $subtype Optionally, the user subtype (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return array|false Array of ElggUser, or false, depending on success
	 */
	public function getFriendsOf($subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10, $offset = 0) {
		return elgg_get_entities_from_relationship(array(
			'relationship' => 'friend',
			'relationship_guid' => $this->guid,
			'inverse_relationship' => true,
			'type' => 'user',
			'subtype' => $subtype,
			'limit' => $limit,
			'offset' => $offset,
		));
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
		elgg_deprecated_notice('ElggUser::listFriends() is deprecated. Use elgg_list_entities_from_relationship()', 1.9);
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
	 * @return array|false Array of ElggGroup, or false, depending on success
	 */
	public function getGroups($options = "", $limit = 10, $offset = 0) {
		if (is_string($options)) {
			elgg_deprecated_notice('ElggUser::getGroups() takes an options array', 1.9);
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
	 * Get an array of ElggObject owned by this user.
	 *
	 * @param string $subtype The subtype of the objects, if any
	 * @param int    $limit   Number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array|false
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0) {
		$params = array(
			'type' => 'object',
			'subtype' => $subtype,
			'owner_guid' => $this->getGUID(),
			'limit' => $limit,
			'offset' => $offset
		);
		return elgg_get_entities($params);
	}

	/**
	 * Get an array of ElggObjects owned by this user's friends.
	 *
	 * @param string $subtype The subtype of the objects, if any
	 * @param int    $limit   Number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array|false
	 */
	public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0) {
		return elgg_get_entities_from_relationship(array(
			'type' => 'object',
			'subtype' => $subtype,
			'limit' => $limit,
			'offset' => $offset,
			'relationship' => 'friend',
			'relationship_guid' => $this->getGUID(),
			'relationship_join_on' => 'container_guid',
		));
	}

	/**
	 * Counts the number of ElggObjects owned by this user
	 *
	 * @param string $subtype The subtypes of the objects, if any
	 *
	 * @return int The number of ElggObjects
	 * @deprecated 1.9 Use elgg_get_entities()
	 */
	public function countObjects($subtype = "") {
		elgg_deprecated_notice("ElggUser::countObjects() is deprecated. Use elgg_get_entities()", 1.9);
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
		elgg_deprecated_notice("ElggUser::getCollections() has been deprecated", 1.8);
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
		elgg_deprecated_notice("ElggUser::getOwner deprecated for ElggUser::getOwnerGUID", 1.8);
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
	 * @see ElggEntity::canComment()
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
}

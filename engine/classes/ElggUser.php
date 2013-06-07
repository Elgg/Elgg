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
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "user";
		$this->attributes['name'] = NULL;
		$this->attributes['username'] = NULL;
		$this->attributes['password'] = NULL;
		$this->attributes['salt'] = NULL;
		$this->attributes['email'] = NULL;
		$this->attributes['language'] = NULL;
		$this->attributes['code'] = NULL;
		$this->attributes['banned'] = "no";
		$this->attributes['admin'] = 'no';
		$this->attributes['prev_last_action'] = NULL;
		$this->attributes['last_login'] = NULL;
		$this->attributes['prev_last_login'] = NULL;
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new user entity, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If an entity table db row then will load the rest of the data.
	 *
	 * @throws Exception if there was a problem creating the user.
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($guid)) {
			// Is $guid is a DB entity row
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid)) {
					$msg = elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid->guid));
					throw new IOException($msg);
				}
			} else if (is_string($guid)) {
				// $guid is a username
				$user = get_user_by_username($guid);
				if ($user) {
					foreach ($user->attributes as $key => $value) {
						$this->attributes[$key] = $value;
					}
				}
			} else if ($guid instanceof ElggUser) {
				// $guid is an ElggUser so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the ElggUser constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if ($guid instanceof ElggEntity) {
				// @todo why have a special case here
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggUser'));
			} else if (is_numeric($guid)) {
				// $guid is a GUID so load entity
				if (!$this->load($guid)) {
					throw new IOException(elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid)));
				}
			} else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
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
		$attr_loader = new ElggAttributeLoader(get_class(), 'user', $this->attributes);
		$attr_loader->secondary_loader = 'get_user_entity_as_row';

		$attrs = $attr_loader->getRequiredAttributes($guid);
		if (!$attrs) {
			return false;
		}

		$this->attributes = $attrs;
		$this->attributes['tables_loaded'] = 2;
		_elgg_cache_entity($this);

		return true;
	}

	/**
	 * Saves this user to the database.
	 *
	 * @return bool
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		_elgg_disable_caching_for_entity($this->guid);
		$ret = create_user_entity($this->get('guid'), $this->get('name'), $this->get('username'),
			$this->get('password'), $this->get('salt'), $this->get('email'), $this->get('language'),
			$this->get('code'));
		_elgg_enable_caching_for_entity($this->guid);

		return $ret;
	}

	/**
	 * User specific override of the entity delete method.
	 *
	 * @return bool
	 */
	public function delete() {
		global $USERNAME_TO_GUID_MAP_CACHE, $CODE_TO_GUID_MAP_CACHE;

		// clear cache
		if (isset($USERNAME_TO_GUID_MAP_CACHE[$this->username])) {
			unset($USERNAME_TO_GUID_MAP_CACHE[$this->username]);
		}
		if (isset($CODE_TO_GUID_MAP_CACHE[$this->code])) {
			unset($CODE_TO_GUID_MAP_CACHE[$this->code]);
		}

		clear_user_files($this);

		// Delete entity
		return parent::delete();
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
			return FALSE;
		}

		// need to manually set attributes since they've already been loaded.
		$this->attributes['admin'] = 'yes';

		return TRUE;
	}

	/**
	 * Remove the admin flag for user
	 *
	 * @return bool
	 */
	public function removeAdmin() {
		// If already saved, use the standard function.
		if ($this->guid && !remove_user_admin($this->guid)) {
			return FALSE;
		}

		// need to manually set attributes since they've already been loaded.
		$this->attributes['admin'] = 'no';

		return TRUE;
	}

	/**
	 * Get sites that this user is a member of
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int    $limit   The number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array
	 */
	function getSites($subtype = "", $limit = 10, $offset = 0) {
		return get_user_sites($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add this user to a particular site
	 *
	 * @param int $site_guid The guid of the site to add it to
	 *
	 * @return bool
	 */
	function addToSite($site_guid) {
		return add_site_user($site_guid, $this->getGUID());
	}

	/**
	 * Remove this user from a particular site
	 *
	 * @param int $site_guid The guid of the site to remove it from
	 *
	 * @return bool
	 */
	function removeFromSite($site_guid) {
		return remove_site_user($site_guid, $this->getGUID());
	}

	/**
	 * Adds a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to add
	 *
	 * @return bool
	 */
	function addFriend($friend_guid) {
		return user_add_friend($this->getGUID(), $friend_guid);
	}

	/**
	 * Removes a user as a friend
	 *
	 * @param int $friend_guid The GUID of the user to remove
	 *
	 * @return bool
	 */
	function removeFriend($friend_guid) {
		return user_remove_friend($this->getGUID(), $friend_guid);
	}

	/**
	 * Determines whether or not this user is a friend of the currently logged in user
	 *
	 * @return bool
	 */
	function isFriend() {
		return $this->isFriendOf(elgg_get_logged_in_user_guid());
	}

	/**
	 * Determines whether this user is friends with another user
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	function isFriendsWith($user_guid) {
		return user_is_friend($this->getGUID(), $user_guid);
	}

	/**
	 * Determines whether or not this user is another user's friend
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return bool
	 */
	function isFriendOf($user_guid) {
		return user_is_friend($user_guid, $this->getGUID());
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
	function getFriends($subtype = "", $limit = 10, $offset = 0) {
		return get_user_friends($this->getGUID(), $subtype, $limit, $offset);
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
	function getFriendsOf($subtype = "", $limit = 10, $offset = 0) {
		return get_user_friends_of($this->getGUID(), $subtype, $limit, $offset);
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
	 */
	function listFriends($subtype = "", $limit = 10, array $vars = array()) {
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
	 * @param string $subtype Optionally, the subtype of user to filter to (leave blank for all)
	 * @param int    $limit   The number of groups to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return array|false Array of ElggGroup, or false, depending on success
	 */
	function getGroups($subtype = "", $limit = 10, $offset = 0) {
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
	 */
	function listGroups($subtype = "", $limit = 10, $offset = 0) {
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
		return get_user_friends_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Counts the number of ElggObjects owned by this user
	 *
	 * @param string $subtype The subtypes of the objects, if any
	 *
	 * @return int The number of ElggObjects
	 */
	public function countObjects($subtype = "") {
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
	function getOwnerGUID() {
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
	function getOwner() {
		elgg_deprecated_notice("ElggUser::getOwner deprecated for ElggUser::getOwnerGUID", 1.8);
		$this->getOwnerGUID();
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'username',
			'language',
		));
	}

	/**
	 * Need to catch attempts to make a user an admin.  Remove for 1.9
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function __set($name, $value) {
		if ($name == 'admin' || $name == 'siteadmin') {
			elgg_deprecated_notice('The admin/siteadmin metadata are not longer used.  Use ElggUser->makeAdmin() and ElggUser->removeAdmin().', 1.7);

			if ($value == 'yes' || $value == '1') {
				$this->makeAdmin();
			} else {
				$this->removeAdmin();
			}
		}
		return parent::__set($name, $value);
	}

	/**
	 * Need to catch attempts to test user for admin.  Remove for 1.9
	 *
	 * @param string $name Name
	 *
	 * @return bool
	 */
	public function __get($name) {
		if ($name == 'admin' || $name == 'siteadmin') {
			elgg_deprecated_notice('The admin/siteadmin metadata are not longer used.  Use ElggUser->isAdmin().', 1.7);
			return $this->isAdmin();
		}

		return parent::__get($name);
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

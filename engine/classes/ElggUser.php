<?php
/**
 * ElggUser
 *
 * Representation of a "user" in the system.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.User
 */
class ElggUser extends ElggEntity
	implements Friendable {
	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 *
	 * @deprecated 1.8 Use ElggUser::initializeAttributes()
	 *
	 * @return void
	 */
	protected function initialise_attributes() {
		elgg_deprecated_notice('ElggUser::initialise_attributes() is deprecated by ::initializeAttributes()', 1.8);

		return $this->initializeAttributes();
	}

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
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new user entity, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If a db row then will attempt to load the rest of the data.
	 *
	 * @throws Exception if there was a problem creating the user.
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a user table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					$msg = elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid->guid));
					throw new IOException($msg);
				}

				// See if this is a username
			} else if (is_string($guid)) {
				$guid = get_user_by_username($guid);
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}

				// Is $guid is an ElggUser? Use a copy constructor
			} else if ($guid instanceof ElggUser) {
				elgg_deprecated_notice('This type of usage of the ElggUser constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}

				// Is this is an ElggEntity but not an ElggUser = ERROR!
			} else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggUser'));

				// We assume if we have got this far, $guid is an int
			} else if (is_numeric($guid)) {
				if (!$this->load($guid)) {
					throw new IOException(elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid)));
				}
			} else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
	}

	/**
	 * Override the load function.
	 * This function will ensure that all data is loaded (were possible), so
	 * if only part of the ElggUser is loaded, it'll load the rest.
	 *
	 * @param int $guid ElggUser GUID
	 *
	 * @return true|false
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type'] != 'user') {
			$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($guid, get_class()));
			throw new InvalidClassException($msg);
		}

		// Load missing data
		$row = get_user_entity_as_row($guid);
		if (($row) && (!$this->isFullyLoaded())) {
			// If $row isn't a cached copy then increment the counter
			$this->attributes['tables_loaded'] ++;
		}

		// Now put these into the attributes array as core values
		$objarray = (array) $row;
		foreach ($objarray as $key => $value) {
			$this->attributes[$key] = $value;
		}

		return true;
	}

	/**
	 * Saves this user to the database.
	 *
	 * @return true|false
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		return create_user_entity($this->get('guid'), $this->get('name'), $this->get('username'),
			$this->get('password'), $this->get('salt'), $this->get('email'), $this->get('language'),
			$this->get('code'));
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

		// Delete owned data
		clear_annotations_by_owner($this->guid);
		clear_metadata_by_owner($this->guid);
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
	 * @return bool
	 */
	function getSites($subtype = "", $limit = 10, $offset = 0) {
		return get_user_sites($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add this user to a particular site
	 *
	 * @param int $site_guid The guid of the site to add it to
	 *
	 * @return true|false
	 */
	function addToSite($site_guid) {
		return add_site_user($site_guid, $this->getGUID());
	}

	/**
	 * Remove this user from a particular site
	 *
	 * @param int $site_guid The guid of the site to remove it from
	 *
	 * @return true|false
	 */
	function removeFromSite($site_guid) {
		return remove_site_user($site_guid, $this->getGUID());
	}

	/**
	 * Adds a user to this user's friends list
	 *
	 * @param int $friend_guid The GUID of the user to add
	 *
	 * @return true|false Depending on success
	 */
	function addFriend($friend_guid) {
		return user_add_friend($this->getGUID(), $friend_guid);
	}

	/**
	 * Removes a user from this user's friends list
	 *
	 * @param int $friend_guid The GUID of the user to remove
	 *
	 * @return true|false Depending on success
	 */
	function removeFriend($friend_guid) {
		return user_remove_friend($this->getGUID(), $friend_guid);
	}

	/**
	 * Determines whether or not this user is a friend of the currently logged in user
	 *
	 * @return true|false
	 */
	function isFriend() {
		return user_is_friend(get_loggedin_userid(), $this->getGUID());
	}

	/**
	 * Determines whether this user is friends with another user
	 *
	 * @param int $user_guid The GUID of the user to check is on this user's friends list
	 *
	 * @return true|false
	 */
	function isFriendsWith($user_guid) {
		return user_is_friend($this->getGUID(), $user_guid);
	}

	/**
	 * Determines whether or not this user is on another user's friends list
	 *
	 * @param int $user_guid The GUID of the user to check against
	 *
	 * @return true|false
	 */
	function isFriendOf($user_guid) {
		return user_is_friend($user_guid, $this->getGUID());
	}

	/**
	 * Retrieves a list of this user's friends
	 *
	 * @param string $subtype Optionally, the subtype of user to filter to (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return array|false Array of ElggUsers, or false, depending on success
	 */
	function getFriends($subtype = "", $limit = 10, $offset = 0) {
		return get_user_friends($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Retrieves a list of people who have made this user a friend
	 *
	 * @param string $subtype Optionally, the subtype of user to filter to (leave blank for all)
	 * @param int    $limit   The number of users to retrieve
	 * @param int    $offset  Indexing offset, if any
	 *
	 * @return array|false Array of ElggUsers, or false, depending on success
	 */
	function getFriendsOf($subtype = "", $limit = 10, $offset = 0) {
		return get_user_friends_of($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Get an array of ElggObjects owned by this user.
	 *
	 * @param string $subtype The subtype of the objects, if any
	 * @param int    $limit   Number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array|false
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0) {
		return get_user_objects($this->getGUID(), $subtype, $limit, $offset);
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
		return get_user_collections($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * If a user's owner is blank, return its own GUID as the owner
	 *
	 * @return int User GUID
	 */
	function getOwner() {
		if ($this->owner_guid == 0) {
			return $this->getGUID();
		}

		return $this->owner_guid;
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
			elgg_deprecated_notice('The admin/siteadmin metadata are not longer used.  Use ElggUser->makeAdmin() and ElggUser->removeAdmin().', '1.7.1');

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
			elgg_deprecated_notice('The admin/siteadmin metadata are not longer used.  Use ElggUser->isAdmin().', '1.7.1');
			return $this->isAdmin();
		}

		return parent::__get($name);
	}
}

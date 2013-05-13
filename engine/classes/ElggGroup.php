<?php

/**
 * Class representing a container for other elgg entities.
 *
 * @package    Elgg.Core
 * @subpackage Groups
 * 
 * @property string $name        A short name that captures the purpose of the group
 * @property string $description A longer body of content that gives more details about the group
 */
class ElggGroup extends ElggEntity
	implements Friendable {

	const CONTENT_ACCESS_MODE_UNRESTRICTED = 'unrestricted';
	const CONTENT_ACCESS_MODE_MEMBERS_ONLY = 'members_only';

	/**
	 * Sets the type to group.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "group";
		$this->attributes['name'] = NULL;
		$this->attributes['description'] = NULL;
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new group entity, optionally from a given guid value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If an entity table db row, then will load the rest of the data.
	 *
	 * @throws IOException|InvalidParameterException if there was a problem creating the group.
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($guid)) {
			// Is $guid is a entity table DB row
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid)) {
					$msg = "Failed to load new " . get_class() . " from GUID:" . $guid->guid;
					throw new IOException($msg);
				}
			} else if ($guid instanceof ElggGroup) {
				// $guid is an ElggGroup so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the ElggGroup constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if ($guid instanceof ElggEntity) {
				// @todo why separate from else
				throw new InvalidParameterException("Passing a non-ElggGroup to an ElggGroup constructor!");
			} else if (is_numeric($guid)) {
				// $guid is a GUID so load entity
				if (!$this->load($guid)) {
					throw new IOException("Failed to load new " . get_class() . " from GUID:" . $guid);
				}
			} else {
				throw new InvalidParameterException("Unrecognized value passed to constuctor.");
			}
		}
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
	 * Add an ElggObject to this group.
	 *
	 * @param ElggObject $object The object.
	 *
	 * @return bool
	 */
	public function addObjectToGroup(ElggObject $object) {
		return add_object_to_group($this->getGUID(), $object->getGUID());
	}

	/**
	 * Remove an object from the containing group.
	 *
	 * @param int $guid The guid of the object.
	 *
	 * @return bool
	 */
	public function removeObjectFromGroup($guid) {
		return remove_object_from_group($this->getGUID(), $guid);
	}

	/**
	 * Returns an attribute or metadata.
	 *
	 * @see ElggEntity::get()
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	public function get($name) {
		if ($name == 'username') {
			return 'group:' . $this->getGUID();
		}
		return parent::get($name);
	}

	/**
	 * Start friendable compatibility block:
	 *
	 * 	public function addFriend($friend_guid);
		public function removeFriend($friend_guid);
		public function isFriend();
		public function isFriendsWith($user_guid);
		public function isFriendOf($user_guid);
		public function getFriends($subtype = "", $limit = 10, $offset = 0);
		public function getFriendsOf($subtype = "", $limit = 10, $offset = 0);
		public function getObjects($subtype="", $limit = 10, $offset = 0);
		public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0);
		public function countObjects($subtype = "");
	 */

	/**
	 * For compatibility with Friendable.
	 *
	 * Join a group when you friend ElggGroup.
	 *
	 * @param int $friend_guid The GUID of the user joining the group.
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::join()
	 */
	public function addFriend($friend_guid) {
		elgg_deprecated_notice("ElggGroup::addFriend() is deprecated. Use ElggGroup::join()", 1.9);
		$user = get_user($friend_guid);
		return $user ? $this->join($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * Leave group when you unfriend ElggGroup.
	 *
	 * @param int $friend_guid The GUID of the user leaving.
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::leave()
	 */
	public function removeFriend($friend_guid) {
		elgg_deprecated_notice("ElggGroup::removeFriend() is deprecated. Use ElggGroup::leave()", 1.9);
		$user = get_user($friend_guid);
		return $user ? $this->leave($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * Friending a group adds you as a member
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::isMember()
	 */
	public function isFriend() {
		elgg_deprecated_notice("ElggGroup::isFriend() is deprecated. Use ElggGroup::isMember()", 1.9);
		return $this->isMember();
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param int $user_guid The GUID of a user to check.
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::isMember()
	 */
	public function isFriendsWith($user_guid) {
		elgg_deprecated_notice("ElggGroup::isFriendsWith() is deprecated. Use ElggGroup::isMember()", 1.9);
		$user = get_user($user_guid);
		return $user ? $this->isMember($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param int $user_guid The GUID of a user to check.
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::isMember()
	 */
	public function isFriendOf($user_guid) {
		elgg_deprecated_notice("ElggGroup::isFriendOf() is deprecated. Use ElggGroup::isMember()", 1.9);
		$user = get_user($user_guid);
		return $user ? $this->isMember($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param string $subtype The GUID of a user to check.
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::getMembers()
	 */
	public function getFriends($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("ElggGroup::getFriends() is deprecated. Use ElggGroup::getMembers()", 1.9);
		return get_group_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param string $subtype The GUID of a user to check.
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggGroup::getMembers()
	 */
	public function getFriendsOf($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("ElggGroup::getFriendsOf() is deprecated. Use ElggGroup::getMembers()", 1.9);
		return get_group_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * Get objects contained in this group.
	 *
	 * @param string $subtype Entity subtype
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return array|false
	 * @deprecated 1.9 Use elgg_get_entities()
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("ElggGroup::getObjects() is deprecated. Use elgg_get_entities()", 1.9);
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", $limit, $offset, false);
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param string $subtype Entity subtype
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return array|false
	 * @deprecated 1.9 Use elgg_get_entities()
	 */
	public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("ElggGroup::getFriendsObjects() is deprecated. Use elgg_get_entities()", 1.9);
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", $limit, $offset, false);
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param string $subtype Subtype of entities
	 *
	 * @return array|false
	 * @deprecated 1.9 Use elgg_get_entities()
	 */
	public function countObjects($subtype = "") {
		elgg_deprecated_notice("ElggGroup::countObjects() is deprecated. Use elgg_get_entities()", 1.9);
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", 10, 0, true);
	}

	/**
	 * End friendable compatibility block
	 */

	/**
	 * Get a list of group members.
	 *
	 * @param int  $limit  Limit
	 * @param int  $offset Offset
	 * @param bool $count  Count
	 *
	 * @return mixed
	 */
	public function getMembers($limit = 10, $offset = 0, $count = false) {
		return get_group_members($this->getGUID(), $limit, $offset, 0, $count);
	}

	/**
	 * Returns whether the current group has open membership or not.
	 *
	 * @return bool
	 */
	public function isPublicMembership() {
		return ($this->membership == ACCESS_PUBLIC);
	}

	/**
	 * Return the content access mode used by group_gatekeeper()
	 *
	 * @return string One of CONTENT_ACCESS_MODE_* constants
	 * @access private
	 * @since 1.9.0
	 */
	public function getContentAccessMode() {
		$mode = $this->content_access_mode;

		if (!is_string($mode)) {
			// fallback to 1.8 default behavior
			if ($this->isPublicMembership()) {
				$mode = self::CONTENT_ACCESS_MODE_UNRESTRICTED;
			} else {
				$mode = self::CONTENT_ACCESS_MODE_MEMBERS_ONLY;
			}
			$this->content_access_mode = $mode;
		}

		// only support two modes for now
		if ($mode === self::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			return $mode;
		}
		return self::CONTENT_ACCESS_MODE_UNRESTRICTED;
	}

	/**
	 * Set the content access mode used by group_gatekeeper()
	 *
	 * @param string $mode One of CONTENT_ACCESS_MODE_* constants
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	public function setContentAccessMode($mode) {
		// only support two modes for now
		if ($mode !== self::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			$mode = self::CONTENT_ACCESS_MODE_UNRESTRICTED;
		}

		$this->content_access_mode = $mode;
	}

	/**
	 * Return whether a given user is a member of this group or not.
	 *
	 * @param ElggUser $user The user
	 *
	 * @return bool
	 */
	public function isMember($user = null) {
		if (!($user instanceof ElggUser)) {
			$user = elgg_get_logged_in_user_entity();
		}
		if (!($user instanceof ElggUser)) {
			return false;
		}
		return is_group_member($this->getGUID(), $user->getGUID());
	}

	/**
	 * Join a user to this group.
	 *
	 * @param ElggUser $user User joining the group.
	 *
	 * @return bool Whether joining was successful.
	 */
	public function join(ElggUser $user) {
		$result = add_entity_relationship($user->guid, 'member', $this->guid);
	
		if ($result) {
			$params = array('group' => $this, 'user' => $user);
			elgg_trigger_event('join', 'group', $params);
		}
	
		return $result;
	}

	/**
	 * Remove a user from the group.
	 *
	 * @param ElggUser $user User to remove from the group.
	 *
	 * @return bool Whether the user was removed from the group.
	 */
	public function leave(ElggUser $user) {
		// event needs to be triggered while user is still member of group to have access to group acl
		$params = array('group' => $this, 'user' => $user);
		elgg_trigger_event('leave', 'group', $params);

		return remove_entity_relationship($user->guid, 'member', $this->guid);
	}

	/**
	 * Load the ElggGroup data from the database
	 *
	 * @param mixed $guid GUID of an ElggGroup entity or database row from entity table
	 *
	 * @return bool
	 */
	protected function load($guid) {
		$attr_loader = new Elgg_AttributeLoader(get_class(), 'group', $this->attributes);
		$attr_loader->requires_access_control = !($this instanceof ElggPlugin);
		$attr_loader->secondary_loader = 'get_group_entity_as_row';

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
	 * {@inheritdoc}
	 */
	protected function update() {
		global $CONFIG;
		
		if (!parent::update()) {
			return false;
		}
		
		$guid = (int)$this->guid;
		$name = sanitize_string($this->name);
		$description = sanitize_string($this->description);
		
		$query = "UPDATE {$CONFIG->dbprefix}groups_entity set"
			. " name='$name', description='$description' where guid=$guid";

		return $this->getDatabase()->updateData($query) !== false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function create() {
		global $CONFIG;
		
		$guid = parent::create();
		$name = sanitize_string($this->name);
		$description = sanitize_string($this->description);

		$query = "INSERT into {$CONFIG->dbprefix}groups_entity"
			. " (guid, name, description) values ($guid, '$name', '$description')";

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
	protected function prepareObject($object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->description = $this->description;
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
			'description',
		));
	}

	/**
	 * Can a user comment on this group?
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

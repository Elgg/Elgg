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
class ElggGroup extends \ElggEntity
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
			'description' => null,
		];
	}

	/**
	 * Construct a new group entity
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * @param \stdClass $row Database row result. Default is null to create a new group.
	 *
	 * @throws IOException|InvalidParameterException if there was a problem creating the group.
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($row)) {
			// Is $guid is a entity table DB row
			if ($row instanceof \stdClass) {
				// Load the rest
				if (!$this->load($row)) {
					$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
					throw new \IOException($msg);
				}
			} else if ($row instanceof \ElggGroup) {
				// $row is an \ElggGroup so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the \ElggGroup constructor was deprecated. Please use the clone method.', 1.7);
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
	 * Add an \ElggObject to this group.
	 *
	 * @param \ElggObject $object The object.
	 *
	 * @return bool
	 */
	public function addObjectToGroup(\ElggObject $object) {
		$object->container_guid = $this->guid;
		return $object->save();
	}

	/**
	 * Remove an object from this containing group and sets the container to be
	 * object's owner
	 *
	 * @param \ElggObject $object The object.
	 *
	 * @return bool
	 */
	public function removeObjectFromGroup($object) {
		if (is_numeric($object)) {
			elgg_deprecated_notice('\ElggGroup::removeObjectFromGroup() takes an \ElggObject not a guid.', 1.9);
			$object = get_entity($object);
			if (!elgg_instanceof($object, 'object')) {
				return false;
			}
		}

		$object->container_guid = $object->owner_guid;
		return $object->save();
	}

	/**
	 * Wrapper around \ElggEntity::__get()
	 *
	 * @see \ElggEntity::__get()
	 *
	 * @param string $name Name
	 * @return mixed
	 * @todo deprecate appending group to username. Was a hack used for creating
	 * URLs for group content. We stopped using the hack in 1.8.
	 */
	public function __get($name) {
		if ($name == 'username') {
			return 'group:' . $this->getGUID();
		}
		return parent::__get($name);
	}

	/**
	 * Wrapper around \ElggEntity::get()
	 *
	 * @param string $name Name
	 * @return mixed
	 * @deprecated 1.9
	 */
	public function get($name) {
		elgg_deprecated_notice("Use -> instead of get()", 1.9);
		return $this->__get($name);
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
	 * Join a group when you friend \ElggGroup.
	 *
	 * @param int $friend_guid The GUID of the user joining the group.
	 *
	 * @return bool
	 * @deprecated 1.9 Use \ElggGroup::join()
	 */
	public function addFriend($friend_guid) {
		elgg_deprecated_notice("\ElggGroup::addFriend() is deprecated. Use \ElggGroup::join()", 1.9);
		$user = get_user($friend_guid);
		return $user ? $this->join($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * Leave group when you unfriend \ElggGroup.
	 *
	 * @param int $friend_guid The GUID of the user leaving.
	 *
	 * @return bool
	 * @deprecated 1.9 Use \ElggGroup::leave()
	 */
	public function removeFriend($friend_guid) {
		elgg_deprecated_notice("\ElggGroup::removeFriend() is deprecated. Use \ElggGroup::leave()", 1.9);
		$user = get_user($friend_guid);
		return $user ? $this->leave($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * Friending a group adds you as a member
	 *
	 * @return bool
	 * @deprecated 1.9 Use \ElggGroup::isMember()
	 */
	public function isFriend() {
		elgg_deprecated_notice("\ElggGroup::isFriend() is deprecated. Use \ElggGroup::isMember()", 1.9);
		return $this->isMember();
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param int $user_guid The GUID of a user to check.
	 *
	 * @return bool
	 * @deprecated 1.9 Use \ElggGroup::isMember()
	 */
	public function isFriendsWith($user_guid) {
		elgg_deprecated_notice("\ElggGroup::isFriendsWith() is deprecated. Use \ElggGroup::isMember()", 1.9);
		$user = get_user($user_guid);
		return $user ? $this->isMember($user) : false;
	}

	/**
	 * For compatibility with Friendable
	 *
	 * @param int $user_guid The GUID of a user to check.
	 *
	 * @return bool
	 * @deprecated 1.9 Use \ElggGroup::isMember()
	 */
	public function isFriendOf($user_guid) {
		elgg_deprecated_notice("\ElggGroup::isFriendOf() is deprecated. Use \ElggGroup::isMember()", 1.9);
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
	 * @deprecated 1.9 Use \ElggGroup::getMembers()
	 */
	public function getFriends($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("\ElggGroup::getFriends() is deprecated. Use \ElggGroup::getMembers()", 1.9);
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
	 * @deprecated 1.9 Use \ElggGroup::getMembers()
	 */
	public function getFriendsOf($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("\ElggGroup::getFriendsOf() is deprecated. Use \ElggGroup::getMembers()", 1.9);
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
		elgg_deprecated_notice("\ElggGroup::getObjects() is deprecated. Use elgg_get_entities()", 1.9);
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
		elgg_deprecated_notice("\ElggGroup::getFriendsObjects() is deprecated. Use elgg_get_entities()", 1.9);
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
		elgg_deprecated_notice("\ElggGroup::countObjects() is deprecated. Use elgg_get_entities()", 1.9);
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", 10, 0, true);
	}

	/**
	 * End friendable compatibility block
	 */

	/**
	 * Get an array of group members.
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationships
	 *                       for a complete list. Common ones are 'limit', 'offset',
	 *                       and 'count'. Options set automatically are 'relationship',
	 *                       'relationship_guid', 'inverse_relationship', and 'type'. This argument
	 *                       used to set the limit (deprecated usage)
	 * @param int   $offset  Offset (deprecated)
	 * @param bool  $count   Count (deprecated)
	 *
	 * @return array
	 */
	public function getMembers($options = array(), $offset = 0, $count = false) {
		if (!is_array($options)) {
			elgg_deprecated_notice('\ElggGroup::getMembers() takes an options array.', 1.9);
			$options = array(
				'relationship' => 'member',
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => true,
				'type' => 'user',
				'limit' => $options,
				'offset' => $offset,
				'count' => $count,
			);
		} else {
			$options['relationship'] = 'member';
			$options['relationship_guid'] = $this->getGUID();
			$options['inverse_relationship'] = true;
			$options['type'] = 'user';
		}

		return elgg_get_entities_from_relationship($options);
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
	 * Is the given user a member of this group?
	 *
	 * @param \ElggUser $user The user. Default is logged in user.
	 *
	 * @return bool
	 */
	public function isMember(\ElggUser $user = null) {
		if ($user == null) {
			$user = _elgg_services()->session->getLoggedInUser();
		}
		if (!$user) {
			return false;
		}

		$result = (bool)check_entity_relationship($user->guid, 'member', $this->guid);

		$params = array(
			'user' => $user,
			'group' => $this,
		);
		return _elgg_services()->hooks->trigger('is_member', 'group', $params, $result);
	}

	/**
	 * Join a user to this group.
	 *
	 * @param \ElggUser $user User joining the group.
	 *
	 * @return bool Whether joining was successful.
	 */
	public function join(\ElggUser $user) {
		$result = add_entity_relationship($user->guid, 'member', $this->guid);
	
		if ($result) {
			$params = array('group' => $this, 'user' => $user);
			_elgg_services()->events->trigger('join', 'group', $params);
		}
	
		return $result;
	}

	/**
	 * Remove a user from the group.
	 *
	 * @param \ElggUser $user User to remove from the group.
	 *
	 * @return bool Whether the user was removed from the group.
	 */
	public function leave(\ElggUser $user) {
		// event needs to be triggered while user is still member of group to have access to group acl
		$params = array('group' => $this, 'user' => $user);
		_elgg_services()->events->trigger('leave', 'group', $params);

		return remove_entity_relationship($user->guid, 'member', $this->guid);
	}

	/**
	 * Load the \ElggGroup data from the database
	 *
	 * @param mixed $guid GUID of an \ElggGroup entity or database row from entity table
	 *
	 * @return bool
	 */
	protected function load($guid) {
		$attr_loader = new \Elgg\AttributeLoader(get_class(), 'group', $this->attributes);
		$attr_loader->requires_access_control = !($this instanceof \ElggPlugin);
		$attr_loader->secondary_loader = 'get_group_entity_as_row';

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
		if (!$guid) {
			// @todo this probably means permission to create entity was denied
			// Is returning false the correct thing to do
			return false;
		}
		
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
}

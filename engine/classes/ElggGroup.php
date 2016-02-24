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
class ElggGroup extends \ElggEntity {

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
	public function __construct(\stdClass $row = null) {
		$this->initializeAttributes();

		if ($row) {
			// Load the rest
			if (!$this->load($row)) {
				$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
				throw new \IOException($msg);
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
	public function removeObjectFromGroup(ElggObject $object) {
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
	 * Get an array of group members.
	 *
	 * @param array $options Options array. See elgg_get_entities_from_relationships
	 *                       for a complete list. Common ones are 'limit', 'offset',
	 *                       and 'count'. Options set automatically are 'relationship',
	 *                       'relationship_guid', 'inverse_relationship', and 'type'.
	 *
	 * @return array
	 */
	public function getMembers(array $options = []) {
		$options['relationship'] = 'member';
		$options['relationship_guid'] = $this->getGUID();
		$options['inverse_relationship'] = true;
		$options['type'] = 'user';

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
	 * @param string $mode One of CONTENT_ACCESS_MODE_* constants. If empty string, mode will not be changed.
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	public function setContentAccessMode($mode) {
		if (!$mode && $this->content_access_mode) {
			return;
		}

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

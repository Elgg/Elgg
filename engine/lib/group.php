<?php
/**
 * Elgg Groups.
 * Groups contain other entities, or rather act as a placeholder for other entities to mark any given container
 * as their container.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * @class ElggGroup Class representing a container for other elgg entities.
 */
class ElggGroup extends ElggEntity
	implements Friendable {

	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['type'] = "group";
		$this->attributes['name'] = "";
		$this->attributes['description'] = "";
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new user entity, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If a db row then will attempt to load the rest of the data.
	 * @throws Exception if there was a problem creating the user.
	 */
	function __construct($guid = null) {
		$this->initialise_attributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a user table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid));
				}
			}
			// Is $guid is an ElggGroup? Use a copy constructor
			else if ($guid instanceof ElggGroup) {
				elgg_deprecated_notice('This type of usage of the ElggGroup constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}
			// Is this is an ElggEntity but not an ElggGroup = ERROR!
			else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggGroup'));
			}
			// We assume if we have got this far, $guid is an int
			else if (is_numeric($guid)) {
				if (!$this->load($guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid));
				}
			}

			else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
	}

	/**
	 * Add an ElggObject to this group.
	 *
	 * @param ElggObject $object The object.
	 * @return bool
	 */
	public function addObjectToGroup(ElggObject $object) {
		return add_object_to_group($this->getGUID(), $object->getGUID());
	}

	/**
	 * Remove an object from the containing group.
	 *
	 * @param int $guid The guid of the object.
	 * @return bool
	 */
	public function removeObjectFromGroup($guid) {
		return remove_object_from_group($this->getGUID(), $guid);
	}

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
	 * For compatibility with Friendable
	 */
	public function addFriend($friend_guid) {
		return $this->join(get_entity($friend_guid));
	}

	/**
	 * For compatibility with Friendable
	 */
	public function removeFriend($friend_guid) {
		return $this->leave(get_entity($friend_guid));
	}

	/**
	 * For compatibility with Friendable
	 */
	public function isFriend() {
		return $this->isMember();
	}

	/**
	 * For compatibility with Friendable
	 */
	public function isFriendsWith($user_guid) {
		return $this->isMember($user_guid);
	}

	/**
	 * For compatibility with Friendable
	 */
	public function isFriendOf($user_guid) {
		return $this->isMember($user_guid);
	}

	/**
	 * For compatibility with Friendable
	 */
	public function getFriends($subtype = "", $limit = 10, $offset = 0) {
		return get_group_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * For compatibility with Friendable
	 */
	public function getFriendsOf($subtype = "", $limit = 10, $offset = 0) {
		return get_group_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * Get objects contained in this group.
	 *
	 * @param string $subtype
	 * @param int $limit
	 * @param int $offset
	 * @return mixed
	 */
	public function getObjects($subtype="", $limit = 10, $offset = 0) {
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", $limit, $offset, false);
	}

	/**
	 * For compatibility with Friendable
	 */
	public function getFriendsObjects($subtype = "", $limit = 10, $offset = 0) {
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", $limit, $offset, false);
	}

	/**
	 * For compatibility with Friendable
	 */
	public function countObjects($subtype = "") {
		return get_objects_in_group($this->getGUID(), $subtype, 0, 0, "", 10, 0, true);
	}

/**
 * End friendable compatibility block
 */

	/**
	 * Get a list of group members.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return mixed
	 */
	public function getMembers($limit = 10, $offset = 0, $count = false) {
		return get_group_members($this->getGUID(), $limit, $offset, 0 , $count);
	}

	/**
	 * Returns whether the current group is public membership or not.
	 * @return bool
	 */
	public function isPublicMembership() {
		if ($this->membership == ACCESS_PUBLIC) {
			return true;
		}

		return false;
	}

	/**
	 * Return whether a given user is a member of this group or not.
	 *
	 * @param ElggUser $user The user
	 * @return bool
	 */
	public function isMember($user = 0) {
		if (!($user instanceof ElggUser)) {
			$user = get_loggedin_user();
		}
		if (!($user instanceof ElggUser)) {
			return false;
		}
		return is_group_member($this->getGUID(), $user->getGUID());
	}

	/**
	 * Join an elgg user to this group.
	 *
	 * @param ElggUser $user
	 * @return bool
	 */
	public function join(ElggUser $user) {
		return join_group($this->getGUID(), $user->getGUID());
	}

	/**
	 * Remove a user from the group.
	 *
	 * @param ElggUser $user
	 */
	public function leave(ElggUser $user) {
		return leave_group($this->getGUID(), $user->getGUID());
	}

	/**
	 * Override the load function.
	 * This function will ensure that all data is loaded (were possible), so
	 * if only part of the ElggGroup is loaded, it'll load the rest.
	 *
	 * @param int $guid
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type']!='group') {
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		}

		// Load missing data
		$row = get_group_entity_as_row($guid);
		if (($row) && (!$this->isFullyLoaded())) {
			// If $row isn't a cached copy then increment the counter
			$this->attributes['tables_loaded'] ++;
		}

		// Now put these into the attributes array as core values
		$objarray = (array) $row;
		foreach($objarray as $key => $value) {
			$this->attributes[$key] = $value;
		}

		return true;
	}

	/**
	 * Override the save function.
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		return create_group_entity($this->get('guid'), $this->get('name'), $this->get('description'));
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'description',
		));
	}
}

/**
 * Get the group entity.
 *
 * @param int $guid
 */
function get_group_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	return get_data_row("SELECT * from {$CONFIG->dbprefix}groups_entity where guid=$guid");
}

/**
 * Create or update the extras table for a given group.
 * Call create_entity first.
 *
 * @param int $guid
 * @param string $name
 * @param string $description
 */
function create_group_entity($guid, $name, $description) {
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		if ($exists = get_data_row("SELECT guid from {$CONFIG->dbprefix}groups_entity WHERE guid = {$guid}")) {
			$result = update_data("UPDATE {$CONFIG->dbprefix}groups_entity set name='$name', description='$description' where guid=$guid");
			if ($result!=false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (trigger_elgg_event('update',$entity->type,$entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		} else {
			// Update failed, attempt an insert.
			$result = insert_data("INSERT into {$CONFIG->dbprefix}groups_entity (guid, name, description) values ($guid, '$name','$description')");
			if ($result!==false) {
				$entity = get_entity($guid);
				if (trigger_elgg_event('create',$entity->type,$entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		}
	}

	return false;
}


/**
 * THIS FUNCTION IS DEPRECATED.
 *
 * Delete a group's extra data.
 *
 * @param int $guid The guid of the group
 * @return bool
 */
function delete_group_entity($guid) {
	system_message(sprintf(elgg_echo('deprecatedfunction'), 'delete_user_entity'));

	// Always return that we have deleted one row in order to not break existing code.
	return 1;
}

/**
 * Add an object to the given group.
 *
 * @param int $group_guid The group to add the object to.
 * @param int $object_guid The guid of the elgg object (must be ElggObject or a child thereof)
 * @return bool
 */
function add_object_to_group($group_guid, $object_guid) {
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof ElggGroup)) {
		throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $group_guid, 'ElggGroup'));
	}

	if (!($object instanceof ElggObject)) {
		throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $object_guid, 'ElggObject'));
	}

	$object->container_guid = $group_guid;
	return $object->save();
}

/**
 * Remove an object from the given group.
 *
 * @param int $group_guid The group to remove the object from
 * @param int $object_guid The object to remove
 */
function remove_object_from_group($group_guid, $object_guid) {
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof ElggGroup)) {
		throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $group_guid, 'ElggGroup'));
	}

	if (!($object instanceof ElggObject)) {
		throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $object_guid, 'ElggObject'));
	}

	$object->container_guid = $object->owner_guid;
	return $object->save();
}

/**
 * Return an array of objects in a given container.
 * @see get_entities()
 *
 * @param int $group_guid The container (defaults to current page owner)
 * @param string $subtype The subtype
 * @param int $owner_guid Owner
 * @param int $site_guid The site
 * @param string $order_by Order
 * @param unknown_type $limit Limit on number of elements to return, by default 10.
 * @param unknown_type $offset Where to start, by default 0.
 * @param unknown_type $count Whether to return the entities or a count of them.
 */
function get_objects_in_group($group_guid, $subtype = "", $owner_guid = 0, $site_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = FALSE) {
	global $CONFIG;

	if ($subtype === FALSE || $subtype === null || $subtype === 0) {
		return FALSE;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = page_owner();
	}

	$where = array();

	$where[] = "e.type='object'";

	if (!empty($subtype)) {
		if (!$subtype = get_subtype_id('object', $subtype)) {
			return FALSE;
		}
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_guid = (int) $owner_guid;
			$where[] = "e.container_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			// Cast every element to the owner_guid array to int
			$owner_guid = array_map("sanitise_int", $owner_guid);
			$owner_guid = implode(",",$owner_guid);
			$where[] = "e.container_guid in ({$owner_guid})";
		}
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (!$count) {
		$query = "SELECT * from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";

		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Get all the entities from metadata from a group.
 *
 * @param int $group_guid The ID of the group.
 * @param mixed $meta_name
 * @param mixed $meta_value
 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int $limit
 * @param int $offset
 * @param string $order_by Optional ordering.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
 */
function get_entities_from_metadata_groups($group_guid, $meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if (is_array($owner_guid)) {
		foreach($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = page_owner();
	}

	//$access = get_access_list();

	$where = array();

	if ($entity_type!="") {
		$where[] = "e.type='$entity_type'";
	}
	if ($entity_subtype) {
		$where[] = "e.subtype=$entity_subtype";
	}
	if ($meta_name!="") {
		$where[] = "m.name_id='$meta_n'";
	}
	if ($meta_value!="") {
		$where[] = "m.value_id='$meta_v'";
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}
	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (".implode(",",$owner_guid).")";
	} else if ($owner_guid > 0)
		$where[] = "e.container_guid = {$owner_guid}";

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid join {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid where";
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix("e");

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}
	return false;
}

/**
 * As get_entities_from_metadata_groups() but with multiple entities.
 *
 * @param int $group_guid The ID of the group.
 * @param array $meta_array Array of 'name' => 'value' pairs
 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int $limit
 * @param int $offset
 * @param string $order_by Optional ordering.
 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
 * @param true|false $count If set to true, returns the total number of entities rather than a list. (Default: false)
 * @return int|array List of ElggEntities, or the total number if count is set to false
 */
function get_entities_from_metadata_groups_multi($group_guid, $meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	global $CONFIG;

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$where = array();

	$mindex = 1;
	$join = "";
	foreach($meta_array as $meta_name => $meta_value) {
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
		$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid join {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid ";
		if ($meta_name!="") {
			$where[] = "m{$mindex}.name_id='$meta_n'";
		}

		if ($meta_value!="") {
			$where[] = "m{$mindex}.value_id='$meta_v'";
		}

		$mindex++;
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$owner_guid = (int) $owner_guid;

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	if ($entity_type!="") {
		$where[] = "e.type = '{$entity_type}'";
	}

	if ($entity_subtype) {
		$where[] = "e.subtype = {$entity_subtype}";
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($owner_guid > 0) {
		$where[] = "e.owner_guid = {$owner_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}

	$query .= " from {$CONFIG->dbprefix}entities e {$join} where";
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	$query .= get_access_sql_suffix("e"); // Add access controls

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Return a list of this group's members.
 *
 * @param int $group_guid The ID of the container/group.
 * @param int $limit The limit
 * @param int $offset The offset
 * @param int $site_guid The site
 * @param bool $count Return the users (false) or the count of them (true)
 * @return mixed
 */
function get_group_members($group_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false) {

	// in 1.7 0 means "not set."  rewrite to make sense.
	if (!$site_guid) {
		$site_guid = ELGG_ENTITIES_ANY_VALUE;
	}

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group_guid,
		'inverse_relationship' => TRUE,
		'types' => 'user',
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count,
		'site_guid' => $site_guid
	));
}

/**
 * Return whether a given user is a member of the group or not.
 *
 * @param int $group_guid The group ID
 * @param int $user_guid The user guid
 * @return bool
 */
function is_group_member($group_guid, $user_guid) {
	$object = check_entity_relationship($user_guid, 'member', $group_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}

/**
 * Join a user to a group.
 *
 * @param int $group_guid The group.
 * @param int $user_guid The user.
 */
function join_group($group_guid, $user_guid) {
	$result = add_entity_relationship($user_guid, 'member', $group_guid);
	trigger_elgg_event('join', 'group', array('group' => get_entity($group_guid), 'user' => get_entity($user_guid)));
	return $result;
}

/**
 * Remove a user from a group.
 *
 * @param int $group_guid The group.
 * @param int $user_guid The user.
 */
function leave_group($group_guid, $user_guid) {
	// event needs to be triggered while user is still member of group to have access to group acl
	trigger_elgg_event('leave', 'group', array('group' => get_entity($group_guid), 'user' => get_entity($user_guid)));
	$result = remove_entity_relationship($user_guid, 'member', $group_guid);
	return $result;
}

/**
 * Return all groups a user is a member of.
 *
 * @param unknown_type $user_guid
 */
function get_users_membership($user_guid) {
	return elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => $user_guid, 'inverse_relationship' => FALSE));
}

/**
 * Checks access to a group.
 *
 * @param boolean $forward If set to true (default), will forward the page; if set to false, will return true or false.
 * @return true|false If $forward is set to false.
 */
function group_gatekeeper($forward = true) {
	$allowed = true;
	$url = '';

	if ($group = page_owner_entity()) {
		if ($group instanceof ElggGroup) {
			$url = $group->getURL();
			if (
				((!isloggedin()) && (!$group->isPublicMembership())) ||
				((!$group->isMember(get_loggedin_user()) && (!$group->isPublicMembership())))
			) {
				$allowed = false;
			}

			// Admin override
			if (isadminloggedin()) {
				$allowed = true;
			}
		}
	}

	if ($forward && $allowed == false) {
		register_error(elgg_echo('membershiprequired'));
		if (!forward($url)) {
			throw new SecurityException(elgg_echo('SecurityException:UnexpectedOutputInGatekeeper'));
		}
	}

	return $allowed;
}

/**
 * Adds a group tool option
 *
 * @see remove_group_tool_option().
 *
 * @param string $name Name of the group tool option
 * @param string $label Used for the group edit form
 * @param boolean $default_on True if this option should be active by default
 *
 */
function add_group_tool_option($name, $label, $default_on=true) {
	global $CONFIG;

	if (!isset($CONFIG->group_tool_options)) {
		$CONFIG->group_tool_options = array();
	}

	$group_tool_option = new stdClass;

	$group_tool_option->name = $name;
	$group_tool_option->label = $label;
	$group_tool_option->default_on = $default_on;

	$CONFIG->group_tool_options[] = $group_tool_option;
}

/**
 * Removes a group tool option based on name
 *
 * @see add_group_tool_option()
 *
 * @param string $name Name of the group tool option
 *
 */
function remove_group_tool_option($name) {
	global $CONFIG;

	if (!isset($CONFIG->group_tool_options)) {
		return;
	}

	foreach ($CONFIG->group_tool_options as $i => $option) {
		if ($option->name == $name) {
			unset($CONFIG->group_tool_options[$i]);
		}
	}
}

/**
 * Searches for a group based on a complete or partial name or description
 *
 * @param string $criteria The partial or full name or description
 * @param int $limit Limit of the search.
 * @param int $offset Offset.
 * @param string $order_by The order.
 * @param boolean $count Whether to return the count of results or just the results.
 * @deprecated 1.7
 */
function search_for_group($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_group() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}groups_entity g on e.guid=g.guid where ";
	// $query .= " match(u.name,u.username) against ('$criteria') ";
	$query .= "(g.name like \"%{$criteria}%\" or g.description like \"%{$criteria}%\")";
	$query .= " and $access";

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Returns a formatted list of groups suitable for injecting into search.
 * @deprecated 1.7
 */
function search_list_groups_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_groups_by_name() was deprecated by new search plugin', 1.7);
	// Change this to set the number of groups that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'group')) {
		if ($groups = search_for_group($tag,$threshold)) {
			$countgroups = search_for_group($tag,0,0,"",true);

			$return = elgg_view('group/search/startblurb',array('count' => $countgroups, 'tag' => $tag));
			foreach($groups as $group) {
				$return .= elgg_view_entity($group);
			}
			$return .= elgg_view('group/search/finishblurb',array('count' => $countgroups, 'threshold' => $threshold, 'tag' => $tag));
			return $return;
		}
	}
}

/**
 * Displays a list of group objects that have been searched for.
 *
 * @see elgg_view_entity_list
 *
 * @param string $tag Search criteria
 * @param int $limit The number of entities to display on a page
 * @return string The list in a form suitable to display
 * @deprecated 1.7
 */
function list_group_search($tag, $limit = 10) {
	elgg_deprecated_notice('list_group_search() was deprecated by new search plugin.', 1.7);
	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) search_for_group($tag, 10, 0, '', true);
	$entities = search_for_group($tag, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);

}

/**
 * Performs initialisation functions for groups
 *
 */
function group_init() {

}

register_elgg_event_handler('init','system','group_init');

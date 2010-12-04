<?php
/**
 * Procedural code for creating, loading, and modifying ElggEntity objects.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Entities
 * @link http://docs.elgg.org/DataModel/Entities
 */

/**
 * Cache entities in memory once loaded.
 *
 * @global array $ENTITY_CACHE
 * @access private
 */
$ENTITY_CACHE = array();

/**
 * Cache subtypes and related class names once loaded.
 *
 * @global array $SUBTYPE_CACHE
 * @access private
 */
$SUBTYPE_CACHE = NULL;

/**
 * Invalidate this class's entry in the cache.
 *
 * @param int $guid The entity guid
 *
 * @return void
 * @access private
 */
function invalidate_cache_for_entity($guid) {
	global $ENTITY_CACHE;

	$guid = (int)$guid;

	unset($ENTITY_CACHE[$guid]);
}

/**
 * Cache an entity.
 *
 * Stores an entity in $ENTITY_CACHE;
 *
 * @param ElggEntity $entity Entity to cache
 *
 * @return void
 * @see retrieve_cached_entity()
 * @see invalidate_cache_for_entity()
 * @access private
 */
function cache_entity(ElggEntity $entity) {
	global $ENTITY_CACHE;

	$ENTITY_CACHE[$entity->guid] = $entity;
}

/**
 * Retrieve a entity from the cache.
 *
 * @param int $guid The guid
 *
 * @return void
 * @see cache_entity()
 * @see invalidate_cache_for_entity()
 * @access private
 */
function retrieve_cached_entity($guid) {
	global $ENTITY_CACHE;

	$guid = (int)$guid;

	if (isset($ENTITY_CACHE[$guid])) {
		if ($ENTITY_CACHE[$guid]->isFullyLoaded()) {
			return $ENTITY_CACHE[$guid];
		}
	}

	return false;
}

/**
 * As retrieve_cached_entity, but returns the result as a stdClass
 * (compatible with load functions that expect a database row.)
 *
 * @param int $guid The guid
 *
 * @return mixed
 * @todo unused
 * @access private
 */
function retrieve_cached_entity_row($guid) {
	$obj = retrieve_cached_entity($guid);
	if ($obj) {
		$tmp = new stdClass;

		foreach ($obj as $k => $v) {
			$tmp->$k = $v;
		}

		return $tmp;
	}

	return false;
}

/**
 * Return the id for a given subtype.
 *
 * ElggEntity objects have a type and a subtype.  Subtypes
 * are defined upon creation and cannot be changed.
 *
 * Plugin authors generally don't need to use this function
 * unless writing their own SQL queries.  Use {@link ElggEntity::getSubtype()}
 * to return the string subtype.
 *
 * @warning {@link ElggEntity::subtype} returns the ID.  You probably want
 * {@link ElggEntity::getSubtype()} instead!
 *
 * @internal Subtypes are stored in the entity_subtypes table.  There is a foreign
 * key in the entities table.
 *
 * @todo Move to a nicer place?
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 *
 * @return int Subtype ID
 * @link http://docs.elgg.org/DataModel/Entities/Subtypes
 * @see get_subtype_from_id()
 * @access private
 */
function get_subtype_id($type, $subtype) {
	global $CONFIG, $SUBTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	if ($subtype == "") {
		return FALSE;
	}

	// Todo: cache here? Or is looping less efficient that going to the db each time?
	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes
		where type='$type' and subtype='$subtype'");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$result->id] = $result;
		return $result->id;
	}

	return FALSE;
}

/**
 * Return string name for a given subtype ID.
 *
 * @todo Move to a nicer place?
 *
 * @param int $subtype_id Subtype ID
 *
 * @return string Subtype name
 * @link http://docs.elgg.org/DataModel/Entities/Subtypes
 * @see get_subtype_from_id()
 * @access private
 */
function get_subtype_from_id($subtype_id) {
	global $CONFIG, $SUBTYPE_CACHE;

	$subtype_id = (int)$subtype_id;

	if (!$subtype_id) {
		return false;
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->subtype;
	}

	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");
	if ($result) {
		if (!$SUBTYPE_CACHE) {
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$subtype_id] = $result;
		return $result->subtype;
	}

	return false;
}

/**
 * Return a classname for a registered type and subtype.
 *
 * Entities can be registered to always be loaded as a certain class
 * with {@link register_entity_subtype()}.  This function returns
 * the class name if found, and NULL if not.
 *
 * @param string $type    The type
 * @param string $subtype The subtype
 *
 * @return string|null a class name or null
 * @see get_subtype_from_id()
 * @see get_subtype_class_from_id()
 * @access private
 */
function get_subtype_class($type, $subtype) {
	global $CONFIG, $SUBTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	// Todo: cache here? Or is looping less efficient that going to the db each time?
	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes
		where type='$type' and subtype='$subtype'");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			$SUBTYPE_CACHE = array();
		}

		$SUBTYPE_CACHE[$result->id] = $result;
		return $result->class;
	}

	return NULL;
}

/**
 * Returns the classname for a subtype id.
 *
 * @param int $subtype_id The subtype id
 *
 * @return string|null
 * @see get_subtype_class()
 * @see get_subtype_from_id()
 * @access private
 */
function get_subtype_class_from_id($subtype_id) {
	global $CONFIG, $SUBTYPE_CACHE;

	$subtype_id = (int)$subtype_id;

	if (!$subtype_id) {
		return false;
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->class;
	}

	$result = get_data_row("SELECT * from {$CONFIG->dbprefix}entity_subtypes where id=$subtype_id");

	if ($result) {
		if (!$SUBTYPE_CACHE) {
			$SUBTYPE_CACHE = array();
		}
		$SUBTYPE_CACHE[$subtype_id] = $result;
		return $result->class;
	}

	return NULL;
}

/**
 * Register ElggEntities with a certain type and subtype to be loaded as a specific class.
 *
 * By default entities are loaded as one of the 4 parent objects: site, user, object, or group.
 * If you subclass any of these you can register the classname with add_subtype() so
 * it will be loaded as that class automatically when retrieved from the database with
 * {@link get_entity()}.
 *
 * @param string $type    The type you're subtyping (site, user, object, or group)
 * @param string $subtype The subtype
 * @param string $class   Optional class name for the object
 *
 * @return int
 * @link http://docs.elgg.org/Tutorials/Subclasses
 * @link http://docs.elgg.org/DataModel/Entities
 * @see update_subtype()
 * @see remove_subtype()
 * @see get_entity()
 */
function add_subtype($type, $subtype, $class = "") {
	global $CONFIG;
	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$class = sanitise_string($class);

	// Short circuit if no subtype is given
	if ($subtype == "") {
		return 0;
	}

	$id = get_subtype_id($type, $subtype);

	if ($id == 0) {
		return insert_data("insert into {$CONFIG->dbprefix}entity_subtypes"
			. " (type, subtype, class) values ('$type','$subtype','$class')");
	}

	return $id;
}

/**
 * Removes a registered ElggEntity type, subtype, and classname.
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 *
 * @return bool
 * @see add_subtype()
 * @see update_subtype()
 */
function remove_subtype($type, $subtype) {
	global $CONFIG;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	return delete_data("DELETE FROM {$CONFIG->dbprefix}entity_subtypes"
		. " WHERE type = '$type' AND subtype = '$subtype'");
}

/**
 * Update a registered ElggEntity type, subtype, and classname
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 * @param string $class   Class name to use when loading this entity
 *
 * @return bool
 */
function update_subtype($type, $subtype, $class = '') {
	global $CONFIG;

	if (!$id = get_subtype_id($type, $subtype)) {
		return FALSE;
	}
	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	return update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes
		SET type = '$type', subtype = '$subtype', class = '$class'
		WHERE id = $id
	");
}

/**
 * Update an entity in the database.
 *
 * There are 4 basic entity types: site, user, object, and group.
 * All entities are split between two tables: the entities table and their type table.
 *
 * @warning Plugin authors should never call this directly. Use ->save() instead.
 *
 * @param int $guid           The guid of the entity to update
 * @param int $owner_guid     The new owner guid
 * @param int $access_id      The new access id
 * @param int $container_guid The new container guid
 *
 * @return bool
 * @link http://docs.elgg.org/DataModel/Entities
 * @access private
 */
function update_entity($guid, $owner_guid, $access_id, $container_guid = null) {
	global $CONFIG, $ENTITY_CACHE;

	$guid = (int)$guid;
	$owner_guid = (int)$owner_guid;
	$access_id = (int)$access_id;
	$container_guid = (int) $container_guid;
	if (is_null($container_guid)) {
		$container_guid = $owner_guid;
	}
	$time = time();

	$entity = get_entity($guid);

	if ($entity && $entity->canEdit()) {
		if (elgg_trigger_event('update', $entity->type, $entity)) {
			$ret = update_data("UPDATE {$CONFIG->dbprefix}entities"
				. " set owner_guid='$owner_guid', access_id='$access_id',"
				. " container_guid='$container_guid', time_updated='$time' WHERE guid=$guid");

			if ($entity instanceof ElggObject) {
				update_river_access_by_object($guid, $access_id);
			}

			// If memcache is available then delete this entry from the cache
			static $newentity_cache;
			if ((!$newentity_cache) && (is_memcache_available())) {
				$newentity_cache = new ElggMemcache('new_entity_cache');
			}
			if ($newentity_cache) {
				$new_entity = $newentity_cache->delete($guid);
			}

			// Handle cases where there was no error BUT no rows were updated!
			if ($ret === false) {
				return false;
			}

			return true;
		}
	}
}

/**
 * Determine if a given user is can write to an entity container.
 *
 * An entity can be a container for any other entity by setting the
 * container_guid.  container_guid can differ from owner_guid.
 *
 * A plugin hook container_permissions_check:$entity_type is emitted to allow granular
 * access controls in plugins.
 *
 * @param int    $user_guid      The user guid, or 0 for get_loggedin_userid()
 * @param int    $container_guid The container, or 0 for the current page owner.
 * @param string $type           The type of entity we're looking to write
 * @param string $subtype        The subtype of the entity we're looking to write
 *
 * @return bool
 * @link http://docs.elgg.org/DataModel/Containers
 */
function can_write_to_container($user_guid = 0, $container_guid = 0, $type = 'all', $subtype = 'all') {
	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	$container_guid = (int)$container_guid;
	if (!$container_guid) {
		$container_guid = elgg_get_page_owner_guid();
	}

	if (!$container_guid) {
		$return = TRUE;
	}

	$container = get_entity($container_guid);

	if ($container) {
		// If the user can edit the container, they can also write to it
		if ($container->canEdit($user_guid)) {
			$return = TRUE;
		}

		// Basics, see if the user is a member of the group.
		// @todo this should be moved to the groups plugin
		if ($user && $container instanceof ElggGroup) {
			if (!$container->isMember($user)) {
				$return = FALSE;
			} else {
				$return = TRUE;
			}
		}
	}

	// See if anyone else has anything to say
	return elgg_trigger_plugin_hook('container_permissions_check', $type,
		array('container' => $container, 'user' => $user, 'subtype' => $subtype), $return);
}

/**
 * Create a new entry in the entities table.
 *
 * Saves the base information in the entities table for the entity.  Saving
 * the type information is handled in the calling class method.
 *
 * @warning Plugin authors should never call this directly.  Always use entity objects.
 *
 * @warning Entities must have an entry in both the entities table and their type table
 * or they will throw an exception when loaded.
 *
 * @param string $type           The type of the entity (site, user, object, group).
 * @param string $subtype        The subtype of the entity.
 * @param int    $owner_guid     The GUID of the object's owner.
 * @param int    $access_id      The access control group to create the entity with.
 * @param int    $site_guid      The site to add this entity to. 0 for current.
 * @param int    $container_guid The container GUID
 *
 * @return int|false The new entity's GUID, or false on failure
 * @throws InvalidParameterException
 * @access private
 * @link http://docs.elgg.org/DataModel/Entities
 */
function create_entity($type, $subtype, $owner_guid, $access_id, $site_guid = 0,
$container_guid = 0) {

	global $CONFIG;

	$type = sanitise_string($type);
	$subtype_id = add_subtype($type, $subtype);
	$owner_guid = (int)$owner_guid;
	$access_id = (int)$access_id;
	$time = time();
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}
	$site_guid = (int) $site_guid;
	if ($container_guid == 0) {
		$container_guid = $owner_guid;
	}

	$user = get_loggedin_user();
	if (!can_write_to_container($user->guid, $owner_guid, $type, $subtype)) {
		return false;
	}
	if ($owner_guid != $container_guid) {
		if (!can_write_to_container($user->guid, $container_guid, $type, $subtype)) {
			return false;
		}
	}
	if ($type == "") {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:EntityTypeNotSet'));
	}

	return insert_data("INSERT into {$CONFIG->dbprefix}entities
		(type, subtype, owner_guid, site_guid, container_guid,
			access_id, time_created, time_updated, last_action)
		values
		('$type',$subtype_id, $owner_guid, $site_guid, $container_guid,
			$access_id, $time, $time, $time)");
}

/**
 * Returns a database row from the entities table.
 *
 * @tip Use get_entity() to return the fully loaded entity.
 *
 * @warning This will only return results if a) it exists, b) you have access to it.
 * see {@link get_access_sql_suffix()}.
 *
 * @param int $guid The GUID of the object to extract
 *
 * @return stdClass|false
 * @link http://docs.elgg.org/DataModel/Entities
 * @see entity_row_to_elggstar()
 * @access private
 */
function get_entity_as_row($guid) {
	global $CONFIG;

	if (!$guid) {
		return false;
	}

	$guid = (int) $guid;
	$access = get_access_sql_suffix();

	return get_data_row("SELECT * from {$CONFIG->dbprefix}entities where guid=$guid and $access");
}

/**
 * Create an Elgg* object from a given entity row.
 *
 * Handles loading all tables into the correct class.
 *
 * @param stdClass $row The row of the entry in the entities table.
 *
 * @return object|false
 * @link http://docs.elgg.org/DataModel/Entities
 * @see get_entity_as_row()
 * @see add_subtype()
 * @see get_entity()
 * @access private
 */
function entity_row_to_elggstar($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	if ((!isset($row->guid)) || (!isset($row->subtype))) {
		return $row;
	}

	$new_entity = false;

	// Create a memcache cache if we can
	static $newentity_cache;
	if ((!$newentity_cache) && (is_memcache_available())) {
		$newentity_cache = new ElggMemcache('new_entity_cache');
	}
	if ($newentity_cache) {
		$new_entity = $newentity_cache->load($row->guid);
	}
	if ($new_entity) {
		return $new_entity;
	}

	// load class for entity if one is registered
	$classname = get_subtype_class_from_id($row->subtype);
	if ($classname != "") {
		if (class_exists($classname)) {
			$new_entity = new $classname($row);

			if (!($new_entity instanceof ElggEntity)) {
				$msg = elgg_echo('ClassException:ClassnameNotClass', array($classname, 'ElggEntity'));
				throw new ClassException($msg);
			}
		} else {
			error_log(elgg_echo('ClassNotFoundException:MissingClass', array($classname)));
		}
	}

	if (!$new_entity) {
		//@todo Make this into a function
		switch ($row->type) {
			case 'object' :
				$new_entity = new ElggObject($row);
				break;
			case 'user' :
				$new_entity = new ElggUser($row);
				break;
			case 'group' :
				$new_entity = new ElggGroup($row);
				break;
			case 'site' :
				$new_entity = new ElggSite($row);
				break;
			default:
				$msg = elgg_echo('InstallationException:TypeNotSupported', array($row->type));
				throw new InstallationException($msg);
		}
	}

	// Cache entity if we have a cache available
	if (($newentity_cache) && ($new_entity)) {
		$newentity_cache->save($new_entity->guid, $new_entity);
	}

	return $new_entity;
}

/**
 * Loads and returns an entity object from a guid.
 *
 * @param int $guid The GUID of the entity
 *
 * @return ElggEntity The correct Elgg or custom object based upon entity type and subtype
 * @link http://docs.elgg.org/DataModel/Entities
 */
function get_entity($guid) {
	static $newentity_cache;
	$new_entity = false;

	if (!is_numeric($guid)) {
		return FALSE;
	}

	if ((!$newentity_cache) && (is_memcache_available())) {
		$newentity_cache = new ElggMemcache('new_entity_cache');
	}

	if ($newentity_cache) {
		$new_entity = $newentity_cache->load($guid);
	}

	if ($new_entity) {
		return $new_entity;
	}

	return entity_row_to_elggstar(get_entity_as_row($guid));
}


/**
 * Returns an array of entities with optional filtering.
 *
 * Entities are the basic unit of storage in Elgg.  This function
 * provides the simplest way to get an array of entities.  There
 * are many options available that can be passed to filter
 * what sorts of entities are returned.
 *
 * @tip To output formatted strings of entities, use {@link elgg_list_entities()} and
 * its cousins.
 *
 * @tip Plural arguments can be written as singular if only specifying a
 * single element.  ('type' => 'object' vs 'types' => array('object')).
 *
 * @param array $options Array in format:
 *
 * 	types => NULL|STR entity type (type IN ('type1', 'type2')
 *           Joined with subtypes by AND. See below)
 *
 * 	subtypes => NULL|STR entity subtype (SQL: subtype IN ('subtype1', 'subtype2))
 *
 * 	type_subtype_pairs => NULL|ARR (array('type' => 'subtype'))
 *                        (type = '$type' AND subtype = '$subtype') pairs
 *
 * 	owner_guids => NULL|INT entity guid
 *
 * 	container_guids => NULL|INT container_guid
 *
 * 	site_guids => NULL (current_site)|INT site_guid
 *
 * 	order_by => NULL (time_created desc)|STR SQL order by clause
 *
 * 	limit => NULL (10)|INT SQL limit clause
 *
 * 	offset => NULL (0)|INT SQL offset clause
 *
 * 	created_time_lower => NULL|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => NULL|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => NULL|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => NULL|INT Modified time upper boundary in epoch time
 *
 * 	count => TRUE|FALSE return a count instead of entities
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *
 * @return mixed int if count is true, an array of entity objects, or false on failure
 * @since 1.7.0
 * @see elgg_get_entities_from_metadata()
 * @see elgg_get_entities_from_relationship()
 * @see elgg_get_entities_from_access_id()
 * @see elgg_get_entities_from_annotations()
 * @see elgg_list_entities()
 * @link http://docs.elgg.org/DataModel/Entities/Getters
 */
function elgg_get_entities(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	$CONFIG->site_guid,

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		'order_by' 				=>	'e.time_created desc',
		'group_by'				=>	ELGG_ENTITIES_ANY_VALUE,
		'limit'					=>	10,
		'offset'				=>	0,
		'count'					=>	FALSE,
		'selects'				=>	array(),
		'wheres'				=>	array(),
		'joins'					=>	array()
	);

	$options = array_merge($defaults, $options);

	// can't use helper function with type_subtype_pair because
	// it's already an array...just need to merge it
	if (isset($options['type_subtype_pair'])) {
		if (isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = array_merge($options['type_subtype_pairs'],
				$options['type_subtype_pair']);
		} else {
			$options['type_subtype_pairs'] = $options['type_subtype_pair'];
		}
	}

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'site_guid');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	}

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_entity_type_subtype_where_sql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = elgg_get_entity_site_where_sql('e', $options['site_guids']);
	$wheres[] = elgg_get_entity_owner_where_sql('e', $options['owner_guids']);
	$wheres[] = elgg_get_entity_container_where_sql('e', $options['container_guids']);
	$wheres[] = elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	}

	// remove identical join clauses
	$joins = array_unique($options['joins']);

	foreach ($joins as $i => $join) {
		if ($join === FALSE) {
			return FALSE;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}

	// evalutate selects
	if ($options['selects']) {
		$selects = '';
		foreach ($options['selects'] as $select) {
			$selects = ", $select";
		}
	} else {
		$selects = '';
	}

	if (!$options['count']) {
		$query = "SELECT DISTINCT e.*{$selects} FROM {$CONFIG->dbprefix}entities e ";
	} else {
		$query = "SELECT count(DISTINCT e.guid) as total FROM {$CONFIG->dbprefix}entities e ";
	}

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$options['count']) {
		if ($options['group_by'] = sanitise_string($options['group_by'])) {
			$query .= " GROUP BY {$options['group_by']}";
		}

		if ($options['order_by'] = sanitise_string($options['order_by'])) {
			$query .= " ORDER BY {$options['order_by']}";
		}

		if ($options['limit']) {
			$limit = sanitise_int($options['limit']);
			$offset = sanitise_int($options['offset']);
			$query .= " LIMIT $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");

		//@todo normalize this to array()
		return $dt;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * Returns entities.
 *
 * @deprecated 1.7.  Use elgg_get_entities().
 *
 * @param string $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     Owner GUID
 * @param string $order_by       Order by clause
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param bool   $count          Return a count or an array of entities
 * @param int    $site_guid      Site GUID
 * @param int    $container_guid Container GUID
 * @param int    $timelower      Lower time limit
 * @param int    $timeupper      Upper time limit
 *
 * @return array
 */
function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10,
$offset = 0, $count = false, $site_guid = 0, $container_guid = null, $timelower = 0,
$timeupper = 0) {

	elgg_deprecated_notice('get_entities() was deprecated by elgg_get_entities().', 1.7);

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
			$owner_guid = NULL;
		}
	}

	$options = array();
	if ($type) {
		if (is_array($type)) {
			$options['types'] = $type;
		} else {
			$options['type'] = $type;
		}
	}

	if ($subtype) {
		if (is_array($subtype)) {
			$options['subtypes'] = $subtype;
		} else {
			$options['subtype'] = $subtype;
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($order_by) {
		$options['order_by'] = $order_by;
	}

	// need to pass 0 for all option
	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($site_guid) {
		$options['site_guids'] = $site_guid;
	}

	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($timeupper) {
		$options['created_time_upper'] = $timeupper;
	}

	if ($timelower) {
		$options['created_time_lower'] = $timelower;
	}

	$r = elgg_get_entities($options);
	return $r;
}

/**
 * Returns SQL where clause for type and subtype on main entity table
 *
 * @param string     $table    Entity table prefix as defined in SELECT...FROM entities $table
 * @param NULL|array $types    Array of types or NULL if none.
 * @param NULL|array $subtypes Array of subtypes or NULL if none
 * @param NULL|array $pairs    Array of pairs of types and subtypes
 *
 * @return FALSE|string
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// subtype depends upon type.
	if ($subtypes && !$types) {
		elgg_log("Cannot set subtypes without type.", 'WARNING');
		return FALSE;
	}

	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	// these are the only valid types for entities in elgg as defined in the DB.
	$valid_types = array('object', 'user', 'group', 'site');

	// pairs override
	$wheres = array();
	if (!is_array($pairs)) {
		if (!is_array($types)) {
			$types = array($types);
		}

		if ($subtypes && !is_array($subtypes)) {
			$subtypes = array($subtypes);
		}

		// decrementer for valid types.  Return FALSE if no valid types
		$valid_types_count = count($types);
		$valid_subtypes_count = 0;
		// remove invalid types to get an accurate count of
		// valid types for the invalid subtype detection to use
		// below.
		// also grab the count of ALL subtypes on valid types to decrement later on
		// and check against.
		//
		// yes this is duplicating a foreach on $types.
		foreach ($types as $type) {
			if (!in_array($type, $valid_types)) {
				$valid_types_count--;
				unset ($types[array_search($type, $types)]);
			} else {
				// do the checking (and decrementing) in the subtype section.
				$valid_subtypes_count += count($subtypes);
			}
		}

		// return false if nothing is valid.
		if (!$valid_types_count) {
			return FALSE;
		}

		// subtypes are based upon types, so we need to look at each
		// type individually to get the right subtype id.
		foreach ($types as $type) {
			$subtype_ids = array();
			if ($subtypes) {
				foreach ($subtypes as $subtype) {
					// check that the subtype is valid (with ELGG_ENTITIES_NO_VALUE being a valid subtype)
					if (ELGG_ENTITIES_NO_VALUE === $subtype || $subtype_id = get_subtype_id($type, $subtype)) {
						$subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $subtype) ? ELGG_ENTITIES_NO_VALUE : $subtype_id;
					} else {
						$valid_subtypes_count--;
						elgg_log("Type-subtype $type:$subtype' does not exist!", 'WARNING');
						continue;
					}
				}

				// return false if we're all invalid subtypes in the only valid type
				if ($valid_subtypes_count <= 0) {
					return FALSE;
				}
			}

			if (is_array($subtype_ids) && count($subtype_ids)) {
				$subtype_ids_str = implode(',', $subtype_ids);
				$wheres[] = "({$table}.type = '$type' AND {$table}.subtype IN ($subtype_ids_str))";
			} else {
				$wheres[] = "({$table}.type = '$type')";
			}
		}
	} else {
		// using type/subtype pairs
		$valid_pairs_count = count($pairs);
		$valid_pairs_subtypes_count = 0;

		// same deal as above--we need to know how many valid types
		// and subtypes we have before hitting the subtype section.
		// also normalize the subtypes into arrays here.
		foreach ($pairs as $paired_type => $paired_subtypes) {
			if (!in_array($paired_type, $valid_types)) {
				$valid_pairs_count--;
				unset ($pairs[array_search($paired_type, $pairs)]);
			} else {
				if ($paired_subtypes && !is_array($paired_subtypes)) {
					$pairs[$paired_type] = array($paired_subtypes);
				}
				$valid_pairs_subtypes_count += count($paired_subtypes);
			}
		}

		if ($valid_pairs_count <= 0) {
			return FALSE;
		}
		foreach ($pairs as $paired_type => $paired_subtypes) {
			// this will always be an array because of line 2027, right?
			// no...some overly clever person can say pair => array('object' => null)
			if (is_array($paired_subtypes)) {
				$paired_subtype_ids = array();
				foreach ($paired_subtypes as $paired_subtype) {
					if (ELGG_ENTITIES_NO_VALUE === $paired_subtype
					|| ($paired_subtype_id = get_subtype_id($paired_type, $paired_subtype))) {

						$paired_subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $paired_subtype) ?
							ELGG_ENTITIES_NO_VALUE : $paired_subtype_id;
					} else {
						$valid_pairs_subtypes_count--;
						elgg_log("Type-subtype $paired_type:$paired_subtype' does not exist!", 'WARNING');
						// return false if we're all invalid subtypes in the only valid type
						continue;
					}
				}

				// return false if there are no valid subtypes.
				if ($valid_pairs_subtypes_count <= 0) {
					return FALSE;
				}


				if ($paired_subtype_ids_str = implode(',', $paired_subtype_ids)) {
					$wheres[] = "({$table}.type = '$paired_type'"
						. " AND {$table}.subtype IN ($paired_subtype_ids_str))";
				}
			} else {
				$wheres[] = "({$table}.type = '$paired_type')";
			}
		}
	}

	// pairs override the above.  return false if they don't exist.
	if (is_array($wheres) && count($wheres)) {
		$where = implode(' OR ', $wheres);
		return "($where)";
	}

	return '';
}

/**
 * Returns SQL where clause for owner and containers.
 *
 * @todo Probably DRY up once things are settled.
 *
 * @param string     $table       Entity table prefix as defined in SELECT...FROM entities $table
 * @param NULL|array $owner_guids Owner GUIDs
 *
 * @return FALSE|str
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_owner_where_sql($table, $owner_guids) {
	// short circuit if nothing requested
	// 0 is a valid owner_guid.
	if (!$owner_guids && $owner_guids !== 0) {
		return '';
	}

	// normalize and sanitise owners
	if (!is_array($owner_guids)) {
		$owner_guids = array($owner_guids);
	}

	$owner_guids_sanitised = array();
	foreach ($owner_guids as $owner_guid) {
		if (($owner_guid != sanitise_int($owner_guid))) {
			return FALSE;
		}
		$owner_guids_sanitised[] = $owner_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (($owner_str = implode(',', $owner_guids_sanitised))
	&& ($owner_str !== FALSE) && ($owner_str !== '')) {

		$where = "({$table}.owner_guid IN ($owner_str))";
	}

	return $where;
}

/**
 * Returns SQL where clause for containers.
 *
 * @param string     $table           Entity table prefix as defined in
 *                                    SELECT...FROM entities $table
 * @param NULL|array $container_guids Array of container guids
 *
 * @return FALSE|string
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_container_where_sql($table, $container_guids) {
	// short circuit if nothing is requested.
	// 0 is a valid container_guid.
	if (!$container_guids && $container_guids !== 0) {
		return '';
	}

	// normalize and sanitise containers
	if (!is_array($container_guids)) {
		$container_guids = array($container_guids);
	}

	$container_guids_sanitised = array();
	foreach ($container_guids as $container_guid) {
		if (($container_guid != sanitise_int($container_guid))) {
			return FALSE;
		}
		$container_guids_sanitised[] = $container_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (FALSE !== $container_str = implode(',', $container_guids_sanitised)) {
		$where = "({$table}.container_guid IN ($container_str))";
	}

	return $where;
}

/**
 * Returns SQL where clause for entity time limits.
 *
 * @param string   $table              Entity table prefix as defined in
 *                                     SELECT...FROM entities $table
 * @param NULL|int $time_created_upper Time crated upper limit
 * @param NULL|int $time_created_lower Time created lower limit
 * @param NULL|int $time_updated_upper Time updated upper limit
 * @param NULL|int $time_updated_lower Time updated lower limit
 *
 * @return FALSE|str FALSE on fail, string on success.
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_time_where_sql($table, $time_created_upper = NULL,
$time_created_lower = NULL, $time_updated_upper = NULL, $time_updated_lower = NULL) {

	$wheres = array();

	// exploit PHP's loose typing (quack) to check that they are INTs and not str cast to 0
	if ($time_created_upper && $time_created_upper == sanitise_int($time_created_upper)) {
		$wheres[] = "{$table}.time_created <= $time_created_upper";
	}

	if ($time_created_lower && $time_created_lower == sanitise_int($time_created_lower)) {
		$wheres[] = "{$table}.time_created >= $time_created_lower";
	}

	if ($time_updated_upper && $time_updated_upper == sanitise_int($time_updated_upper)) {
		$wheres[] = "{$table}.time_updated <= $time_updated_upper";
	}

	if ($time_updated_lower && $time_updated_lower == sanitise_int($time_updated_lower)) {
		$wheres[] = "{$table}.time_updated >= $time_updated_lower";
	}

	if (is_array($wheres) && count($wheres) > 0) {
		$where_str = implode(' AND ', $wheres);
		return "($where_str)";
	}

	return '';
}

/**
 * Returns SQL where clause for site entities
 *
 * @param string     $table      Entity table prefix as defined in SELECT...FROM entities $table
 * @param NULL|array $site_guids Array of site guids
 *
 * @return FALSE|string
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_site_where_sql($table, $site_guids) {
	// short circuit if nothing requested
	if (!$site_guids) {
		return '';
	}

	if (!is_array($site_guids)) {
		$site_guids = array($site_guids);
	}

	$site_guids_sanitised = array();
	foreach ($site_guids as $site_guid) {
		if (!$site_guid || ($site_guid != sanitise_int($site_guid))) {
			return FALSE;
		}
		$site_guids_sanitised[] = $site_guid;
	}

	if ($site_guids_str = implode(',', $site_guids_sanitised)) {
		return "({$table}.site_guid IN ($site_guids_str))";
	}

	return '';
}

/**
 * Returns a string of parsed entities.
 *
 * Displays list of entities with formatting specified
 * by the entity view.
 *
 * @tip Pagination is handled automatically.
 *
 * @param array $options Any options from $getter options plus:
 * 	 full_view => BOOL Display full view entities
 * 	 list_type_toggle => BOOL Display gallery / list switch
 * 	 pagination => BOOL Display pagination links
 *
 * @param mixed $getter  The entity getter function to use to fetch the entities
 *
 * @return string
 * @since 1.7
 * @see elgg_get_entities()
 * @see elgg_view_entity_list()
 * @link http://docs.elgg.org/Entities/Output
 */
function elgg_list_entities(array $options = array(), $getter = 'elgg_get_entities') {
	$defaults = array(
		'offset' => (int) max(get_input('offset', 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'full_view' => TRUE,
		'list_type_toggle' => FALSE,
		'pagination' => TRUE,
	);

	$options = array_merge($defaults, $options);

	//backwards compatibility
	if (isset($options['view_type_toggle'])) {
		$options['list_type_toggle'] = $options['view_type_toggle'];
	}
	
	$options['count'] = TRUE;
	$count = $getter($options);

	$options['count'] = FALSE;
	$entities = $getter($options);

	return elgg_view_entity_list($entities, $count, $options['offset'], $options['limit'],
		$options['full_view'], $options['list_type_toggle'], $options['pagination']);
}

/**
 * Lists entities
 *
 * @deprecated 1.7.  Use elgg_list_entities().
 *
 * @param string $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param bool   $fullview       Display entity full views?
 * @param bool   $listtypetoggle Allow switching to gallery mode?
 * @param bool   $pagination     Show pagination?
 *
 * @return string
 */
function list_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $pagination = true) {

	elgg_deprecated_notice('list_entities() was deprecated by elgg_list_entities()!', 1.7);

	$options = array();

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid) {
		$options['container_guids'] = $owner_guid;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities($options);
}

/**
 * Returns a list of months in which entities were updated or created.
 *
 * @tip Use this to generate a list of archives by month for when entities were added or updated.
 *
 * @warning Months are returned in the form YYYYMM.
 *
 * @param string $type           The type of entity
 * @param string $subtype        The subtype of entity
 * @param int    $container_guid The container GUID that the entinties belong to
 * @param int    $site_guid      The site GUID
 * @param str    $order_by       Order_by SQL order by clause
 *
 * @return array|false Either an array months as YYYYMM, or false on failure
 */
function get_entity_dates($type = '', $subtype = '', $container_guid = 0, $site_guid = 0,
$order_by = 'time_created') {

	global $CONFIG;

	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}
	$where = array();

	if ($type != "") {
		$type = sanitise_string($type);
		$where[] = "type='$type'";
	}

	if (is_array($subtype)) {
		$tempwhere = "";
		if (sizeof($subtype)) {
			foreach ($subtype as $typekey => $subtypearray) {
				foreach ($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval)) {
							return false;
						}
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) {
						$tempwhere .= " or ";
					}
					$tempwhere .= "(type = '{$typekey}' and subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		if ($subtype) {
			if (!$subtype_id = get_subtype_id($type, $subtype)) {
				return FALSE;
			} else {
				$where[] = "subtype=$subtype_id";
			}
		}
	}

	if ($container_guid !== 0) {
		if (is_array($container_guid)) {
			foreach ($container_guid as $key => $val) {
				$container_guid[$key] = (int) $val;
			}
			$where[] = "container_guid in (" . implode(",", $container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "container_guid = {$container_guid}";
		}
	}

	if ($site_guid > 0) {
		$where[] = "site_guid = {$site_guid}";
	}

	$where[] = get_access_sql_suffix();

	$sql = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(time_created)) AS yearmonth
		FROM {$CONFIG->dbprefix}entities where ";

	foreach ($where as $w) {
		$sql .= " $w and ";
	}

	$sql .= "1=1 ORDER BY $order_by";
	if ($result = get_data($sql)) {
		$endresult = array();
		foreach ($result as $res) {
			$endresult[] = $res->yearmonth;
		}
		return $endresult;
	}
	return false;
}

/**
 * Disable an entity.
 *
 * Disabled entities do not show up in list or elgg_get_entity()
 * calls, but still exist in the database.
 *
 * Entities are disabled by setting disabled = yes in the
 * entities table.
 *
 * You can ignore the disabled field by using {@link access_show_hidden_entities()}.
 *
 * @note Use ElggEntity::disable() instead.
 *
 * @param int    $guid      The guid
 * @param string $reason    Optional reason
 * @param bool   $recursive Recursively disable all entities owned or contained by $guid?
 *
 * @return bool
 * @access private
 * @see access_show_hidden_entities()
 * @link http://docs.elgg.org/Entities
 */
function disable_entity($guid, $reason = "", $recursive = true) {
	global $CONFIG;

	$guid = (int)$guid;
	$reason = sanitise_string($reason);

	if ($entity = get_entity($guid)) {
		if (elgg_trigger_event('disable', $entity->type, $entity)) {
			if ($entity->canEdit()) {
				if ($reason) {
					create_metadata($guid, 'disable_reason', $reason, '', 0, ACCESS_PUBLIC);
				}

				if ($recursive) {
					// Temporary token overriding access controls
					// @todo Do this better.
					static $__RECURSIVE_DELETE_TOKEN;
					// Make it slightly harder to guess
					$__RECURSIVE_DELETE_TOKEN = md5(get_loggedin_userid());

					$sub_entities = get_data("SELECT * from {$CONFIG->dbprefix}entities
						WHERE container_guid=$guid
						or owner_guid=$guid
						or site_guid=$guid", 'entity_row_to_elggstar');

					if ($sub_entities) {
						foreach ($sub_entities as $e) {
							$e->disable($reason);
						}
					}

					$__RECURSIVE_DELETE_TOKEN = null;
				}

				$res = update_data("UPDATE {$CONFIG->dbprefix}entities
					set enabled='no'
					where guid={$guid}");

				return $res;
			}
		}
	}
	return false;
}

/**
 * Enable an entity.
 *
 * @warning In order to enable an entity using ElggEntity::enable(),
 * you must first use {@link access_show_hidden_entities()}.
 *
 * @param int $guid GUID of entity to enable
 *
 * @return bool
 */
function enable_entity($guid) {
	global $CONFIG;

	$guid = (int)$guid;

	// Override access only visible entities
	$access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	if ($entity = get_entity($guid)) {
		if (elgg_trigger_event('enable', $entity->type, $entity)) {
			if ($entity->canEdit()) {

				access_show_hidden_entities($access_status);

				$result = update_data("UPDATE {$CONFIG->dbprefix}entities
					set enabled='yes'
					where guid={$guid}");
				$entity->clearMetaData('disable_reason');

				return $result;
			}
		}
	}

	access_show_hidden_entities($access_status);
	return false;
}

/**
 * Delete an entity.
 *
 * Removes an entity and its metadata, annotations, relationships, river entries,
 * and private data.
 *
 * Optionally can remove entities contained and owned by $guid.
 *
 * @tip Use ElggEntity::delete() instead.
 *
 * @warning If deleting recursively, this bypasses ownership of items contained by
 * the entity.  That means that if the container_guid = $guid, the item will be deleted
 * regardless of who owns it.
 *
 * @param int  $guid      The guid of the entity to delete
 * @param bool $recursive If true (default) then all entities which are
 *                        owned or contained by $guid will also be deleted.
 *
 * @access private
 * @return bool
 */
function delete_entity($guid, $recursive = true) {
	global $CONFIG, $ENTITY_CACHE;

	$guid = (int)$guid;
	if ($entity = get_entity($guid)) {
		if (elgg_trigger_event('delete', $entity->type, $entity)) {
			if ($entity->canEdit()) {

				// delete cache
				if (isset($ENTITY_CACHE[$guid])) {
					invalidate_cache_for_entity($guid);
				}

				// Delete contained owned and otherwise releated objects (depth first)
				if ($recursive) {
					// Temporary token overriding access controls
					// @todo Do this better.
					static $__RECURSIVE_DELETE_TOKEN;
					// Make it slightly harder to guess
					$__RECURSIVE_DELETE_TOKEN = md5(get_loggedin_userid());

					$sub_entities = get_data("SELECT * from {$CONFIG->dbprefix}entities
						WHERE container_guid=$guid
							or owner_guid=$guid
							or site_guid=$guid", 'entity_row_to_elggstar');
					if ($sub_entities) {
						foreach ($sub_entities as $e) {
							$e->delete();
						}
					}

					$__RECURSIVE_DELETE_TOKEN = null;
				}

				// Now delete the entity itself
				$entity->clearMetadata();
				$entity->clearAnnotations();
				$entity->clearRelationships();
				remove_from_river_by_subject($guid);
				remove_from_river_by_object($guid);
				remove_all_private_settings($guid);
				$res = delete_data("DELETE from {$CONFIG->dbprefix}entities where guid={$guid}");
				if ($res) {
					$sub_table = "";

					// Where appropriate delete the sub table
					switch ($entity->type) {
						case 'object' :
							$sub_table = $CONFIG->dbprefix . 'objects_entity';
							break;
						case 'user' :
							$sub_table = $CONFIG->dbprefix . 'users_entity';
							break;
						case 'group' :
							$sub_table = $CONFIG->dbprefix . 'groups_entity';
							break;
						case 'site' :
							$sub_table = $CONFIG->dbprefix . 'sites_entity';
							break;
					}

					if ($sub_table) {
						delete_data("DELETE from $sub_table where guid={$guid}");
					}
				}

				return $res;
			}
		}
	}
	return false;

}

/**
 * Delete multiple entities that match a given query.
 * This function iterates through and calls delete_entity on
 * each one, this is somewhat inefficient but lets
 * the 'delete' event be called for each entity.
 *
 * @deprecated 1.7. This is a dangerous function as it defaults to deleting everything.
 *
 * @param string $type       The type of entity (eg "user", "object" etc)
 * @param string $subtype    The arbitrary subtype of the entity
 * @param int    $owner_guid The GUID of the owning user
 *
 * @return false
 */
function delete_entities($type = "", $subtype = "", $owner_guid = 0) {
	elgg_deprecated_notice('delete_entities() was deprecated because no one should use it.', 1.7);
	return false;
}

/**
 * Exports attributes generated on the fly (volatile) about an entity.
 *
 * @param string $hook        volatile
 * @param string $entity_type metadata
 * @param string $returnvalue Return value from previous hook
 * @param array  $params      The parameters, passed 'guid' and 'varname'
 *
 * @return null
 * @elgg_plugin_hook_handler volatile metadata
 * @todo investigate more.
 * @access private
 * @todo document
 */
function volatile_data_export_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$guid = (int)$params['guid'];
	$variable_name = sanitise_string($params['varname']);

	if (($hook == 'volatile') && ($entity_type == 'metadata')) {
		if (($guid) && ($variable_name)) {
			switch ($variable_name) {
				case 'renderedentity' :
					elgg_set_viewtype('default');
					$view = elgg_view_entity(get_entity($guid));
					elgg_set_viewtype();

					$tmp = new ElggMetadata();
					$tmp->type = 'volatile';
					$tmp->name = 'renderedentity';
					$tmp->value = $view;
					$tmp->entity_guid = $guid;

					return $tmp;

				break;
			}
		}
	}
}

/**
 * Exports all attributes of an entity.
 *
 * @warning Only exports fields in the entity and entity type tables.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook return value
 * @param array  $params      Parameters
 *
 * @elgg_event_handler export all
 * @return mixed
 */
function export_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];

	// Get the entity
	$entity = get_entity($guid);
	if (!($entity instanceof ElggEntity)) {
		$msg = elgg_echo('InvalidClassException:NotValidElggStar', array($guid, get_class()));
		throw new InvalidClassException($msg);
	}

	$export = $entity->export();

	if (is_array($export)) {
		foreach ($export as $e) {
			$returnvalue[] = $e;
		}
	} else {
		$returnvalue[] = $export;
	}

	return $returnvalue;
}

/**
 * Utility function used by import_entity_plugin_hook() to
 * process an ODDEntity into an unsaved ElggEntity.
 *
 * @param ODDEntity $element The OpenDD element
 *
 * @return ElggEntity the unsaved entity which should be populated by items.
 * @todo Remove this.
 */
function oddentity_to_elggentity(ODDEntity $element) {
	$class = $element->getAttribute('class');
	$subclass = $element->getAttribute('subclass');

	// See if we already have imported this uuid
	$tmp = get_entity_from_uuid($element->getAttribute('uuid'));

	if (!$tmp) {
		// Construct new class with owner from session
		$classname = get_subtype_class($class, $subclass);
		if ($classname != "") {
			if (class_exists($classname)) {
				$tmp = new $classname();

				if (!($tmp instanceof ElggEntity)) {
					$msg = elgg_echo('ClassException:ClassnameNotClass', array($classname, get_class()));
					throw new ClassException($msg);
				}
			} else {
				error_log(elgg_echo('ClassNotFoundException:MissingClass', array($classname)));
			}
		} else {
			switch ($class) {
				case 'object' :
					$tmp = new ElggObject($row);
					break;
				case 'user' :
					$tmp = new ElggUser($row);
					break;
				case 'group' :
					$tmp = new ElggGroup($row);
					break;
				case 'site' :
					$tmp = new ElggSite($row);
					break;
				default:
					$msg = elgg_echo('InstallationException:TypeNotSupported', array($class));
					throw new InstallationException($msg);
			}
		}
	}

	if ($tmp) {
		if (!$tmp->import($element)) {
			$msg = elgg_echo('ImportException:ImportFailed', array($element->getAttribute('uuid')));
			throw new ImportException($msg);
		}

		return $tmp;
	}

	return NULL;
}

/**
 * Import an entity.
 *
 * This function checks the passed XML doc (as array) to see if it is
 * a user, if so it constructs a new elgg user and returns "true"
 * to inform the importer that it's been handled.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @elgg_plugin_hook_handler import all
 * @todo document
 *
 */
function import_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDEntity) {
		$tmp = oddentity_to_elggentity($element);

		if ($tmp) {
			// Make sure its saved
			if (!$tmp->save()) {
				elgg_echo('ImportException:ProblemSaving', array($element->getAttribute('uuid')));
				throw new ImportException($msg);
			}

			// Belts and braces
			if (!$tmp->guid) {
				throw new ImportException(elgg_echo('ImportException:NoGUID'));
			}

			// We have saved, so now tag
			add_uuid_to_guid($tmp->guid, $element->getAttribute('uuid'));

			return $tmp;
		}
	}
}

/**
 * Returns if $user_guid is able to edit $entity_guid.
 *
 * @tip Can be overridden by by registering for the permissions_check
 * plugin hook.
 *
 * @warning If a $user_guid is not passed it will default to the logged in user.
 *
 * @tip Use ElggEntity::canEdit() instead.
 *
 * @param int $entity_guid The GUID of the entity
 * @param int $user_guid   The GUID of the user
 *
 * @return bool
 * @link http://docs.elgg.org/Entities/AccessControl
 */
function can_edit_entity($entity_guid, $user_guid = 0) {
	global $CONFIG;

	$user_guid = (int)$user_guid;
	$user = get_entity($user_guid);
	if (!$user) {
		$user = get_loggedin_user();
	}

	if ($entity = get_entity($entity_guid)) {
		$return = false;

		// Test user if possible - should default to false unless a plugin hook says otherwise
		if ($user) {
			if ($entity->getOwnerGUID() == $user->getGUID()) {
				$return = true;
			}
			if ($entity->container_guid == $user->getGUID()) {
				$return = true;
			}
			if ($entity->type == "user" && $entity->getGUID() == $user->getGUID()) {
				$return = true;
			}
			if ($container_entity = get_entity($entity->container_guid)) {
				if ($container_entity->canEdit($user->getGUID())) {
					$return = true;
				}
			}
		}

		return elgg_trigger_plugin_hook('permissions_check', $entity->type,
			array('entity' => $entity, 'user' => $user), $return);

	} else {
		return false;
	}
}

/**
 * Returns if $user_guid can edit the metadata on $entity_guid.
 *
 * @tip Can be overridden by by registering for the permissions_check:metadata
 * plugin hook.
 *
 * @warning If a $user_guid isn't specified, the currently logged in user is used.
 *
 * @param int          $entity_guid The GUID of the entity
 * @param int          $user_guid   The GUID of the user
 * @param ElggMetadata $metadata    The metadata to specifically check (if any; default null)
 *
 * @return bool
 * @see elgg_register_plugin_hook_handler()
 */
function can_edit_entity_metadata($entity_guid, $user_guid = 0, $metadata = null) {
	if ($entity = get_entity($entity_guid)) {

		$return = null;

		if ($metadata->owner_guid == 0) {
			$return = true;
		}
		if (is_null($return)) {
			$return = can_edit_entity($entity_guid, $user_guid);
		}

		$user = get_entity($user_guid);
		$params = array('entity' => $entity, 'user' => $user, 'metadata' => $metadata);
		$return = elgg_trigger_plugin_hook('permissions_check:metadata', $entity->type, $parms, $return);
		return $return;
	} else {
		return false;
	}
}

/**
 * Return the icon URL for an entity.
 *
 * @tip Can be overridden by registering a plugin hook for entity:icon:url, $entity_type.
 *
 * @internal This is passed an entity rather than a guid to handle non-created entities.
 *
 * @param ElggEntity $entity The entity
 * @param string     $size   Icon size
 *
 * @return string URL to the entity icon.
 */
function get_entity_icon_url(ElggEntity $entity, $size = 'medium') {
	global $CONFIG;

	$size = sanitise_string($size);
	switch (strtolower($size)) {
		case 'master':
			$size = 'master';
			break;

		case 'large' :
			$size = 'large';
			break;

		case 'topbar' :
			$size = 'topbar';
			break;

		case 'tiny' :
			$size = 'tiny';
			break;

		case 'small' :
			$size = 'small';
			break;

		case 'medium' :
		default:
			$size = 'medium';
	}

	$url = false;

	$viewtype = elgg_get_viewtype();

	// Step one, see if anyone knows how to render this in the current view
	$params = array('entity' => $entity, 'viewtype' => $viewtype, 'size' => $size);
	$url = elgg_trigger_plugin_hook('entity:icon:url', $entity->getType(), $params, $url);

	// Fail, so use default
	if (!$url) {
		$type = $entity->getType();
		$subtype = $entity->getSubtype();

		if (!empty($subtype)) {
			$overrideurl = elgg_view("icon/{$type}/{$subtype}/{$size}", array('entity' => $entity));
			if (!empty($overrideurl)) {
				return $overrideurl;
			}
		}

		$overrideurl = elgg_view("icon/{$type}/default/{$size}", array('entity' => $entity));
		if (!empty($overrideurl)) {
			return $overrideurl;
		}

		$url = "_graphics/icons/default/$size.png";
	}

	return elgg_normalize_url($url);
}

/**
 * Returns the URL for an entity.
 *
 * @tip Can be overridden with {@link register_entity_url_handler()}.
 *
 * @param int $entity_guid The GUID of the entity
 *
 * @return string The URL of the entity
 * @see register_entity_url_handler()
 */
function get_entity_url($entity_guid) {
	global $CONFIG;

	if ($entity = get_entity($entity_guid)) {
		$url = "";

		if (isset($CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()])) {
			$function = $CONFIG->entity_url_handler[$entity->getType()][$entity->getSubType()];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		} elseif (isset($CONFIG->entity_url_handler[$entity->getType()]['all'])) {
			$function = $CONFIG->entity_url_handler[$entity->getType()]['all'];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		} elseif (isset($CONFIG->entity_url_handler['all']['all'])) {
			$function = $CONFIG->entity_url_handler['all']['all'];
			if (is_callable($function)) {
				$url = $function($entity);
			}
		}

		if ($url == "") {
			$url = "pg/view/" . $entity_guid;
		}

		return elgg_normalize_url($url);

	}

	return false;
}

/**
 * Sets the URL handler for a particular entity type and subtype
 *
 * @param string $function_name  The function to register
 * @param string $entity_type    The entity type
 * @param string $entity_subtype The entity subtype
 *
 * @return true|false Depending on success
 * @see get_entity_url()
 * @see ElggEntity::getURL()
 */
function register_entity_url_handler($function_name, $entity_type = "all",
$entity_subtype = "all") {

	global $CONFIG;

	if (!is_callable($function_name)) {
		return false;
	}

	if (!isset($CONFIG->entity_url_handler)) {
		$CONFIG->entity_url_handler = array();
	}

	if (!isset($CONFIG->entity_url_handler[$entity_type])) {
		$CONFIG->entity_url_handler[$entity_type] = array();
	}

	$CONFIG->entity_url_handler[$entity_type][$entity_subtype] = $function_name;

	return true;
}

/**
 * Default Icon handler for entities.
 *
 * @tip This will attempt to find a default entity for the current view and return a url.
 * This is registered at a high priority so that other handlers will pick it up first.
 *
 * @param string $hook        entity:icon:url
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook's return value
 * @param array  $params      Array of params
 *
 * @return string|null String of URL for entity's icon
 * @elgg_plugin_hook_handler entity:icon:url all
 */
function default_entity_icon_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;

	if ((!$returnvalue) && ($hook == 'entity:icon:url')) {
		$entity = $params['entity'];
		$type = $entity->type;
		$subtype = get_subtype_from_id($entity->subtype);
		$viewtype = $params['viewtype'];
		$size = $params['size'];

		$url = "views/$viewtype/graphics/icons/$type/$subtype/$size.png";

		if (!@file_exists($CONFIG->path . $url)) {
			$url = "views/$viewtype/graphics/icons/$type/default/$size.png";
		}

		if (!@file_exists($CONFIG->path . $url)) {
			$url = "views/$viewtype/graphics/icons/default/$size.png";
		}

		if (@file_exists($CONFIG->path . $url)) {
			return elgg_get_site_url().$url;
		}
	}
}

/**
 * Registers an entity type and subtype as a public-facing entity that should
 * be shown in search and by {@link elgg_list_registered_entities()}.
 *
 * @warning Entities that aren't registered here will not show up in search.
 *
 * @tip Add a language string item:type:subtype to make sure the items are display properly.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return true|false Depending on success
 * @see get_registered_entity_types()
 * @link http://docs.elgg.org/Search
 * @link http://docs.elgg.org/Tutorials/Search
 */
function register_entity_type($type, $subtype) {
	global $CONFIG;

	$type = strtolower($type);
	if (!in_array($type, array('object', 'site', 'group', 'user'))) {
		return FALSE;
	}

	if (!isset($CONFIG->registered_entities)) {
		$CONFIG->registered_entities = array();
	}

	if (!isset($CONFIG->registered_entities[$type])) {
		$CONFIG->registered_entities[$type] = array();
	}

	if ($subtype) {
		$CONFIG->registered_entities[$type][] = $subtype;
	}

	return TRUE;
}

/**
 * Unregisters an entity type and subtype as a public-facing entity.
 *
 * @warning With a blank subtype, it unregisters that entity type including
 * all subtypes. This must be called after all subtypes have been registered.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return true|false Depending on success
 * @see register_entity_type()
 */
function unregister_entity_type($type, $subtype) {
	global $CONFIG;

	$type = strtolower($type);
	if (!in_array($type, array('object', 'site', 'group', 'user'))) {
		return FALSE;
	}

	if (!isset($CONFIG->registered_entities)) {
		return FALSE;
	}

	if (!isset($CONFIG->registered_entities[$type])) {
		return FALSE;
	}

	if ($subtype) {
		if (in_array($subtype, $CONFIG->registered_entities[$type])) {
			$key = array_search($subtype, $CONFIG->registered_entities[$type]);
			unset($CONFIG->registered_entities[$type][$key]);
		} else {
			return FALSE;
		}
	} else {
		unset($CONFIG->registered_entities[$type]);
	}

	return TRUE;
}

/**
 * Returns registered entity types and subtypes
 *
 * @param string $type The type of entity (object, site, user, group) or blank for all
 *
 * @return array|false Depending on whether entities have been registered
 * @see register_entity_type()
 */
function get_registered_entity_types($type = '') {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}
	if (!empty($type)) {
		$type = strtolower($type);
	}
	if (!empty($type) && empty($CONFIG->registered_entities[$type])) {
		return false;
	}

	if (empty($type)) {
		return $CONFIG->registered_entities;
	}

	return $CONFIG->registered_entities[$type];
}

/**
 * Returns if the entity type and subtype have been registered with {@see register_entity_type()}.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype (may be blank)
 *
 * @return true|false Depending on whether or not the type has been registered
 */
function is_registered_entity_type($type, $subtype) {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}
	$type = strtolower($type);
	if (empty($CONFIG->registered_entities[$type])) {
		return false;
	}
	if (in_array($subtype, $CONFIG->registered_entities[$type])) {
		return true;
	}
}

/**
 * Page handler for generic entities view system
 *
 * @param array $page Page elements from pain page handler
 *
 * @return void
 * @elgg_page_handler view
 */
function entities_page_handler($page) {
	if (isset($page[0])) {
		global $CONFIG;
		set_input('guid', $page[0]);
		include($CONFIG->path . "pages/entities/index.php");
	}
}

/**
 * Lists entities.
 *
 * @param int  $owner_guid     Owner GUID
 * @param int  $limit          Limit
 * @param bool $fullview       Show entity full views
 * @param bool $listtypetoggle Show list type toggle
 * @param bool $allowedtypes   A string of the allowed types
 *
 * @return string
 * @deprecated 1.7.  Use elgg_list_registered_entities().
 */
function list_registered_entities($owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $allowedtypes = true) {

	elgg_deprecated_notice('list_registered_entities() was deprecated by elgg_list_registered_entities().', 1.7);

	$options = array();

	// don't want to send anything if not being used.
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($allowedtypes) {
		$options['allowed_types'] = $allowedtypes;
	}

	// need to send because might be BOOL
	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;

	$options['offset'] = get_input('offset', 0);

	return elgg_list_registered_entities($options);
}

/**
 * Returns a viewable list of entities based on the registered types.
 *
 * @see elgg_view_entity_list
 *
 * @param array $options Any elgg_get_entity() options plus:
 *
 * 	full_view => BOOL Display full view entities
 *
 * 	list_type_toggle => BOOL Display gallery / list switch
 *
 * 	allowed_types => TRUE|ARRAY True to show all types or an array of valid types.
 *
 * 	pagination => BOOL Display pagination links
 *
 * @return string A viewable list of entities
 * @since 1.7.0
 */
function elgg_list_registered_entities($options) {
	$defaults = array(
		'full_view' => TRUE,
		'allowed_types' => TRUE,
		'list_type_toggle' => FALSE,
		'pagination' => TRUE,
		'offset' => 0
	);

	$options = array_merge($defaults, $options);
	//backwards compatibility
	if (isset($options['view_type_toggle'])) {
		$options['list_type_toggle'] = $options['view_type_toggle'];
	}
	
	$typearray = array();

	if ($object_types = get_registered_entity_types()) {
		foreach ($object_types as $object_type => $subtype_array) {
			if (in_array($object_type, $options['allowed_types']) || $options['allowed_types'] === TRUE) {
				$typearray[$object_type] = array();

				if (is_array($subtype_array) && count($subtype_array)) {
					foreach ($subtype_array as $subtype) {
						$typearray[$object_type][] = $subtype;
					}
				}
			}
		}
	}

	$options['type_subtype_pairs'] = $typearray;

	$count = elgg_get_entities(array_merge(array('count' => TRUE), $options));
	$entities = elgg_get_entities($options);

	return elgg_view_entity_list($entities, $count, $options['offset'],
		$options['limit'], $options['full_view'], $options['list_type_toggle'], $options['pagination']);
}

/**
 * Check the recursive delete permissions token.
 *
 * If an entity is deleted recursively, a permissions override is required to allow
 * contained or owned entities to be removed.
 *
 * @access private
 * @return bool
 * @elgg_plugin_hook_handler permissions_check all
 * @elgg_plugin_hook_handler permissions_check:metadata all
 */
function recursive_delete_permissions_check() {
	static $__RECURSIVE_DELETE_TOKEN;

	if ((isloggedin()) && ($__RECURSIVE_DELETE_TOKEN)
	&& (strcmp($__RECURSIVE_DELETE_TOKEN, md5(get_loggedin_userid())))) {
		return true;
	}

	// consult next function
	return NULL;
}

/**
 * Checks if $entity is an ElggEntity and optionally for type and subtype.
 *
 * @tip Use this function in actions and views to check that you are dealing
 * with the correct type of entity.
 *
 * @param mixed  $entity  Entity
 * @param string $type    Entity type
 * @param string $subtype Entity subtype
 * @param string $class   Class name
 *
 * @return Bool
 * @since 1.8
 */
function elgg_instanceof($entity, $type = NULL, $subtype = NULL, $class = NULL) {
	$return = ($entity instanceof ElggEntity);

	if ($type) {
		$return = $return && ($entity->getType() == $type);
	}

	if ($subtype) {
		$return = $return && ($entity->getSubtype() == $subtype);
	}

	if ($class) {
		$return = $return && ($entity instanceof $class);
	}

	return $return;
}


/**
 * Update the last_action column in the entities table for $guid.
 *
 * This determines the sort order of 1.8's default river.
 *
 * @warning This is different to time_updated.  Time_updated is automatically set,
 * while last_action is only set when explicitly called.
 *
 * @param int $guid   Entity annotation|relationship action carried out on
 * @param int $posted Timestamp of last action
 *
 * @return bool
 **/
function update_entity_last_action($guid, $posted = NULL) {
	global $CONFIG;
	$guid = (int)$guid;

	if (!$posted) {
		$posted = time();
	}

	if ($guid) {
		//now add to the river updated table
		$query = "UPDATE {$CONFIG->dbprefix}entities SET last_action = {$posted} WHERE guid = {$guid}";
		$result = update_data($query);
		if ($result) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

/**
 * Garbage collect stub and fragments from any broken delete/create calls
 *
 * @return void
 * @elgg_plugin_hook_handler gc system
 */
function entities_gc() {
	global $CONFIG;

	$tables = array ('sites_entity', 'objects_entity', 'groups_entity', 'users_entity');

	foreach ($tables as $table) {
		delete_data("DELETE from {$CONFIG->dbprefix}{$table}
			where guid NOT IN (SELECT guid from {$CONFIG->dbprefix}entities)");
	}
}

/**
 * Runs unit tests for the entity objects.
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function entities_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/objects/entities.php';
	return $value;
}

/**
 * Entities init function; establishes the default entity page handler
 *
 * @return void
 * @elgg_event_handler init system
 */
function entities_init() {
	register_page_handler('view', 'entities_page_handler');

	elgg_register_plugin_hook_handler('unit_test', 'system', 'entities_test');

	// Allow a permission override for recursive entity deletion
	// @todo Can this be done better?
	elgg_register_plugin_hook_handler('permissions_check', 'all', 'recursive_delete_permissions_check');
	elgg_register_plugin_hook_handler('permissions_check:metadata', 'all', 'recursive_delete_permissions_check');

	elgg_register_plugin_hook_handler('gc', 'system', 'entities_gc');
}

/** Register the import hook */
elgg_register_plugin_hook_handler("import", "all", "import_entity_plugin_hook", 0);

/** Register the hook, ensuring entities are serialised first */
elgg_register_plugin_hook_handler("export", "all", "export_entity_plugin_hook", 0);

/** Hook to get certain named bits of volatile data about an entity */
elgg_register_plugin_hook_handler('volatile', 'metadata', 'volatile_data_export_plugin_hook');

/** Hook for rendering a default icon for entities */
elgg_register_plugin_hook_handler('entity:icon:url', 'all', 'default_entity_icon_hook', 1000);

/** Register init system event **/
elgg_register_event_handler('init', 'system', 'entities_init');
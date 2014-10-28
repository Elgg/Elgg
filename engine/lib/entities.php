<?php
/**
 * Procedural code for creating, loading, and modifying ElggEntity objects.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Entities
 */

/**
 * Cache entities in memory once loaded.
 *
 * @global ElggEntity[] $ENTITY_CACHE
 * @access private
 */
global $ENTITY_CACHE;
$ENTITY_CACHE = array();

/**
 * GUIDs of entities banned from the entity cache (during this request)
 *
 * @global array $ENTITY_CACHE_DISABLED_GUIDS
 * @access private
 */
global $ENTITY_CACHE_DISABLED_GUIDS;
$ENTITY_CACHE_DISABLED_GUIDS = array();

/**
 * Cache subtypes and related class names.
 *
 * @global array|null $SUBTYPE_CACHE array once populated from DB, initially null
 * @access private
 */
global $SUBTYPE_CACHE;
$SUBTYPE_CACHE = null;

/**
 * Remove this entity from the entity cache and make sure it is not re-added
 *
 * @param int $guid The entity guid
 *
 * @access private
 * @todo this is a workaround until #5604 can be implemented
 */
function _elgg_disable_caching_for_entity($guid) {
	global $ENTITY_CACHE_DISABLED_GUIDS;

	_elgg_invalidate_cache_for_entity($guid);
	$ENTITY_CACHE_DISABLED_GUIDS[$guid] = true;
}

/**
 * Allow this entity to be stored in the entity cache
 *
 * @param int $guid The entity guid
 *
 * @access private
 */
function _elgg_enable_caching_for_entity($guid) {
	global $ENTITY_CACHE_DISABLED_GUIDS;

	unset($ENTITY_CACHE_DISABLED_GUIDS[$guid]);
}

/**
 * Invalidate this class's entry in the cache.
 *
 * @param int $guid The entity guid
 *
 * @return void
 * @access private
 */
function _elgg_invalidate_cache_for_entity($guid) {
	global $ENTITY_CACHE;

	$guid = (int)$guid;

	if (isset($ENTITY_CACHE[$guid])) {
		unset($ENTITY_CACHE[$guid]);

		// Purge separate metadata cache. Original idea was to do in entity destructor, but that would
		// have caused a bunch of unnecessary purges at every shutdown. Doing it this way we have no way
		// to know that the expunged entity will be GCed (might be another reference living), but that's
		// OK; the metadata will reload if necessary.
		_elgg_get_metadata_cache()->clear($guid);
	}
}

/**
 * Cache an entity.
 *
 * Stores an entity in $ENTITY_CACHE;
 *
 * @param ElggEntity $entity Entity to cache
 *
 * @return void
 * @see _elgg_retrieve_cached_entity()
 * @see _elgg_invalidate_cache_for_entity()
 * @access private
 * @todo Use an ElggCache object
 */
function _elgg_cache_entity(ElggEntity $entity) {
	global $ENTITY_CACHE, $ENTITY_CACHE_DISABLED_GUIDS;

	// Don't cache non-plugin entities while access control is off, otherwise they could be
	// exposed to users who shouldn't see them when control is re-enabled.
	if (!($entity instanceof ElggPlugin) && elgg_get_ignore_access()) {
		return;
	}

	$guid = $entity->getGUID();
	if (isset($ENTITY_CACHE_DISABLED_GUIDS[$guid])) {
		return;
	}

	// Don't store too many or we'll have memory problems
	// @todo Pick a less arbitrary limit
	if (count($ENTITY_CACHE) > 256) {
		_elgg_invalidate_cache_for_entity(array_rand($ENTITY_CACHE));
	}

	$ENTITY_CACHE[$guid] = $entity;
}

/**
 * Retrieve a entity from the cache.
 *
 * @param int $guid The guid
 *
 * @return ElggEntity|bool false if entity not cached, or not fully loaded
 * @see _elgg_cache_entity()
 * @see _elgg_invalidate_cache_for_entity()
 * @access private
 */
function _elgg_retrieve_cached_entity($guid) {
	global $ENTITY_CACHE;

	if (isset($ENTITY_CACHE[$guid])) {
		if ($ENTITY_CACHE[$guid]->isFullyLoaded()) {
			return $ENTITY_CACHE[$guid];
		}
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
 * @internal Subtypes are stored in the entity_subtypes table.  There is a foreign
 * key in the entities table.
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 *
 * @return int Subtype ID
 * @see get_subtype_from_id()
 * @access private
 */
function get_subtype_id($type, $subtype) {
	global $SUBTYPE_CACHE;

	if (!$subtype) {
		return false;
	}

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	// use the cache before hitting database
	$result = _elgg_retrieve_cached_subtype($type, $subtype);
	if ($result !== null) {
		return $result->id;
	}

	return false;
}

/**
 * Gets the denormalized string for a given subtype ID.
 *
 * @param int $subtype_id Subtype ID from database
 * @return string|false Subtype name, false if subtype not found
 * @see get_subtype_id()
 * @access private
 */
function get_subtype_from_id($subtype_id) {
	global $SUBTYPE_CACHE;

	if (!$subtype_id) {
		return '';
	}

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->subtype;
	}

	return false;
}

/**
 * Retrieve subtype from the cache.
 *
 * @param string $type
 * @param string $subtype
 * @return stdClass|null
 *
 * @access private
 */
function _elgg_retrieve_cached_subtype($type, $subtype) {
	global $SUBTYPE_CACHE;

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	foreach ($SUBTYPE_CACHE as $obj) {
		if ($obj->type === $type && $obj->subtype === $subtype) {
			return $obj;
		}
	}
	return null;
}

/**
 * Fetch all suptypes from DB to local cache.
 *
 * @access private
 */
function _elgg_populate_subtype_cache() {
	global $CONFIG, $SUBTYPE_CACHE;

	$results = get_data("SELECT * FROM {$CONFIG->dbprefix}entity_subtypes");

	$SUBTYPE_CACHE = array();
	foreach ($results as $row) {
		$SUBTYPE_CACHE[$row->id] = $row;
	}
}

/**
 * Return the class name for a registered type and subtype.
 *
 * Entities can be registered to always be loaded as a certain class
 * with add_subtype() or update_subtype(). This function returns the class
 * name if found and null if not.
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
	global $SUBTYPE_CACHE;

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	// use the cache before going to the database
	$obj = _elgg_retrieve_cached_subtype($type, $subtype);
	if ($obj) {
		return $obj->class;
	}

	return null;
}

/**
 * Returns the class name for a subtype id.
 *
 * @param int $subtype_id The subtype id
 *
 * @return string|null
 * @see get_subtype_class()
 * @see get_subtype_from_id()
 * @access private
 */
function get_subtype_class_from_id($subtype_id) {
	global $SUBTYPE_CACHE;

	if (!$subtype_id) {
		return null;
	}

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	if (isset($SUBTYPE_CACHE[$subtype_id])) {
		return $SUBTYPE_CACHE[$subtype_id]->class;
	}

	return null;
}

/**
 * Register ElggEntities with a certain type and subtype to be loaded as a specific class.
 *
 * By default entities are loaded as one of the 4 parent objects: site, user, object, or group.
 * If you subclass any of these you can register the classname with add_subtype() so
 * it will be loaded as that class automatically when retrieved from the database with
 * {@link get_entity()}.
 *
 * @warning This function cannot be used to change the class for a type-subtype pair.
 * Use update_subtype() for that.
 *
 * @param string $type    The type you're subtyping (site, user, object, or group)
 * @param string $subtype The subtype
 * @param string $class   Optional class name for the object
 *
 * @return int
 * @see update_subtype()
 * @see remove_subtype()
 * @see get_entity()
 */
function add_subtype($type, $subtype, $class = "") {
	global $CONFIG, $SUBTYPE_CACHE;

	if (!$subtype) {
		return 0;
	}

	$id = get_subtype_id($type, $subtype);

	if (!$id) {
		// In cache we store non-SQL-escaped strings because that's what's returned by query
		$cache_obj = (object) array(
			'type' => $type,
			'subtype' => $subtype,
			'class' => $class,
		);

		$type = sanitise_string($type);
		$subtype = sanitise_string($subtype);
		$class = sanitise_string($class);

		$id = insert_data("INSERT INTO {$CONFIG->dbprefix}entity_subtypes"
			. " (type, subtype, class) VALUES ('$type', '$subtype', '$class')");

		// add entry to cache
		$cache_obj->id = $id;
		$SUBTYPE_CACHE[$id] = $cache_obj;
	}

	return $id;
}

/**
 * Removes a registered ElggEntity type, subtype, and classname.
 *
 * @warning You do not want to use this function. If you want to unregister
 * a class for a subtype, use update_subtype(). Using this function will
 * permanently orphan all the objects created with the specified subtype.
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 *
 * @return bool
 * @see add_subtype()
 * @see update_subtype()
 */
function remove_subtype($type, $subtype) {
	global $CONFIG, $SUBTYPE_CACHE;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);

	$success = delete_data("DELETE FROM {$CONFIG->dbprefix}entity_subtypes"
		. " WHERE type = '$type' AND subtype = '$subtype'");

	if ($success) {
		// invalidate the cache
		$SUBTYPE_CACHE = null;
	}

	return (bool) $success;
}

/**
 * Update a registered ElggEntity type, subtype, and class name
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 * @param string $class   Class name to use when loading this entity
 *
 * @return bool
 */
function update_subtype($type, $subtype, $class = '') {
	global $CONFIG, $SUBTYPE_CACHE;

	$id = get_subtype_id($type, $subtype);
	if (!$id) {
		return false;
	}

	if ($SUBTYPE_CACHE === null) {
		_elgg_populate_subtype_cache();
	}

	$unescaped_class = $class;

	$type = sanitise_string($type);
	$subtype = sanitise_string($subtype);
	$class = sanitise_string($class);

	$success = update_data("UPDATE {$CONFIG->dbprefix}entity_subtypes
		SET type = '$type', subtype = '$subtype', class = '$class'
		WHERE id = $id
	");

	if ($success && isset($SUBTYPE_CACHE[$id])) {
		$SUBTYPE_CACHE[$id]->class = $unescaped_class;
	}

	return $success;
}

/**
 * Determine if a given user can write to an entity container.
 *
 * An entity can be a container for any other entity by setting the
 * container_guid.  container_guid can differ from owner_guid.
 *
 * A plugin hook container_permissions_check:$entity_type is emitted to allow granular
 * access controls in plugins.
 *
 * @param int    $user_guid      The user guid, or 0 for logged in user.
 * @param int    $container_guid The container, or 0 for the current page owner.
 * @param string $type           The type of entity we want to create (default: 'all')
 * @param string $subtype        The subtype of the entity we want to create (default: 'all')
 *
 * @return bool
 */
function can_write_to_container($user_guid = 0, $container_guid = 0, $type = 'all', $subtype = 'all') {
	$container_guid = (int)$container_guid;
	if (!$container_guid) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$container = get_entity($container_guid);

	$user_guid = (int)$user_guid;
	if ($user_guid == 0) {
		$user = elgg_get_logged_in_user_entity();
		$user_guid = elgg_get_logged_in_user_guid();
	} else {
		$user = get_user($user_guid);
		if (!$user) {
			return false;
		}
	}

	$return = false;
	if ($container) {
		// If the user can edit the container, they can also write to it
		if ($container->canEdit($user_guid)) {
			$return = true;
		}
	}

	// See if anyone else has anything to say
	return elgg_trigger_plugin_hook(
			'container_permissions_check',
			$type,
			array(
				'container' => $container,
				'user' => $user,
				'subtype' => $subtype
			),
			$return);
}

/**
 * Returns a database row from the entities table.
 *
 * @tip Use get_entity() to return the fully loaded entity.
 *
 * @warning This will only return results if a) it exists, b) you have access to it.
 * see {@link _elgg_get_access_where_sql()}.
 *
 * @param int $guid The GUID of the object to extract
 *
 * @return stdClass|false
 * @see entity_row_to_elggstar()
 * @access private
 */
function get_entity_as_row($guid) {
	global $CONFIG;

	if (!$guid) {
		return false;
	}

	$guid = (int) $guid;
	$access = _elgg_get_access_where_sql(array('table_alias' => ''));

	return get_data_row("SELECT * from {$CONFIG->dbprefix}entities where guid=$guid and $access");
}

/**
 * Create an Elgg* object from a given entity row.
 *
 * Handles loading all tables into the correct class.
 *
 * @param stdClass $row The row of the entry in the entities table.
 *
 * @return ElggEntity|false
 * @see get_entity_as_row()
 * @see add_subtype()
 * @see get_entity()
 * @access private
 *
 * @throws ClassException|InstallationException
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
				$msg = $classname . " is not a " . 'ElggEntity' . ".";
				throw new ClassException($msg);
			}
		} else {
			error_log("Class '" . $classname . "' was not found, missing plugin?");
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
				$msg = "Entity type " . $row->type . " is not supported.";
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
 */
function get_entity($guid) {
	// This should not be a static local var. Notice that cache writing occurs in a completely
	// different instance outside this function.
	// @todo We need a single Memcache instance with a shared pool of namespace wrappers. This function would pull an instance from the pool.
	static $shared_cache;

	// We could also use: if (!(int) $guid) { return false },
	// but that evaluates to a false positive for $guid = true.
	// This is a bit slower, but more thorough.
	if (!is_numeric($guid) || $guid === 0 || $guid === '0') {
		return false;
	}

	// Check local cache first
	$new_entity = _elgg_retrieve_cached_entity($guid);
	if ($new_entity) {
		return $new_entity;
	}

	// Check shared memory cache, if available
	if (null === $shared_cache) {
		if (is_memcache_available()) {
			$shared_cache = new ElggMemcache('new_entity_cache');
		} else {
			$shared_cache = false;
		}
	}

	// until ACLs in memcache, DB query is required to determine access
	$entity_row = get_entity_as_row($guid);
	if (!$entity_row) {
		return false;
	}

	if ($shared_cache) {
		$cached_entity = $shared_cache->load($guid);
		// @todo store ACLs in memcache https://github.com/elgg/elgg/issues/3018#issuecomment-13662617
		if ($cached_entity) {
			// @todo use ACL and cached entity access_id to determine if user can see it
			return $cached_entity;
		}
	}

	// don't let incomplete entities cause fatal exceptions
	try {
		$new_entity = entity_row_to_elggstar($entity_row);
	} catch (IncompleteEntityException $e) {
		return false;
	}

	if ($new_entity) {
		_elgg_cache_entity($new_entity);
	}
	return $new_entity;
}

/**
 * Does an entity exist?
 *
 * This function checks for the existence of an entity independent of access
 * permissions. It is useful for situations when a user cannot access an entity
 * and it must be determined whether entity has been deleted or the access level
 * has changed.
 *
 * @param int $guid The GUID of the entity
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_entity_exists($guid) {
	global $CONFIG;

	$guid = sanitize_int($guid);

	$query = "SELECT count(*) as total FROM {$CONFIG->dbprefix}entities WHERE guid = $guid";
	$result = get_data_row($query);
	if ($result->total == 0) {
		return false;
	} else {
		return true;
	}
}

/**
 * Enable an entity.
 *
 * @param int  $guid      GUID of entity to enable
 * @param bool $recursive Recursively enable all entities disabled with the entity?
 *
 * @return bool
 * @since 1.9.0
 */
function elgg_enable_entity($guid, $recursive = true) {

	// Override access only visible entities
	$old_access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	$result = false;
	$entity = get_entity($guid);
	if ($entity) {
		$result = $entity->enable($recursive);
	}

	access_show_hidden_entities($old_access_status);
	return $result;
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
 * 	types => null|STR entity type (type IN ('type1', 'type2')
 *           Joined with subtypes by AND. See below)
 *
 * 	subtypes => null|STR entity subtype (SQL: subtype IN ('subtype1', 'subtype2))
 *              Use ELGG_ENTITIES_NO_VALUE for no subtype.
 *
 * 	type_subtype_pairs => null|ARR (array('type' => 'subtype'))
 *                        (type = '$type' AND subtype = '$subtype') pairs
 *
 *	guids => null|ARR Array of entity guids
 *
 * 	owner_guids => null|ARR Array of owner guids
 *
 * 	container_guids => null|ARR Array of container_guids
 *
 * 	site_guids => null (current_site)|ARR Array of site_guid
 *
 * 	order_by => null (time_created desc)|STR SQL order by clause
 *
 *  reverse_order_by => BOOL Reverse the default order by clause
 *
 * 	limit => null (10)|INT SQL limit clause (0 means no limit)
 *
 * 	offset => null (0)|INT SQL offset clause
 *
 * 	created_time_lower => null|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => null|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => null|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => null|INT Modified time upper boundary in epoch time
 *
 * 	count => true|false return a count instead of entities
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *
 * 	callback => string A callback function to pass each row through
 *
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 * @see elgg_get_entities_from_metadata()
 * @see elgg_get_entities_from_relationship()
 * @see elgg_get_entities_from_access_id()
 * @see elgg_get_entities_from_annotations()
 * @see elgg_list_entities()
 */
function elgg_get_entities(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'guids'					=>	ELGG_ENTITIES_ANY_VALUE,
		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	$CONFIG->site_guid,

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		'reverse_order_by'		=>	false,
		'order_by' 				=>	'e.time_created desc',
		'group_by'				=>	ELGG_ENTITIES_ANY_VALUE,
		'limit'					=>	10,
		'offset'				=>	0,
		'count'					=>	false,
		'selects'				=>	array(),
		'wheres'				=>	array(),
		'joins'					=>	array(),

		'callback'				=> 'entity_row_to_elggstar',

		'__ElggBatch'			=> null,
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

	$singulars = array('type', 'subtype', 'guid', 'owner_guid', 'container_guid', 'site_guid');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	}

	$wheres = $options['wheres'];

	$wheres[] = _elgg_get_entity_type_subtype_where_sql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	$wheres[] = _elgg_get_guid_based_where_sql('e.guid', $options['guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.owner_guid', $options['owner_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.container_guid', $options['container_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.site_guid', $options['site_guids']);

	$wheres[] = _elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === false) {
			return false;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	}

	// remove identical join clauses
	$joins = array_unique($options['joins']);

	foreach ($joins as $i => $join) {
		if ($join === false) {
			return false;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}

	// evalutate selects
	if ($options['selects']) {
		$selects = '';
		foreach ($options['selects'] as $select) {
			$selects .= ", $select";
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
	$query .= _elgg_get_access_where_sql();

	// reverse order by
	if ($options['reverse_order_by']) {
		$options['order_by'] = _elgg_sql_reverse_order_by_clause($options['order_by']);
	}

	if (!$options['count']) {
		if ($options['group_by']) {
			$query .= " GROUP BY {$options['group_by']}";
		}

		if ($options['order_by']) {
			$query .= " ORDER BY {$options['order_by']}";
		}

		if ($options['limit']) {
			$limit = sanitise_int($options['limit'], false);
			$offset = sanitise_int($options['offset'], false);
			$query .= " LIMIT $offset, $limit";
		}

		if ($options['callback'] === 'entity_row_to_elggstar') {
			$dt = _elgg_fetch_entities_from_sql($query, $options['__ElggBatch']);
		} else {
			$dt = get_data($query, $options['callback']);
		}

		if ($dt) {
			// populate entity and metadata caches
			$guids = array();
			foreach ($dt as $item) {
				// A custom callback could result in items that aren't ElggEntity's, so check for them
				if ($item instanceof ElggEntity) {
					_elgg_cache_entity($item);
					// plugins usually have only settings
					if (!$item instanceof ElggPlugin) {
						$guids[] = $item->guid;
					}
				}
			}
			// @todo Without this, recursive delete fails. See #4568
			reset($dt);

			if ($guids) {
				_elgg_get_metadata_cache()->populateFromEntities($guids);
			}
		}
		return $dt;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * Return entities from an SQL query generated by elgg_get_entities.
 *
 * @param string    $sql
 * @param ElggBatch $batch
 * @return ElggEntity[]
 *
 * @access private
 * @throws LogicException
 */
function _elgg_fetch_entities_from_sql($sql, ElggBatch $batch = null) {
	static $plugin_subtype;
	if (null === $plugin_subtype) {
		$plugin_subtype = get_subtype_id('object', 'plugin');
	}

	// Keys are types, values are columns that, if present, suggest that the secondary
	// table is already JOINed
	$types_to_optimize = array(
		'object' => 'title',
		'user' => 'password',
		'group' => 'name',
	);

	$rows = get_data($sql);

	// guids to look up in each type
	$lookup_types = array();
	// maps GUIDs to the $rows key
	$guid_to_key = array();

	if (isset($rows[0]->type, $rows[0]->subtype)
			&& $rows[0]->type === 'object'
			&& $rows[0]->subtype == $plugin_subtype) {
		// Likely the entire resultset is plugins, which have already been optimized
		// to JOIN the secondary table. In this case we allow retrieving from cache,
		// but abandon the extra queries.
		$types_to_optimize = array();
	}

	// First pass: use cache where possible, gather GUIDs that we're optimizing
	foreach ($rows as $i => $row) {
		if (empty($row->guid) || empty($row->type)) {
			throw new LogicException('Entity row missing guid or type');
		}
		$entity = _elgg_retrieve_cached_entity($row->guid);
		if ($entity) {
			$entity->refresh($row);
			$rows[$i] = $entity;
			continue;
		}
		if (isset($types_to_optimize[$row->type])) {
			// check if row already looks JOINed.
			if (isset($row->{$types_to_optimize[$row->type]})) {
				// Row probably already contains JOINed secondary table. Don't make another query just
				// to pull data that's already there
				continue;
			}
			$lookup_types[$row->type][] = $row->guid;
			$guid_to_key[$row->guid] = $i;
		}
	}
	// Do secondary queries and merge rows
	if ($lookup_types) {
		$dbprefix = elgg_get_config('dbprefix');

		foreach ($lookup_types as $type => $guids) {
			$set = "(" . implode(',', $guids) . ")";
			$sql = "SELECT * FROM {$dbprefix}{$type}s_entity WHERE guid IN $set";
			$secondary_rows = get_data($sql);
			if ($secondary_rows) {
				foreach ($secondary_rows as $secondary_row) {
					$key = $guid_to_key[$secondary_row->guid];
					// cast to arrays to merge then cast back
					$rows[$key] = (object)array_merge((array)$rows[$key], (array)$secondary_row);
				}
			}
		}
	}
	// Second pass to finish conversion
	foreach ($rows as $i => $row) {
		if ($row instanceof ElggEntity) {
			continue;
		} else {
			try {
				$rows[$i] = entity_row_to_elggstar($row);
			} catch (IncompleteEntityException $e) {
				// don't let incomplete entities throw fatal errors
				unset($rows[$i]);

				// report incompletes to the batch process that spawned this query
				if ($batch) {
					$batch->reportIncompleteEntity($row);
				}
			}
		}
	}
	return $rows;
}

/**
 * Returns SQL where clause for type and subtype on main entity table
 *
 * @param string     $table    Entity table prefix as defined in SELECT...FROM entities $table
 * @param null|array $types    Array of types or null if none.
 * @param null|array $subtypes Array of subtypes or null if none
 * @param null|array $pairs    Array of pairs of types and subtypes
 *
 * @return false|string
 * @since 1.7.0
 * @access private
 */
function _elgg_get_entity_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// subtype depends upon type.
	if ($subtypes && !$types) {
		elgg_log("Cannot set subtypes without type.", 'WARNING');
		return false;
	}

	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	// these are the only valid types for entities in elgg
	$valid_types = elgg_get_config('entity_types');

	// pairs override
	$wheres = array();
	if (!is_array($pairs)) {
		if (!is_array($types)) {
			$types = array($types);
		}

		if ($subtypes && !is_array($subtypes)) {
			$subtypes = array($subtypes);
		}

		// decrementer for valid types.  Return false if no valid types
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
				unset($types[array_search($type, $types)]);
			} else {
				// do the checking (and decrementing) in the subtype section.
				$valid_subtypes_count += count($subtypes);
			}
		}

		// return false if nothing is valid.
		if (!$valid_types_count) {
			return false;
		}

		// subtypes are based upon types, so we need to look at each
		// type individually to get the right subtype id.
		foreach ($types as $type) {
			$subtype_ids = array();
			if ($subtypes) {
				foreach ($subtypes as $subtype) {
					// check that the subtype is valid
					if (!$subtype && ELGG_ENTITIES_NO_VALUE === $subtype) {
						// subtype value is 0
						$subtype_ids[] = ELGG_ENTITIES_NO_VALUE;
					} elseif (!$subtype) {
						// subtype is ignored.
						// this handles ELGG_ENTITIES_ANY_VALUE, '', and anything falsy that isn't 0
						continue;
					} else {
						$subtype_id = get_subtype_id($type, $subtype);

						if ($subtype_id) {
							$subtype_ids[] = $subtype_id;
						} else {
							$valid_subtypes_count--;
							elgg_log("Type-subtype '$type:$subtype' does not exist!", 'NOTICE');
							continue;
						}
					}
				}

				// return false if we're all invalid subtypes in the only valid type
				if ($valid_subtypes_count <= 0) {
					return false;
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
				unset($pairs[array_search($paired_type, $pairs)]);
			} else {
				if ($paired_subtypes && !is_array($paired_subtypes)) {
					$pairs[$paired_type] = array($paired_subtypes);
				}
				$valid_pairs_subtypes_count += count($paired_subtypes);
			}
		}

		if ($valid_pairs_count <= 0) {
			return false;
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
						elgg_log("Type-subtype '$paired_type:$paired_subtype' does not exist!", 'NOTICE');
						// return false if we're all invalid subtypes in the only valid type
						continue;
					}
				}

				// return false if there are no valid subtypes.
				if ($valid_pairs_subtypes_count <= 0) {
					return false;
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
 * @param string     $column Column name the guids should be checked against. Usually
 *                           best to provide in table.column format.
 * @param null|array $guids  Array of GUIDs.
 *
 * @return false|string
 * @since 1.8.0
 * @access private
 */
function _elgg_get_guid_based_where_sql($column, $guids) {
	// short circuit if nothing requested
	// 0 is a valid guid
	if (!$guids && $guids !== 0) {
		return '';
	}

	// normalize and sanitise owners
	if (!is_array($guids)) {
		$guids = array($guids);
	}

	$guids_sanitized = array();
	foreach ($guids as $guid) {
		if ($guid !== ELGG_ENTITIES_NO_VALUE) {
			$guid = sanitise_int($guid);

			if (!$guid) {
				return false;
			}
		}
		$guids_sanitized[] = $guid;
	}

	$where = '';
	$guid_str = implode(',', $guids_sanitized);

	// implode(',', 0) returns 0.
	if ($guid_str !== false && $guid_str !== '') {
		$where = "($column IN ($guid_str))";
	}

	return $where;
}

/**
 * Returns SQL where clause for entity time limits.
 *
 * @param string   $table              Entity table prefix as defined in
 *                                     SELECT...FROM entities $table
 * @param null|int $time_created_upper Time created upper limit
 * @param null|int $time_created_lower Time created lower limit
 * @param null|int $time_updated_upper Time updated upper limit
 * @param null|int $time_updated_lower Time updated lower limit
 *
 * @return false|string false on fail, string on success.
 * @since 1.7.0
 * @access private
 */
function _elgg_get_entity_time_where_sql($table, $time_created_upper = null,
$time_created_lower = null, $time_updated_upper = null, $time_updated_lower = null) {

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
 * Returns a string of rendered entities.
 *
 * Displays list of entities with formatting specified by the entity view.
 *
 * @tip Pagination is handled automatically.
 *
 * @internal This also provides the views for elgg_view_annotation().
 *
 * @internal If the initial COUNT query returns 0, the $getter will not be called again.
 *
 * @param array    $options Any options from $getter options plus:
 *                   full_view => BOOL Display full view of entities (default: false)
 *                   list_type => STR 'list' or 'gallery'
 *                   list_type_toggle => BOOL Display gallery / list switch
 *                   pagination => BOOL Display pagination links
 *                   no_results => STR Message to display when there are no entities
 *
 * @param callback $getter  The entity getter function to use to fetch the entities.
 * @param callback $viewer  The function to use to view the entity list.
 *
 * @return string
 * @since 1.7
 * @see elgg_get_entities()
 * @see elgg_view_entity_list()
 */
function elgg_list_entities(array $options = array(), $getter = 'elgg_get_entities',
	$viewer = 'elgg_view_entity_list') {

	global $autofeed;
	$autofeed = true;

	$offset_key = isset($options['offset_key']) ? $options['offset_key'] : 'offset';

	$defaults = array(
		'offset' => (int) max(get_input($offset_key, 0), 0),
		'limit' => (int) max(get_input('limit', 10), 0),
		'full_view' => false,
		'list_type_toggle' => false,
		'pagination' => true,
		'no_results' => '',
	);

	$options = array_merge($defaults, $options);

	// backward compatibility
	if (isset($options['view_type_toggle'])) {
		elgg_deprecated_notice("Option 'view_type_toggle' deprecated by 'list_type_toggle' in elgg_list* functions", 1.9);
		$options['list_type_toggle'] = $options['view_type_toggle'];
	}

	$options['count'] = true;
	$count = call_user_func($getter, $options);

	if ($count > 0) {
		$options['count'] = false;
		$entities = call_user_func($getter, $options);
	} else {
		$entities = array();
	}

	$options['count'] = $count;

	return call_user_func($viewer, $entities, $options);
}

/**
 * Gets entities based upon attributes in secondary tables.
 * Also accepts all options available to elgg_get_entities(),
 * elgg_get_entities_from_metadata(), and elgg_get_entities_from_relationship().
 *
 * @warning requires that the entity type be specified and there can only be one
 * type.
 *
 * @see elgg_get_entities
 * @see elgg_get_entities_from_metadata
 * @see elgg_get_entities_from_relationship
 *
 * @param array $options Array in format:
 *
 * 	attribute_name_value_pairs => ARR (
 *                                   'name' => 'name',
 *                                   'value' => 'value',
 *                                   'operand' => '=', (optional)
 *                                   'case_sensitive' => false (optional)
 *                                  )
 * 	                             If multiple values are sent via
 *                               an array ('value' => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *
 * 	attribute_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default is AND
 *
 * @return ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.9.0
 * @throws InvalidArgumentException
 * @todo Does not support ordering by attributes or using an attribute pair shortcut like this ('title' => 'foo')
 */
function elgg_get_entities_from_attributes(array $options = array()) {
	$defaults = array(
		'attribute_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,
		'attribute_name_value_pairs_operator' => 'AND',
	);

	$options = array_merge($defaults, $options);

	$singulars = array('type', 'attribute_name_value_pair');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$clauses = _elgg_get_entity_attribute_where_sql($options);

	if ($clauses) {
		// merge wheres to pass to elgg_get_entities()
		if (isset($options['wheres']) && !is_array($options['wheres'])) {
			$options['wheres'] = array($options['wheres']);
		} elseif (!isset($options['wheres'])) {
			$options['wheres'] = array();
		}

		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);

		// merge joins to pass to elgg_get_entities()
		if (isset($options['joins']) && !is_array($options['joins'])) {
			$options['joins'] = array($options['joins']);
		} elseif (!isset($options['joins'])) {
			$options['joins'] = array();
		}

		$options['joins'] = array_merge($options['joins'], $clauses['joins']);
	}

	return elgg_get_entities_from_relationship($options);
}

/**
 * Get the join and where clauses for working with entity attributes
 *
 * @return false|array False on fail, array('joins', 'wheres')
 * @since 1.9.0
 * @access private
 * @throws InvalidArgumentException
 */
function _elgg_get_entity_attribute_where_sql(array $options = array()) {

	if (!isset($options['types'])) {
		throw new InvalidArgumentException("The entity type must be defined for elgg_get_entities_from_attributes()");
	}

	if (is_array($options['types']) && count($options['types']) !== 1) {
		throw new InvalidArgumentException("Only one type can be passed to elgg_get_entities_from_attributes()");
	}

	// type can be passed as string or array
	$type = $options['types'];
	if (is_array($type)) {
		$type = $type[0];
	}

	// @todo the types should be defined somewhere (as constant on ElggEntity?)
	if (!in_array($type, array('group', 'object', 'site', 'user'))) {
		throw new InvalidArgumentException("Invalid type '$type' passed to elgg_get_entities_from_attributes()");
	}

	global $CONFIG;
	$type_table = "{$CONFIG->dbprefix}{$type}s_entity";

	$return = array(
		'joins' => array(),
		'wheres' => array(),
	);

	// short circuit if nothing requested
	if ($options['attribute_name_value_pairs'] == ELGG_ENTITIES_ANY_VALUE) {
		return $return;
	}

	if (!is_array($options['attribute_name_value_pairs'])) {
		throw new InvalidArgumentException("attribute_name_value_pairs must be an array for elgg_get_entities_from_attributes()");
	}

	$wheres = array();

	// check if this is an array of pairs or just a single pair.
	$pairs = $options['attribute_name_value_pairs'];
	if (isset($pairs['name']) || isset($pairs['value'])) {
		$pairs = array($pairs);
	}

	$pair_wheres = array();
	foreach ($pairs as $index => $pair) {
		// must have at least a name and value
		if (!isset($pair['name']) || !isset($pair['value'])) {
			continue;
		}

		if (isset($pair['operand'])) {
			$operand = sanitize_string($pair['operand']);
		} else {
			$operand = '=';
		}

		if (is_numeric($pair['value'])) {
			$value = sanitize_string($pair['value']);
		} else if (is_array($pair['value'])) {
			$values_array = array();
			foreach ($pair['value'] as $pair_value) {
				if (is_numeric($pair_value)) {
					$values_array[] = sanitize_string($pair_value);
				} else {
					$values_array[] = "'" . sanitize_string($pair_value) . "'";
				}
			}

			$operand = 'IN';
			if ($values_array) {
				$value = '(' . implode(', ', $values_array) . ')';
			}

		} else {
			$value = "'" . sanitize_string($pair['value']) . "'";
		}

		$name = sanitize_string($pair['name']);

		// case sensitivity can be specified per pair
		$pair_binary = '';
		if (isset($pair['case_sensitive'])) {
			$pair_binary = ($pair['case_sensitive']) ? 'BINARY ' : '';
		}

		$pair_wheres[] = "({$pair_binary}type_table.$name $operand $value)";
	}

	if ($where = implode(" {$options['attribute_name_value_pairs_operator']} ", $pair_wheres)) {
		$return['wheres'][] = "($where)";

		$return['joins'][] = "JOIN $type_table type_table ON e.guid = type_table.guid";
	}

	return $return;
}

/**
 * Returns a list of months in which entities were updated or created.
 *
 * @tip Use this to generate a list of archives by month for when entities were added or updated.
 *
 * @todo document how to pass in array for $subtype
 *
 * @warning Months are returned in the form YYYYMM.
 *
 * @param string $type           The type of entity
 * @param string $subtype        The subtype of entity
 * @param int    $container_guid The container GUID that the entities belong to
 * @param int    $site_guid      The site GUID
 * @param string $order_by       Order_by SQL order by clause
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
				return false;
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

	$where[] = _elgg_get_access_where_sql(array('table_alias' => ''));

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
 * @return bool Depending on success
 * @see get_registered_entity_types()
 */
function elgg_register_entity_type($type, $subtype = null) {
	global $CONFIG;

	$type = strtolower($type);
	if (!in_array($type, $CONFIG->entity_types)) {
		return false;
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

	return true;
}

/**
 * Unregisters an entity type and subtype as a public-facing type.
 *
 * @warning With a blank subtype, it unregisters that entity type including
 * all subtypes. This must be called after all subtypes have been registered.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return bool Depending on success
 * @see elgg_register_entity_type()
 */
function elgg_unregister_entity_type($type, $subtype = null) {
	global $CONFIG;

	$type = strtolower($type);
	if (!in_array($type, $CONFIG->entity_types)) {
		return false;
	}

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}

	if (!isset($CONFIG->registered_entities[$type])) {
		return false;
	}

	if ($subtype) {
		if (in_array($subtype, $CONFIG->registered_entities[$type])) {
			$key = array_search($subtype, $CONFIG->registered_entities[$type]);
			unset($CONFIG->registered_entities[$type][$key]);
		} else {
			return false;
		}
	} else {
		unset($CONFIG->registered_entities[$type]);
	}

	return true;
}

/**
 * Returns registered entity types and subtypes
 *
 * @param string $type The type of entity (object, site, user, group) or blank for all
 *
 * @return array|false Depending on whether entities have been registered
 * @see elgg_register_entity_type()
 */
function get_registered_entity_types($type = null) {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}
	if ($type) {
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
 * Returns if the entity type and subtype have been registered with {@link elgg_register_entity_type()}.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype (may be blank)
 *
 * @return bool Depending on whether or not the type has been registered
 */
function is_registered_entity_type($type, $subtype = null) {
	global $CONFIG;

	if (!isset($CONFIG->registered_entities)) {
		return false;
	}

	$type = strtolower($type);

	// @todo registering a subtype implicitly registers the type.
	// see #2684
	if (!isset($CONFIG->registered_entities[$type])) {
		return false;
	}

	if ($subtype && !in_array($subtype, $CONFIG->registered_entities[$type])) {
		return false;
	}
	return true;
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
 * 	allowed_types => true|ARRAY True to show all types or an array of valid types.
 *
 * 	pagination => BOOL Display pagination links
 *
 * @return string A viewable list of entities
 * @since 1.7.0
 */
function elgg_list_registered_entities(array $options = array()) {
	global $autofeed;
	$autofeed = true;

	$defaults = array(
		'full_view' => false,
		'allowed_types' => true,
		'list_type_toggle' => false,
		'pagination' => true,
		'offset' => 0,
		'types' => array(),
		'type_subtype_pairs' => array(),
	);

	$options = array_merge($defaults, $options);

	// backward compatibility
	if (isset($options['view_type_toggle'])) {
		elgg_deprecated_notice("Option 'view_type_toggle' deprecated by 'list_type_toggle' in elgg_list* functions", 1.9);
		$options['list_type_toggle'] = $options['view_type_toggle'];
	}

	$types = get_registered_entity_types();

	foreach ($types as $type => $subtype_array) {
		if (in_array($type, $options['allowed_types']) || $options['allowed_types'] === true) {
			// you must explicitly register types to show up in here and in search for objects
			if ($type == 'object') {
				if (is_array($subtype_array) && count($subtype_array)) {
					$options['type_subtype_pairs'][$type] = $subtype_array;
				}
			} else {
				if (is_array($subtype_array) && count($subtype_array)) {
					$options['type_subtype_pairs'][$type] = $subtype_array;
				} else {
					$options['type_subtype_pairs'][$type] = ELGG_ENTITIES_ANY_VALUE;
				}
			}
		}
	}

	if (!empty($options['type_subtype_pairs'])) {
		$count = elgg_get_entities(array_merge(array('count' => true), $options));
		if ($count > 0) {
			$entities = elgg_get_entities($options);
		} else {
			$entities = array();
		}
	} else {
		$count = 0;
		$entities = array();
	}

	$options['count'] = $count;
	return elgg_view_entity_list($entities, $options);
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
 * @return bool
 * @since 1.8.0
 */
function elgg_instanceof($entity, $type = null, $subtype = null, $class = null) {
	$return = ($entity instanceof ElggEntity);

	if ($type) {
		/* @var ElggEntity $entity */
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
 * @warning This is different to time_updated.  Time_updated is automatically set,
 * while last_action is only set when explicitly called.
 *
 * @param int $guid   Entity annotation|relationship action carried out on
 * @param int $posted Timestamp of last action
 *
 * @return bool
 * @access private
 */
function update_entity_last_action($guid, $posted = null) {
	global $CONFIG;
	$guid = (int)$guid;
	$posted = (int)$posted;

	if (!$posted) {
		$posted = time();
	}

	if ($guid) {
		//now add to the river updated table
		$query = "UPDATE {$CONFIG->dbprefix}entities SET last_action = {$posted} WHERE guid = {$guid}";
		$result = update_data($query);
		if ($result) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Garbage collect stub and fragments from any broken delete/create calls
 *
 * @return void
 * @elgg_plugin_hook_handler gc system
 * @access private
 */
function _elgg_entities_gc() {
	global $CONFIG;

	$tables = array(
		'site' => 'sites_entity',
		'object' => 'objects_entity',
		'group' => 'groups_entity',
		'user' => 'users_entity',
	);

	foreach ($tables as $type => $table) {
		delete_data("DELETE FROM {$CONFIG->dbprefix}{$table}
			WHERE guid NOT IN (SELECT guid FROM {$CONFIG->dbprefix}entities)");
		delete_data("DELETE FROM {$CONFIG->dbprefix}entities
			WHERE type = '$type' AND guid NOT IN (SELECT guid FROM {$CONFIG->dbprefix}{$table})");
	}
}

/**
 * Runs unit tests for the entity objects.
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param array  $value  Array of tests
 *
 * @return array
 * @access private
 */
function _elgg_entities_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggEntityTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreAttributeLoaderTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesFromAnnotationsTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesFromMetadataTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesFromPrivateSettingsTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesFromRelationshipTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreGetEntitiesFromAttributesTest.php';
	return $value;
}

/**
 * Entities init function; establishes the default entity page handler
 *
 * @return void
 * @elgg_event_handler init system
 * @access private
 */
function _elgg_entities_init() {
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_entities_test');
	elgg_register_plugin_hook_handler('gc', 'system', '_elgg_entities_gc');
}

elgg_register_event_handler('init', 'system', '_elgg_entities_init');

<?php
/**
 * Procedural code for creating, loading, and modifying \ElggEntity objects.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Entities
 */

/**
 * Cache entities in memory once loaded.
 *
 * @global \ElggEntity[] $ENTITY_CACHE
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
		_elgg_services()->metadataCache->clear($guid);
	}
}

/**
 * Cache an entity.
 *
 * Stores an entity in $ENTITY_CACHE;
 *
 * @param \ElggEntity $entity Entity to cache
 *
 * @return void
 * @see _elgg_retrieve_cached_entity()
 * @see _elgg_invalidate_cache_for_entity()
 * @access private
 * @todo Use an \ElggCache object
 */
function _elgg_cache_entity(\ElggEntity $entity) {
	global $ENTITY_CACHE, $ENTITY_CACHE_DISABLED_GUIDS;

	// Don't cache non-plugin entities while access control is off, otherwise they could be
	// exposed to users who shouldn't see them when control is re-enabled.
	if (!($entity instanceof \ElggPlugin) && elgg_get_ignore_access()) {
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
 * @return \ElggEntity|bool false if entity not cached, or not fully loaded
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
 * \ElggEntity objects have a type and a subtype.  Subtypes
 * are defined upon creation and cannot be changed.
 *
 * Plugin authors generally don't need to use this function
 * unless writing their own SQL queries.  Use {@link \ElggEntity::getSubtype()}
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
	return _elgg_services()->subtypeTable->getId($type, $subtype);
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
	return _elgg_services()->subtypeTable->getSubtype($subtype_id);
}

/**
 * Retrieve subtype from the cache.
 *
 * @param string $type
 * @param string $subtype
 * @return \stdClass|null
 *
 * @access private
 */
function _elgg_retrieve_cached_subtype($type, $subtype) {
	return _elgg_services()->subtypeTable->retrieveFromCache($type, $subtype);
}

/**
 * Fetch all suptypes from DB to local cache.
 *
 * @access private
 */
function _elgg_populate_subtype_cache() {
	return _elgg_services()->subtypeTable->populateCache();
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
	return _elgg_services()->subtypeTable->getClass($type, $subtype);
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
	return _elgg_services()->subtypeTable->getClassFromId($subtype_id);
}

/**
 * Register \ElggEntities with a certain type and subtype to be loaded as a specific class.
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
	return _elgg_services()->subtypeTable->add($type, $subtype, $class);
}

/**
 * Removes a registered \ElggEntity type, subtype, and classname.
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
	return _elgg_services()->subtypeTable->remove($type, $subtype);
}

/**
 * Update a registered \ElggEntity type, subtype, and class name
 *
 * @param string $type    Type
 * @param string $subtype Subtype
 * @param string $class   Class name to use when loading this entity
 *
 * @return bool
 */
function update_subtype($type, $subtype, $class = '') {
	return _elgg_services()->subtypeTable->update($type, $subtype, $class);
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
 * @return \stdClass|false
 * @see entity_row_to_elggstar()
 * @access private
 */
function get_entity_as_row($guid) {
	return _elgg_services()->entityTable->getRow($guid);
}

/**
 * Create an Elgg* object from a given entity row.
 *
 * Handles loading all tables into the correct class.
 *
 * @param \stdClass $row The row of the entry in the entities table.
 *
 * @return \ElggEntity|false
 * @see get_entity_as_row()
 * @see add_subtype()
 * @see get_entity()
 * @access private
 *
 * @throws ClassException|InstallationException
 */
function entity_row_to_elggstar($row) {
	return _elgg_services()->entityTable->rowToElggStar($row);
}

/**
 * Loads and returns an entity object from a guid.
 *
 * @param int $guid The GUID of the entity
 *
 * @return \ElggEntity The correct Elgg or custom object based upon entity type and subtype
 */
function get_entity($guid) {
	return _elgg_services()->entityTable->get($guid);
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
	return _elgg_services()->entityTable->exists($guid);
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
	return _elgg_services()->entityTable->enable($guid, $recursive);
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
 * 	limit => null (from settings)|INT SQL limit clause (0 means no limit)
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
 * 	preload_owners => bool (false) If set to true, this function will preload
 * 					  all the owners of the returned entities resulting in better
 * 					  performance when displaying entities owned by several users
 *
 * 	callback => string A callback function to pass each row through
 *
 * 	distinct => bool (true) If set to false, Elgg will drop the DISTINCT clause from
 *				the MySQL query, which will improve performance in some situations.
 *				Avoid setting this option without a full understanding of the underlying
 *				SQL query Elgg creates.
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
	return _elgg_services()->entityTable->getEntities($options);
}

/**
 * Return entities from an SQL query generated by elgg_get_entities.
 *
 * @param string    $sql
 * @param \ElggBatch $batch
 * @return \ElggEntity[]
 *
 * @access private
 * @throws LogicException
 */
function _elgg_fetch_entities_from_sql($sql, \ElggBatch $batch = null) {
	return _elgg_services()->entityTable->fetchFromSql($sql, $batch);
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
	return _elgg_services()->entityTable->getEntityTypeSubtypeWhereSql($table, $types, $subtypes, $pairs);
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
	return _elgg_services()->entityTable->getGuidBasedWhereSql($column, $guids);
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
	return _elgg_services()->entityTable->getEntityTimeWhereSql($table,
		$time_created_upper, $time_created_lower, $time_updated_upper, $time_updated_lower);
}

/**
 * Returns a string of rendered entities.
 *
 * Displays list of entities with formatting specified by the entity view.
 *
 * @tip Pagination is handled automatically.
 *
 * @note Internal: This also provides the views for elgg_view_annotation().
 *
 * @note Internal: If the initial COUNT query returns 0, the $getter will not be called again.
 *
 * @param array    $options Any options from $getter options plus:
 *                   item_view => STR Optional. Alternative view used to render list items
 *                   full_view => BOOL Display full view of entities (default: false)
 *                   list_type => STR 'list' or 'gallery'
 *                   list_type_toggle => BOOL Display gallery / list switch
 *                   pagination => BOOL Display pagination links
 *                   no_results => STR|Closure Message to display when there are no entities
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
		'limit' => (int) max(get_input('limit', elgg_get_config('default_limit')), 0),
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
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.9.0
 * @throws InvalidArgumentException
 * @todo Does not support ordering by attributes or using an attribute pair shortcut like this ('title' => 'foo')
 */
function elgg_get_entities_from_attributes(array $options = array()) {
	return _elgg_services()->entityTable->getEntitiesFromAttributes($options);
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
	return _elgg_services()->entityTable->getEntityAttributeWhereSql($options);
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
	return _elgg_services()->entityTable->getDates(
		$type, $subtype, $container_guid, $site_guid, $order_by);
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
 * Checks if $entity is an \ElggEntity and optionally for type and subtype.
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
	$return = ($entity instanceof \ElggEntity);

	if ($type) {
		/* @var \ElggEntity $entity */
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
	return _elgg_services()->entityTable->updateLastAction($guid, $posted);
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
	$value[] = $CONFIG->path . 'engine/tests/ElggEntityPreloaderIntegrationTest.php';
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
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_entities_init');
};

<?php
/**
 * Procedural code for creating, loading, and modifying \ElggEntity objects.
 */

/**
 * Return the class name registered as a constructor for an entity of a given type and subtype
 *
 * @see elgg_set_entity_type()
 *
 * @param string $type    The type
 * @param string $subtype The subtype
 *
 * @return string
 */
function elgg_get_entity_class($type, $subtype) {
	return _elgg_services()->entityTable->getEntityClass($type, $subtype);
}

/**
 * Sets class constructor name for entities with given type and subtype
 *
 * By default entities are loaded as one of the 4 parent objects:
 *  - site: ElggSite
 *  - user: ElggUser
 *  - object: ElggObject
 *  - group: ElggGroup
 *
 * Entity classes for subtypes should extend the base class for entity type,
 * e.g. ElggBlog must extend ElggObject
 *
 * @param string $type    Entity type
 * @param string $subtype Entity subtype
 * @param string $class   Class name for the object
 *                        Can be empty to reset previously declared class name
 *
 * @return void
 */
function elgg_set_entity_class($type, $subtype, $class = "") {
	_elgg_services()->entityTable->setEntityClass($type, $subtype, $class);
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
	if ($guid == 1) {
		return _elgg_config()->site;
	}
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
 * Get the current site entity
 *
 * @return \ElggSite
 * @since 1.8.0
 */
function elgg_get_site_entity() {
	return _elgg_config()->site;
}

/**
 * Fetches/counts entities or performs a calculation on their properties
 *
 * Note that you can use singulars for most options, e.g. $options['type'] will be normalized to $options['types']
 *
 * ------------------------
 * TYPE SUBTYPE CONSTRAINTS
 * ------------------------
 *
 * Filter entities by their type and subtype
 *
 * @option string[] $types
 * @option string[] $subtypes
 * @option string[] $type_subtype_pairs
 *
 * <code>
 * $options['types'] = ['object'];
 * $options['subtypes'] = ['blog', 'file'];
 * $options['type_subtype_pairs'] = [
 *     'object' => ['blog', 'file'],
 *     'group' => [], // all group subtypes
 *     'user' => null, // all user subtypes
 * ];
 * </code>
 *
 * ----------------
 * GUID CONSTRAINTS
 * ----------------
 *
 * Filter entities by their guid, owner or container
 *
 * @option int[]|ElggEntity[] $guids
 * @option int[]|ElggEntity[] $owner_guids
 * @option int[]|ElggEntity[] $container_guids
 *
 * ----------------
 * TIME CONSTRAINTS
 * ----------------
 *
 * Filter entities that were created, updated or last acted on within certain bounds
 *
 * @option DateTime|string|int $created_after
 * @option DateTime|string|int $created_before
 * @option DateTime|string|int $updated_after
 * @option DateTime|string|int $updated_before
 * @option DateTime|string|int $last_action_after
 * @option DateTime|string|int $last_action_before
 *
 * <code>
 * $options['created_after'] = '-1 year';
 * $options['created_before'] = 'now';
 * </code>
 *
 * ------------------
 * ACCESS CONSTRAINTS
 * ------------------
 *
 * Filter entities by their access_id attribute. Note that this filter apply to entities that the user has access to.
 * You can ignore access system using {@link elgg_set_ignore_access()}
 *
 * @option int[] $access_id
 *
 * ----------------
 * LIMIT AND OFFSET
 * ----------------
 *
 * This options are used for paginating lists of entities
 *
 * @option int $limit
 * @option int $offset
 *
 * --------------------
 * METADATA CONSTRAINTS
 * --------------------
 *
 * Filter entities by their metadata and attributes
 *
 * The following options will be merged and applied as a metadata pair to @options['metadata_name_value_pairs']
 * Note metadata names can contain attributes names and will be resolved automatically during query building.
 * @option int[]                $metadata_ids
 * @option string[]             $metadata_names
 * @option mixed                $metadata_values
 * @option DateTime|string|int  $metadata_created_after
 * @option DateTime|string|int  $metadata_created_before
 * @option bool                 $metadata_case_sensitive
 *
 * Metadata name value pairs will be joined by the boolean specified in $metadata_name_value_pairs_operator
 * @option array                $metadata_name_value_pairs
 * @option string               $metadata_name_value_pairs_operator
 *
 * In addition to metadata name value pairs, you can specify search pair, which will be merged using OR boolean
 * and will filter entities regardless of metadata name value pairs and their operator
 *
 * @option array                $search_name_value_pairs
 *
 * <code>
 * // Search for entities with:
 * // status of draft or unsaved_draft
 * // AND index greater than 5
 * // AND (title/description containing the word hello OR tags containing the word world)
 * $options['metadata_name_value_pairs'] = [
 *    [
 *       'name' => 'status',
 *       'value' => ['draft', 'unsaved_draft'],
 *       'operand' => 'IN',
 *       'created_after' => '-1 day',
 *    ],
 *    [
 *        'name' => 'index',
 *        'value' => 5,
 *        'operand' => '>=',
 *        'type' => ELGG_VALUE_INTEGER,
 *    ]
 * ];
 * $options['search_name_value_pairs'] = [
 *    [
 *       'name' => ['title', 'description'],
 *       'value' => '%hello%',
 *       'operand' => 'LIKE',
 *       'case_sensitive' => false,
 *    ],
 *    [
 *       'name' => 'tags',
 *       'value' => '%world%',
 *       'operand' => 'LIKE',
 *       'case_sensitive' => false,
 *    ],
 * ];
 * </code>
 *
 * ----------------------
 * ANNOTATION CONSTRAINTS
 * ----------------------
 *
 * Filter entities by their annotations
 *
 * The following options will be merged and applied as an annotation pair to @options['annotation_name_value_pairs']
 * @option int[]                $annotation_ids
 * @option string[]             $annotation_names
 * @option mixed                $annotation_values
 * @option bool                 $annotation_case_sensitive
 * @option DateTime|string|int  $annotation_created_after
 * @option DateTime|string|int  $annotation_created_before
 * @option int[]|ElggEntity[]   $annotation_owner_guids
 * @option int[]|ElggEntity[]   $annotation_access_ids
 *
 * Annotation name value pairs will be joined by the boolean specified in $annotation_name_value_pairs_operator
 * @option array                $annotation_name_value_pairs
 * @option string               $annotation_name_value_pairs_operator
 **
 * <code>
 * $options['annotation_name_value_pairs'] = [
 *    [
 *       'name' => 'likes',
 *       'created_after' => '-1 day',
 *    ],
 *    [
 *        'name' => 'rating',
 *        'value' => 5,
 *        'operand' => '>=',
 *        'type' => ELGG_VALUE_INTEGER,
 *    ],
 *    [
 *        'name' => 'review',
 *        'value' => '%awesome%',
 *        'operand' => 'LIKE',
 *        'type' => ELGG_VALUE_STRING,
 *    ]
 * ];
 * </code>
 *
 * ------------------------
 * RELATIONSHIP CONSTRAINTS
 * ------------------------
 *
 * Filter entities by their relationships
 *
 * The following options will be merged and applied as a relationship pair to $options['relationship_name_value_pairs']
 * @option int[]                $relationship_ids
 * @option string[]             $relationship
 * @option int[]|ElggEntity[]   $relationship_guid
 * @option bool                 $inverse_relationship
 * @option DateTime|string|int  $relationship_created_after
 * @option DateTime|string|int  $relationship_created_before
 * @option string               $relationship_join_on Column name in the name main table
 *
 * @option array                $relationship_pairs
 *
 * <code>
 * // Get all entities that user with guid 25 has friended or been friended by
 * $options['relationship_pairs'] = [
 *    [
 *       'relationship' => 'friend',
 *       'relationship_guid' => 25,
 *       'inverse_relationship' => true,
 *    ],
 *    [
 *       'relationship' => 'friend',
 *       'relationship_guid' => 25,
 *       'inverse_relationship' => false,
 *    ],
 * ];
 * </code>
 *
 * ----------------------------
 * PRIVATE SETTINGS CONSTRAINTS
 * ----------------------------
 *
 * Filter entities by their private settings
 *
 * The following options will be merged and applied as a private_setting pair to
 * $options['private_setting_name_value_pairs']
 * @option int[]                $private_setting_ids
 * @option string[]             $private_setting_names
 * @option mixed                $private_setting_values
 * @option bool                 $private_setting_case_sensitive
 *
 * Private name value pairs will be joined by the boolean specified in $private_setting_name_value_pairs_operator
 * @option array                $private_setting_name_value_pairs
 * @option string               $private_setting_name_value_pairs_operator
 *
 * Setting names in all pairs can be namespaced using the prefix
 * @option string               $private_setting_name_prefix
 *
 * <code>
 * $options['private_setting_name_value_pairs'] = [
 *    [
 *       'name' => 'handler',
 *       'value' => ['admin', 'dashboard'],
 *       'operand' => 'IN',
 *    ],
 * ];
 * </code>
 *
 * -------
 * SORTING
 * -------
 *
 * You can specify sorting options using ONE of the following options
 *
 * Order by a calculation performed on annotation name value pairs
 * @see    elgg_get_entities_from_annotation_calculation()
 * $option array annotation_sort_by_calculation e.g. avg, max, min, sum
 *
 * Order by value of a specific annotation
 * @option array $order_by_annotation
 *
 * Order by value of a speicifc metadata/attribute
 * @option array $order_by_metadata
 *
 * Order by arbitrary clauses, if $reverse_order_by is true, then all asc|desc statements in order by clauses will be
 * replaced with their opposites
 * @option array $order_by
 * @option bool $reverse_order by
 *
 * <code>
 * $options['order_by_metadata'] = [
 *     'name' => 'priority',
 *     'direction' => 'DESC',
 *     'as' => 'integer',
 * ];
 * $options['order_by_annotation'] = [
 *     'name' => 'priority',
 *     'direction' => 'DESC',
 *     'as' => 'integer',
 * ];
 *
 * $sort_by = new \Elgg\Database\Clauses\EntitySortByClause();
 * $sort_by->property = 'private';
 * $sort_by->property_type = 'private_setting';
 * $sort_by->join_type = 'left';
 *
 * $fallback = new \Elgg\Database\Clauses\OrderByClause('e.time_created', 'desc');
 *
 * $options['order_by'] = [
 *     $sort_by,
 *     $fallback,
 * ];
 * </code>
 *
 * -----------------
 * COUNT/CALCULATION
 * -----------------
 *
 * Performs a calculation on a set of entities that match all of the criteria
 * If any of these are specific, the return of this function will be int or float
 *
 * Return total number of entities
 * @option bool $count
 *
 * Perform a calculation on a set of entity's annotations using a numeric sql function
 * If specified, the number of annotation name value pairs can not be more than 1, or they must be merged using OR
 * operator
 * @option string $annotation_calculation e.g. avg, max, min, sum
 *
 * Perform a calculation on a set of entity's metadat using a numeric sql function
 * If specified, the number of metadata name value pairs can not be more than 1, or they must be merged using OR
 * operator
 * @option string $metadata_calculation e.g. avg, max, min, sum
 *
 * ----------
 * SQL SELECT
 * ----------
 *
 * @option array $selects
 * <code>
 * $options['selects'] = [
 *    'e.last_action AS last_action',
 *    function(QueryBulder $qb, $main_alias) {
 *        $joined_alias = $qb->joinMetadataTable($main_alias, 'guid', 'status');
 *        return "$joined_alias.value AS status";
 *    }
 * ];
 * </code>
 *
 * --------
 * SQL JOIN
 * --------
 *
 * @option array $joins
 * <code>
 * $on = function(QueryBuilder $qb, $joined_alias, $main_alias) {
 *     return $qb->compare("$joined_alias.user_guid", '=', "$main_alias.guid");
 * };
 * $options['joins'] = [
 *     new JoinClause('access_collections_membership', 'acm', $on);
 * ];
 * </code>
 *
 * ----------
 * SQL GROUPS
 * ----------
 *
 * @option array $group_by
 * @option array $having
 *
 * <code>
 * $options['group_by'] = [
 *      function(QueryBuilder $qb, $main_alias) {
 *          return "$main_alias.guid";
 *      }
 * ];
 * $options['having'] = [
 *      function(QueryBuilder $qb, $main_alias) {
 *          return $qb->compare("$main_alias.guid", '>=', 50, ELGG_VALUE_INTEGER);
 *      }
 * ];
 * </code>
 *
 * ---------
 * SQL WHERE
 * ---------
 *
 * @option array $where
 * <code>
 * $options['wheres'] = [
 *      function(QueryBuilder $qb, $main_alias) {
 *          return $qb->merge([
 *              $qb->compare("$main_alias.guid", '>=', 50, ELGG_VALUE_INTEGER),
 *              $qb->compare("$main_alias.guid", '<=', 250, ELGG_VALUE_INTEGER),
 *          ], 'OR');
 *      }
 * ];
 * </code>
 *
 * --------------
 * RESULT OPTIONS
 * --------------
 *
 * @option bool $distinct           If set to false, Elgg will drop the DISTINCT clause from
 *                                  the MySQL query, which will improve performance in some situations.
 *                                  Avoid setting this option without a full understanding of the underlying
 *                                  SQL query Elgg creates.
 *                                  Default: true
 * @option callable|false $callback A callback function to pass each row through
 *                                  Default: entity_row_to_elggstar
 * @option bool $preload_owners     If set to true, this function will preload
 *                                  all the owners of the returned entities resulting in better
 *                                  performance when displaying entities owned by several users
 *                                  Default: false
 * @option bool $batch              If set to true, an Elgg\BatchResult object will be returned instead of an array.
 *                                  Default: false
 * @option bool $batch_inc_offset   If "batch" is used, this tells the batch to increment the offset
 *                                  on each fetch. This must be set to false if you delete the batched results.
 *                                  Default: true
 * @option int  $batch_size         If "batch" is used, this is the number of entities/rows to pull in before
 *                                  requesting more.
 *                                  Default: 25
 *
 *
 * @see    elgg_list_entities()
 * @see    \Elgg\Database\LegacyQueryOptionsAdapter
 *
 * @param array $options Options
 *
 * @return \ElggEntity[]|int|mixed If count, int. Otherwise an array or an Elgg\BatchResult. false on errors.
 *
 * @since 1.7.0
 */
function elgg_get_entities(array $options = []) {
	return \Elgg\Database\Entities::find($options);
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
 *                   list_type => STR 'list', 'gallery', or 'table'
 *                   columns => ARR instances of Elgg\Views\TableColumn if list_type is "table"
 *                   list_type_toggle => BOOL Display gallery / list switch
 *                   pagination => BOOL Display pagination links
 *                   no_results => STR|Closure Message to display when there are no entities
 *
 * @param callable $getter  The entity getter function to use to fetch the entities.
 * @param callable $viewer  The function to use to view the entity list.
 *
 * @return string
 * @since 1.7
 * @see elgg_get_entities()
 * @see elgg_view_entity_list()
 */
function elgg_list_entities(array $options = [], $getter = 'elgg_get_entities', $viewer = 'elgg_view_entity_list') {

	elgg_register_rss_link();

	$offset_key = isset($options['offset_key']) ? $options['offset_key'] : 'offset';

	$defaults = [
		'offset' => (int) max(get_input($offset_key, 0), 0),
		'limit' => (int) max(get_input('limit', _elgg_config()->default_limit), 0),
		'full_view' => false,
		'list_type_toggle' => false,
		'pagination' => true,
		'no_results' => '',
	];

	$options = array_merge($defaults, $options);

	$entities = [];
	
	if (!$options['pagination']) {
		$options['count'] = false;
		$entities = call_user_func($getter, $options);
		unset($options['count']);
	} else {
		$options['count'] = true;
		$count = call_user_func($getter, $options);
	
		if ($count > 0) {
			$options['count'] = false;
			$entities = call_user_func($getter, $options);
		}

		$options['count'] = $count;
	}
	
	return call_user_func($viewer, $entities, $options);
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
 * @param int    $ignored        Ignored parameter
 * @param string $order_by       Order_by SQL order by clause
 *
 * @return array|false Either an array months as YYYYMM, or false on failure
 */
function get_entity_dates($type = '', $subtype = '', $container_guid = 0, $ignored = 0, $order_by = 'time_created') {
	return _elgg_services()->entityTable->getDates($type, $subtype, $container_guid, $order_by);
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
	$type = strtolower($type);
	if (!in_array($type, \Elgg\Config::getEntityTypes())) {
		return false;
	}

	$entities = _elgg_config()->registered_entities;
	if (!$entities) {
		$entities = [];
	}

	if (!isset($entities[$type])) {
		$entities[$type] = [];
	}

	if ($subtype) {
		$entities[$type][] = $subtype;
	}

	_elgg_config()->registered_entities = $entities;

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
	$type = strtolower($type);
	if (!in_array($type, \Elgg\Config::getEntityTypes())) {
		return false;
	}

	$entities = _elgg_config()->registered_entities;
	if (!$entities) {
		return false;
	}

	if (!isset($entities[$type])) {
		return false;
	}

	if ($subtype) {
		if (in_array($subtype, $entities[$type])) {
			$key = array_search($subtype, $entities[$type]);
			unset($entities[$type][$key]);
		} else {
			return false;
		}
	} else {
		unset($entities[$type]);
	}

	_elgg_config()->registered_entities = $entities;
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
	$registered_entities = _elgg_config()->registered_entities;
	if (!$registered_entities) {
		return false;
	}

	if ($type) {
		$type = strtolower($type);
	}

	if (!empty($type) && !isset($registered_entities[$type])) {
		return false;
	}

	if (empty($type)) {
		return $registered_entities;
	}

	return $registered_entities[$type];
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
	$registered_entities = _elgg_config()->registered_entities;
	if (!$registered_entities) {
		return true;
	}

	$type = strtolower($type);

	// @todo registering a subtype implicitly registers the type.
	// see #2684
	if (!isset($registered_entities[$type])) {
		return false;
	}

	if ($subtype && !in_array($subtype, $registered_entities[$type])) {
		return false;
	}
	return true;
}

/**
 * Returns a viewable list of entities based on the registered types.
 *
 * @see elgg_view_entity_list()
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
function elgg_list_registered_entities(array $options = []) {
	elgg_register_rss_link();

	$defaults = [
		'full_view' => false,
		'allowed_types' => true,
		'list_type_toggle' => false,
		'pagination' => true,
		'offset' => 0,
		'types' => [],
		'type_subtype_pairs' => [],
	];

	$options = array_merge($defaults, $options);

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
		$count = elgg_get_entities(array_merge(['count' => true], $options));
		if ($count > 0) {
			$entities = elgg_get_entities($options);
		} else {
			$entities = [];
		}
	} else {
		$count = 0;
		$entities = [];
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
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_instanceof($entity, $type = null, $subtype = null) {
	$return = ($entity instanceof \ElggEntity);

	if ($type) {
		/* @var \ElggEntity $entity */
		$return = $return && ($entity->getType() == $type);
	}

	if ($subtype) {
		$return = $return && ($entity->getSubtype() == $subtype);
	}

	return $return;
}

/**
 * Checks options for the existing of site_guid or site_guids contents and reports a warning if found
 *
 * @param array $options array of options to check
 *
 * @return void
 */
function _elgg_check_unsupported_site_guid(array $options = []) {
	$site_guid = elgg_extract('site_guid', $options, elgg_extract('site_guids', $options));
	if ($site_guid === null) {
		return;
	}
	
	$backtrace = debug_backtrace();
	// never show this call.
	array_shift($backtrace);

	if (!empty($backtrace[0]['class'])) {
		$warning = "Passing site_guid or site_guids to the method {$backtrace[0]['class']}::{$backtrace[0]['file']} is not supported.";
		$warning .= "Please update your usage of the method.";
	} else {
		$warning = "Passing site_guid or site_guids to the function {$backtrace[0]['function']} in {$backtrace[0]['file']} is not supported.";
		$warning .= "Please update your usage of the function.";
	}

	_elgg_services()->logger->warn($warning);
}

/**
 * Runs unit tests for the entity objects.
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $value Array of tests
 *
 * @return array
 * @access private
 */
function _elgg_entities_test($hook, $type, $value) {
	$value[] = ElggEntityUnitTest::class;
	$value[] = ElggCoreAttributeLoaderTest::class;
	//$value[] = ElggCoreGetEntitiesBaseTest::class;
	$value[] = ElggCoreGetEntitiesFromAnnotationsTest::class;
	$value[] = ElggCoreGetEntitiesFromMetadataTest::class;
	$value[] = ElggCoreGetEntitiesFromPrivateSettingsTest::class;
	$value[] = ElggCoreGetEntitiesFromRelationshipTest::class;
	$value[] = ElggEntityPreloaderIntegrationTest::class;
	$value[] = ElggCoreObjectTest::class;
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

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_entities_init');
};

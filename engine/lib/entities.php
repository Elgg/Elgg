<?php
/**
 * Procedural code for creating, loading, and modifying \ElggEntity objects.
 */

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;

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
function elgg_get_entity_class(string $type, string $subtype): string {
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
function elgg_set_entity_class(string $type, string $subtype, string $class = ''): void {
	_elgg_services()->entityTable->setEntityClass($type, $subtype, $class);
}

/**
 * Returns a database row from the entities table.
 *
 * @tip Use get_entity() to return the fully loaded entity.
 *
 * @warning This will only return results if a) it exists, b) you have access to it.
 *
 * @param int $guid The GUID of the object to extract
 *
 * @return \stdClass|null
 */
function elgg_get_entity_as_row(int $guid): ?\stdClass {
	return _elgg_services()->entityTable->getRow($guid);
}

/**
 * Loads and returns an entity object from a guid.
 *
 * @param int $guid The GUID of the entity
 *
 * @return null|\ElggEntity The correct Elgg or custom object based upon entity type and subtype
 */
function get_entity(int $guid): ?\ElggEntity {
	if ($guid === 1) {
		return _elgg_services()->config->site;
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
function elgg_entity_exists(int $guid): bool {
	return _elgg_services()->entityTable->exists($guid);
}

/**
 * Get the current site entity
 *
 * @return \ElggSite
 * @since 1.8.0
 */
function elgg_get_site_entity(): \ElggSite {
	return _elgg_services()->config->site;
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
 * You can ignore access system using {@link elgg_call()}
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
 * @warning During normalization, search name value pairs will ignore properties under metadata_ namespace, that is
 *          you can not use metadata_ids, metadata_created_before, metadata_created_after, metadata_case_sensitive
 *          to constrain search pairs. You will need to pass these properties for each individual search pair,
 *          as seen in the example below
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
 *       // 'ids' => [55, 56, 57, 58, 59, 60], // only search these 5 metadata rows
 *       'name' => 'tags',
 *       'value' => '%world%',
 *       'operand' => 'LIKE',
 *       'case_sensitive' => false,
 *       'created_after' => '-1 day',
 *       'created_before' => 'now',
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
 *
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
 * -------
 * SORTING
 * -------
 *
 * You can specify sorting options using ONE of the following options
 *
 * NOTE: Some order by options only work when fetching entities and not from
 * derived function (eg elgg_get_annotations, elgg_get_relationships)
 *
 * Order by a calculation performed on annotation name value pairs
 * $option array annotation_sort_by_calculation e.g. avg, max, min, sum
 *
 * Order by arbitrary clauses
 * @option array $order_by
 *
 * Order by attribute/annotation/metadata
 * @option sort_by an array of sorting definitions
 *
 * <code>
 *
 * $sort_by = new \Elgg\Database\Clauses\EntitySortByClause();
 * $sort_by->property = 'private';
 * $sort_by->property_type = 'metadata';
 * $sort_by->join_type = 'left';
 *
 * $fallback = new \Elgg\Database\Clauses\OrderByClause('e.time_created', 'desc');
 *
 * $options['order_by'] = [
 *     $sort_by,
 *     $fallback,
 * ];
 *
 * // @see \Elgg\Database\Clauses\EntitySortByClause
 *
 * // single sort_by option
 * $options['sort_by'] = [
 * 		'property_type' => 'attribute',
 * 		'property' => 'time_created',
 * 		'direction' => 'ASC',
 * 	];
 *
 * // multiple sort_by options
 * $options['sort_by'] = [
 * 		[
 * 			'property_type' => 'attribute',
 * 			'property' => 'time_created',
 * 			'direction' => 'ASC',
 * 		],
 * 		[
 * 			'property_type' => 'metadata',
 * 			'property' => 'name',
 * 			'direction' => 'DESC',
 * 		],
 * 		[
 * 			'property_type' => 'annotation',
 * 			'property' => 'priority',
 * 			'direction' => 'ASC',
 * 			'signed' => true,
 * 		],
 * 		[
 * 			'property_type' => 'relationship',
 * 			'property' => 'members',
 * 			'direction' => 'ASC',
 * 			'inverse_relationship' => true,
 * 		],
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
 * @option bool $distinct                 If set to false, Elgg will drop the DISTINCT clause from
 *                                        the MySQL query, which will improve performance in some situations.
 *                                        Avoid setting this option without a full understanding of the underlying
 *                                        SQL query Elgg creates.
 *                                        Default: true
 * @option callable|false $callback       A callback function to pass each row through
 *                                        Default: _elgg_services()->entityTable->rowToElggStar()
 * @option bool $preload_owners           If set to true, this function will preload
 *                                        all the owners of the returned entities resulting in better
 *                                        performance when displaying entities owned by several users
 *                                        Default: false
 * @option bool $preload_containers       If set to true, this function will preload
 *                                        all the containers of the returned entities resulting in better
 *                                        performance when displaying entities contained by several users/groups
 *                                        Default: false
 * @option bool $batch                    If set to true, an \ElggBatch object will be returned instead of an array.
 *                                        Default: false
 * @option bool $batch_inc_offset         If "batch" is used, this tells the batch to increment the offset
 *                                        on each fetch. This must be set to false if you delete the batched results.
 *                                        Default: true
 * @option int  $batch_size               If "batch" is used, this is the number of entities/rows to pull in before
 *                                        requesting more.
 *                                        Default: 25
 *
 *
 * @see    elgg_list_entities()
 * @see    \Elgg\Traits\Database\LegacyQueryOptionsAdapter
 *
 * @param array $options Options
 *
 * @return \ElggEntity[]|int|mixed If count, int. Otherwise an array or an \ElggBatch
 *
 * @since 1.7.0
 */
function elgg_get_entities(array $options = []) {
	return \Elgg\Database\Entities::find($options);
}

/**
 * Returns a count of entities.
 *
 * @param array $options the same options as elgg_get_entities() but forces 'count' to true
 *
 * @return int
 */
function elgg_count_entities(array $options = []): int {
	$options['count'] = true;
	
	return (int) elgg_get_entities($options);
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
 *                          - item_view => STR Optional. Alternative view used to render list items
 *                          - full_view => BOOL Display full view of entities (default: false)
 *                          - list_type => STR 'list', 'gallery', or 'table'
 *                          - columns => ARR instances of Elgg\Views\TableColumn if list_type is "table"
 *                          - pagination => BOOL Display pagination links
 *                          - no_results => STR|true for default notfound text|Closure Message to display when there are no entities
 *
 * @param callable $getter  The entity getter function to use to fetch the entities.
 * @param callable $viewer  The function to use to view the entity list.
 *
 * @return string
 * @since 1.7
 * @see elgg_get_entities()
 * @see elgg_view_entity_list()
 */
function elgg_list_entities(array $options = [], callable $getter = null, callable $viewer = null): string {
	$getter = $getter ?? 'elgg_get_entities'; // callables only support default NULL value
	$viewer = $viewer ?? 'elgg_view_entity_list'; // callables only support default NULL value
	$offset_key = $options['offset_key'] ?? 'offset';

	$defaults = [
		'offset' => (int) max(get_input($offset_key, 0), 0),
		'limit' => (int) max(get_input('limit', _elgg_services()->config->default_limit), 0),
		'sort_by' => get_input('sort_by', []),
		'full_view' => false,
		'pagination' => true,
		'no_results' => '',
		'preload_owners' => true,
		'preload_containers' => true,
	];

	$options = array_merge($defaults, $options);
	
	$options['register_rss_link'] = elgg_extract('register_rss_link', $options, elgg_extract('pagination', $options));
	if ($options['register_rss_link']) {
		elgg_register_rss_link();
	}

	$options['count'] = false;
	$entities = call_user_func($getter, $options);
	$options['count'] = is_array($entities) ? count($entities) : 0;
	
	if (!is_array($entities) && $viewer === 'elgg_view_entity_list') {
		_elgg_services()->logger->error(elgg_echo('list:error:getter:admin', [$getter, gettype($entities), $viewer]));
		
		return elgg_echo('list:error:getter:user');
	}
	
	if (!empty($entities) || !empty($options['offset'])) {
		$count_needed = true;
		if (!$options['pagination']) {
			$count_needed = false;
		} elseif (!$options['offset'] && !$options['limit']) {
			$count_needed = false;
		} elseif (($options['count'] < (int) $options['limit']) && !$options['offset']) {
			$count_needed = false;
		}
		
		if ($count_needed) {
			$options['count'] = true;
		
			$options['count'] = (int) call_user_func($getter, $options);
		}
	}
	
	return call_user_func($viewer, $entities, $options);
}

/**
 * Returns a list of months in which entities were updated or created.
 *
 * @tip     Use this to generate a list of archives by month for when entities were added or updated.
 *
 * @warning Months are returned in the form YYYYMM.
 *
 * @param array $options all entity options supported by {@see elgg_get_entities()}
 *
 * @return array An array months as YYYYMM
 * @since 3.0
 */
function elgg_get_entity_dates(array $options = []): array {
	return \Elgg\Database\Entities::with($options)->getDates();
}

/**
 * Returns search results as an array of entities, as a batch, or a count,
 * depending on parameters given.
 *
 * @param array $options Search parameters
 *                       Accepts all options supported by {@link elgg_get_entities()}
 *
 * @option string $query         Search query
 * @option string $type          Entity type. Required if no search type is set
 * @option string $search_type   Custom search type. Required if no type is set
 * @option array  $fields        An array of fields to search in, supported keys are
 * 		[
 * 			'attributes' => ['some attribute', 'some other attribute'],
 *	 		'metadata' => ['some metadata name', 'some other metadata name'],
 * 			'annotations' => ['some annotation name', 'some other annotation name'],
 * 		]
 * @option bool   $partial_match Allow partial matches, e.g. find 'elgg' when search for 'el'
 * @option bool   $tokenize      Break down search query into tokens,
 *                               e.g. find 'elgg has been released' when searching for 'elgg released'
 *
 * @return ElggBatch|ElggEntity[]|int|false
 *
 * @see elgg_get_entities()
 */
function elgg_search(array $options = []) {
	try {
		return _elgg_services()->search->search($options);
	} catch (\Elgg\Exceptions\DomainException $e) {
		return false;
	}
}

/**
 * Return an array reporting the number of various entities in the system.
 *
 * @param array $options additional options
 *
 * @return array
 * @since 4.3
 * @see elgg_get_entities()
 */
function elgg_get_entity_statistics(array $options = []): array {
	$required = [
		'selects' => [
			'count(*) AS total',
		],
		'group_by' => [
			function(QueryBuilder $qb, $main_alias) {
				return "{$main_alias}.type";
			},
			function(QueryBuilder $qb, $main_alias) {
				return "{$main_alias}.subtype";
			},
		],
		'order_by' => [
			new OrderByClause('total', 'desc'),
		],
		'callback' => function($row) {
			return (object) [
				'type' => $row->type,
				'subtype' => $row->subtype,
				'total' => $row->total,
			];
		},
		'limit' => false,
	];
	
	$options = array_merge($options, $required);
	
	$rows = elgg_call(ELGG_IGNORE_ACCESS, function() use ($options) {
		return elgg_get_entities($options);
	});
	
	$entity_stats = [];
	foreach ($rows as $row) {
		$type = $row->type;
		if (!isset($entity_stats[$type]) || !is_array($entity_stats[$type])) {
			$entity_stats[$type] = [];
		}
		
		$entity_stats[$type][$row->subtype] = $row->total;
	}
	
	return $entity_stats;
}

/**
 * Checks if a capability is enabled for a specified type/subtype
 *
 * @param string $type       type of the entity
 * @param string $subtype    subtype of the entity
 * @param string $capability name of the capability to check
 * @param bool   $default    default value to return if it is not explicitly set
 *
 * @return bool
 * @since 4.1
 */
function elgg_entity_has_capability(string $type, string $subtype, string $capability, bool $default = false): bool {
	return _elgg_services()->entity_capabilities->hasCapability($type, $subtype, $capability, $default);
}

/**
 * Enables the capability for a specified type/subtype
 *
 * @param string $type       type of the entity
 * @param string $subtype    subtype of the entity
 * @param string $capability name of the capability to set
 *
 * @return void
 * @since 4.1
 */
function elgg_entity_enable_capability(string $type, string $subtype, string $capability): void {
	_elgg_services()->entity_capabilities->setCapability($type, $subtype, $capability, true);
}

/**
 * Disables the capability for a specified type/subtype
 *
 * @param string $type       type of the entity
 * @param string $subtype    subtype of the entity
 * @param string $capability name of the capability to set
 *
 * @return void
 * @since 4.1
 */
function elgg_entity_disable_capability(string $type, string $subtype, string $capability): void {
	_elgg_services()->entity_capabilities->setCapability($type, $subtype, $capability, false);
}

/**
 * Returns an array of type/subtypes with the requested capability enabled
 *
 * @param string $capability name of the capability to set
 *
 * @return array
 * @since 4.1
 */
function elgg_entity_types_with_capability(string $capability): array {
	return _elgg_services()->entity_capabilities->getTypesWithCapability($capability);
}

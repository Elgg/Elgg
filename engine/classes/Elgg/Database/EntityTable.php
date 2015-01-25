<?php
namespace Elgg\Database;

use IncompleteEntityException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.10.0
 */
class EntityTable {
	/**
	 * Global Elgg configuration
	 * 
	 * @var \stdClass
	 */
	private $CONFIG;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;
		$this->CONFIG = $CONFIG;
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
	function getRow($guid) {
		
	
		if (!$guid) {
			return false;
		}
	
		$guid = (int) $guid;
		$access = _elgg_get_access_where_sql(array('table_alias' => ''));
	
		return _elgg_services()->db->getDataRow("SELECT * from {$this->CONFIG->dbprefix}entities where guid=$guid and $access");
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
	 * @throws \ClassException|\InstallationException
	 */
	function rowToElggStar($row) {
		if (!($row instanceof \stdClass)) {
			return $row;
		}
	
		if ((!isset($row->guid)) || (!isset($row->subtype))) {
			return $row;
		}
	
		$new_entity = false;
	
		// Create a memcache cache if we can
		static $newentity_cache;
		if ((!$newentity_cache) && (is_memcache_available())) {
			$newentity_cache = new \ElggMemcache('new_entity_cache');
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
	
				if (!($new_entity instanceof \ElggEntity)) {
					$msg = $classname . " is not a " . '\ElggEntity' . ".";
					throw new \ClassException($msg);
				}
			} else {
				error_log("Class '" . $classname . "' was not found, missing plugin?");
			}
		}
	
		if (!$new_entity) {
			//@todo Make this into a function
			switch ($row->type) {
				case 'object' :
					$new_entity = new \ElggObject($row);
					break;
				case 'user' :
					$new_entity = new \ElggUser($row);
					break;
				case 'group' :
					$new_entity = new \ElggGroup($row);
					break;
				case 'site' :
					$new_entity = new \ElggSite($row);
					break;
				default:
					$msg = "Entity type " . $row->type . " is not supported.";
					throw new \InstallationException($msg);
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
	 * @param int    $guid The GUID of the entity
	 * @param string $type The type of the entity. If given, even an existing entity with the given GUID
	 *                     will not be returned unless its type matches.
	 *
	 * @return \ElggEntity The correct Elgg or custom object based upon entity type and subtype
	 */
	function get($guid, $type = '') {
		// We could also use: if (!(int) $guid) { return false },
		// but that evaluates to a false positive for $guid = true.
		// This is a bit slower, but more thorough.
		if (!is_numeric($guid) || $guid === 0 || $guid === '0') {
			return false;
		}
		
		// Check local cache first
		$new_entity = _elgg_retrieve_cached_entity($guid);
		if ($new_entity) {
			if ($type) {
				return elgg_instanceof($new_entity, $type) ? $new_entity : false;
			}
			return $new_entity;
		}

		$options = [
			'guid' => $guid,
			'limit' => 1,
			'site_guids' => ELGG_ENTITIES_ANY_VALUE, // for BC with get_entity, allow matching any site
		];
		if ($type) {
			$options['type'] = $type;
		}
		$entities = $this->getEntities($options);
		return $entities ? $entities[0] : false;
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
	 */
	function exists($guid) {
		
	
		$guid = sanitize_int($guid);
	
		$query = "SELECT count(*) as total FROM {$this->CONFIG->dbprefix}entities WHERE guid = $guid";
		$result = _elgg_services()->db->getDataRow($query);
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
	 */
	function enable($guid, $recursive = true) {
	
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
	 * 	preload_owners => bool (false) If set to true, this function will preload
	 * 					  all the owners of the returned entities resulting in better
	 * 					  performance if those owners need to be displayed
	 *
	 *  preload_containers => bool (false) If set to true, this function will preload
	 * 					      all the containers of the returned entities resulting in better
	 * 					      performance if those containers need to be displayed
	 *
	 *
	 * 	callback => string A callback function to pass each row through
	 *
	 * 	distinct => bool (true) If set to false, Elgg will drop the DISTINCT clause from
	 *				the MySQL query, which will improve performance in some situations.
	 *				Avoid setting this option without a full understanding of the underlying
	 *				SQL query Elgg creates.
	 *
	 * @return mixed If count, int. If not count, array. false on errors.
	 * @see elgg_get_entities_from_metadata()
	 * @see elgg_get_entities_from_relationship()
	 * @see elgg_get_entities_from_access_id()
	 * @see elgg_get_entities_from_annotations()
	 * @see elgg_list_entities()
	 */
	function getEntities(array $options = array()) {
		
	
		$defaults = array(
			'types'					=>	ELGG_ENTITIES_ANY_VALUE,
			'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
			'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,
	
			'guids'					=>	ELGG_ENTITIES_ANY_VALUE,
			'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
			'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
			'site_guids'			=>	$this->CONFIG->site_guid,
	
			'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
			'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
			'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
			'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
	
			'reverse_order_by'		=>	false,
			'order_by' 				=>	'e.time_created desc',
			'group_by'				=>	ELGG_ENTITIES_ANY_VALUE,
			'limit'					=>	_elgg_services()->config->get('default_limit'),
			'offset'				=>	0,
			'count'					=>	false,
			'selects'				=>	array(),
			'wheres'				=>	array(),
			'joins'					=>	array(),
	
			'preload_owners'		=> false,
			'preload_containers'	=> false,
			'callback'				=> 'entity_row_to_elggstar',
			'distinct'				=> true,
	
			// private API
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

		$options = $this->autoJoinTables($options);

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
			$distinct = $options['distinct'] ? "DISTINCT" : "";
			$query = "SELECT $distinct e.*{$selects} FROM {$this->CONFIG->dbprefix}entities e ";
		} else {
			// note: when DISTINCT unneeded, it's slightly faster to compute COUNT(*) than GUIDs
			$count_expr = $options['distinct'] ? "DISTINCT e.guid" : "*";
			$query = "SELECT COUNT($count_expr) as total FROM {$this->CONFIG->dbprefix}entities e ";
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

		if ($options['count']) {
			$total = _elgg_services()->db->getDataRow($query);
			return (int)$total->total;
		}

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
			$results = _elgg_fetch_entities_from_sql($query, $options['__ElggBatch']);
		} else {
			$results = _elgg_services()->db->getData($query, $options['callback']);
		}

		if (!$results) {
			// no results, no preloading
			return $results;
		}

		// populate entity and metadata caches, and prepare $entities for preloader
		$guids = array();
		foreach ($results as $item) {
			// A custom callback could result in items that aren't \ElggEntity's, so check for them
			if ($item instanceof \ElggEntity) {
				_elgg_cache_entity($item);
				// plugins usually have only settings
				if (!$item instanceof \ElggPlugin) {
					$guids[] = $item->guid;
				}
			}
		}
		// @todo Without this, recursive delete fails. See #4568
		reset($results);

		if ($guids) {
			// there were entities in the result set, preload metadata for them
			_elgg_services()->metadataCache->populateFromEntities($guids);
		}

		if (count($results) > 1) {
			$props_to_preload = [];
			if ($options['preload_owners']) {
				$props_to_preload[] = 'owner_guid';
			}
			if ($options['preload_containers']) {
				$props_to_preload[] = 'container_guid';
			}
			if ($props_to_preload) {
				// note, ElggEntityPreloaderIntegrationTest assumes it can swap out
				// the preloader after boot. If you inject this component at construction
				// time that unit test will break. :/
				_elgg_services()->entityPreloader->preload($results, $props_to_preload);
			}
		}

		return $results;
	}

	/**
	 * Decorate getEntities() options in order to auto-join secondary tables where it's
	 * safe to do so.
	 *
	 * @param array $options Options array in getEntities() after normalization
	 * @return array
	 */
	protected function autoJoinTables(array $options) {
		// we must be careful that the query doesn't specify any options that may join
		// tables or change the selected columns
		if (!is_array($options['types'])
				|| count($options['types']) !== 1
				|| !empty($options['selects'])
				|| !empty($options['wheres'])
				|| !empty($options['joins'])
				|| $options['callback'] !== 'entity_row_to_elggstar'
				|| $options['count']) {
			// Too dangerous to auto-join
			return $options;
		}

		$join_types = [
			// Each class must have a static getExternalAttributes() : array
			'object' => 'ElggObject',
			'user' => 'ElggUser',
			'group' => 'ElggGroup',
			'site' => 'ElggSite',
		];

		// We use reset() because $options['types'] may not have a numeric key
		$type = reset($options['types']);
		if (empty($join_types[$type])) {
			return $options;
		}

		// Get the columns we'll need to select. We can't use st.* because the order_by
		// clause may reference "guid", which MySQL will complain about being ambiguous
		if (!is_callable([$join_types[$type], 'getExternalAttributes'])) {
			// for some reason can't get external attributes.
			return $options;
		}

		$attributes = $join_types[$type]::getExternalAttributes();
		foreach (array_keys($attributes) as $col) {
			$options['selects'][] = "st.$col";
		}

		// join the secondary table
		$options['joins'][] = "JOIN {$this->CONFIG->dbprefix}{$type}s_entity st ON (e.guid = st.guid)";

		return $options;
	}
	
	/**
	 * Return entities from an SQL query generated by elgg_get_entities.
	 *
	 * @param string    $sql
	 * @param \ElggBatch $batch
	 * @return \ElggEntity[]
	 *
	 * @access private
	 * @throws \LogicException
	 */
	function fetchFromSql($sql, \ElggBatch $batch = null) {
		static $plugin_subtype;
		if (null === $plugin_subtype) {
			$plugin_subtype = get_subtype_id('object', 'plugin');
		}
	
		// Keys are types, values are columns that, if present, suggest that the secondary
		// table is already JOINed. Note it's OK if guess incorrectly because entity load()
		// will fetch any missing attributes.
		$types_to_optimize = array(
			'object' => 'title',
			'user' => 'password',
			'group' => 'name',
			'site' => 'url',
		);
	
		$rows = _elgg_services()->db->getData($sql);
	
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
				throw new \LogicException('Entity row missing guid or type');
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
			$dbprefix = _elgg_services()->config->get('dbprefix');
	
			foreach ($lookup_types as $type => $guids) {
				$set = "(" . implode(',', $guids) . ")";
				$sql = "SELECT * FROM {$dbprefix}{$type}s_entity WHERE guid IN $set";
				$secondary_rows = _elgg_services()->db->getData($sql);
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
			if ($row instanceof \ElggEntity) {
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
	 * @access private
	 */
	function getEntityTypeSubtypeWhereSql($table, $types, $subtypes, $pairs) {
		// subtype depends upon type.
		if ($subtypes && !$types) {
			_elgg_services()->logger->warn("Cannot set subtypes without type.");
			return false;
		}
	
		// short circuit if nothing is requested
		if (!$types && !$subtypes && !$pairs) {
			return '';
		}
	
		// these are the only valid types for entities in elgg
		$valid_types = _elgg_services()->config->get('entity_types');
	
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
								_elgg_services()->logger->notice("Type-subtype '$type:$subtype' does not exist!");
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
							_elgg_services()->logger->notice("Type-subtype '$paired_type:$paired_subtype' does not exist!");
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
	 * @access private
	 */
	function getGuidBasedWhereSql($column, $guids) {
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
	 * @access private
	 */
	function getEntityTimeWhereSql($table, $time_created_upper = null,
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
	 * @throws InvalidArgumentException
	 * @todo Does not support ordering by attributes or using an attribute pair shortcut like this ('title' => 'foo')
	 */
	function getEntitiesFromAttributes(array $options = array()) {
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
	 * @access private
	 * @throws InvalidArgumentException
	 */
	function getEntityAttributeWhereSql(array $options = array()) {
	
		if (!isset($options['types'])) {
			throw new \InvalidArgumentException("The entity type must be defined for elgg_get_entities_from_attributes()");
		}
	
		if (is_array($options['types']) && count($options['types']) !== 1) {
			throw new \InvalidArgumentException("Only one type can be passed to elgg_get_entities_from_attributes()");
		}
	
		// type can be passed as string or array
		$type = $options['types'];
		if (is_array($type)) {
			$type = $type[0];
		}
	
		// @todo the types should be defined somewhere (as constant on \ElggEntity?)
		if (!in_array($type, array('group', 'object', 'site', 'user'))) {
			throw new \InvalidArgumentException("Invalid type '$type' passed to elgg_get_entities_from_attributes()");
		}
	
		
		$type_table = "{$this->CONFIG->dbprefix}{$type}s_entity";
	
		$return = array(
			'joins' => array(),
			'wheres' => array(),
		);
	
		// short circuit if nothing requested
		if ($options['attribute_name_value_pairs'] == ELGG_ENTITIES_ANY_VALUE) {
			return $return;
		}
	
		if (!is_array($options['attribute_name_value_pairs'])) {
			throw new \InvalidArgumentException("attribute_name_value_pairs must be an array for elgg_get_entities_from_attributes()");
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
	function getDates($type = '', $subtype = '', $container_guid = 0, $site_guid = 0,
			$order_by = 'time_created') {
	
		
	
		$site_guid = (int) $site_guid;
		if ($site_guid == 0) {
			$site_guid = $this->CONFIG->site_guid;
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
			FROM {$this->CONFIG->dbprefix}entities where ";
	
		foreach ($where as $w) {
			$sql .= " $w and ";
		}
	
		$sql .= "1=1 ORDER BY $order_by";
		if ($result = _elgg_services()->db->getData($sql)) {
			$endresult = array();
			foreach ($result as $res) {
				$endresult[] = $res->yearmonth;
			}
			return $endresult;
		}
		return false;
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
	function updateLastAction($guid, $posted = null) {
		
		$guid = (int)$guid;
		$posted = (int)$posted;
	
		if (!$posted) {
			$posted = time();
		}
	
		if ($guid) {
			//now add to the river updated table
			$query = "UPDATE {$this->CONFIG->dbprefix}entities SET last_action = {$posted} WHERE guid = {$guid}";
			$result = _elgg_services()->db->updateData($query);
			if ($result) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
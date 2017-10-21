<?php

namespace Elgg\Database;

use ClassException;
use Elgg\Cache\EntityCache;
use Elgg\Cache\MetadataCache;
use Elgg\Config as Conf;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\EntityTable\UserFetchFailureException;
use Elgg\EntityPreloader;
use Elgg\EventsService;
use Elgg\I18n\Translator;
use Elgg\Logger;
use ElggBatch;
use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggPlugin;
use ElggSession;
use ElggSite;
use ElggUser;
use IncompleteEntityException;
use InstallationException;
use InvalidArgumentException;
use LogicException;
use stdClass;

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

	use \Elgg\TimeUsing;

	/**
	 * @var Conf
	 */
	protected $config;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var array
	 */
	protected $entity_classes;

	/**
	 * @var EntityCache
	 */
	protected $entity_cache;

	/**
	 * @var EntityPreloader
	 */
	protected $entity_preloader;

	/**
	 * @var MetadataCache
	 */
	protected $metadata_cache;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor
	 *
	 * @param Conf          $config         Config
	 * @param Database      $db             Database
	 * @param EntityCache   $entity_cache   Entity cache
	 * @param MetadataCache $metadata_cache Metadata cache
	 * @param EventsService $events         Events service
	 * @param ElggSession   $session        Session
	 * @param Translator    $translator     Translator
	 * @param Logger        $logger         Logger
	 */
	public function __construct(
		Conf $config,
		Database $db,
		EntityCache $entity_cache,
		MetadataCache $metadata_cache,
		EventsService $events,
		ElggSession $session,
		Translator $translator,
		Logger $logger
	) {
		$this->config = $config;
		$this->db = $db;
		$this->table = $this->db->prefix . 'entities';
		$this->entity_cache = $entity_cache;
		$this->metadata_cache = $metadata_cache;
		$this->events = $events;
		$this->session = $session;
		$this->translator = $translator;
		$this->logger = $logger;
	}

	/**
	 * Sets class constructor name for entities with given type and subtype
	 *
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @param string $class   Entity class
	 *
	 * @return void
	 * @throws \InvalidParameterException
	 */
	public function setEntityClass($type, $subtype, $class = '') {
		if (!in_array($type, Config::getEntityTypes())) {
			throw new \InvalidParameterException("$type is not a valid entity type");
		}

		$this->entity_classes[$type][$subtype] = $class;
	}

	/**
	 * Returns class name registered as a constructor for a given type and subtype
	 *
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 *
	 * @return string
	 */
	public function getEntityClass($type, $subtype) {
		if (isset($this->entity_classes[$type][$subtype])) {
			return $this->entity_classes[$type][$subtype];
		}

		return '';
	}

	/**
	 * Returns a database row from the entities table.
	 *
	 * @see entity_row_to_elggstar()
	 *
	 * @tip Use get_entity() to return the fully loaded entity.
	 *
	 * @warning This will only return results if a) it exists, b) you have access to it.
	 * see {@link _elgg_get_access_where_sql()}.
	 *
	 * @param int $guid      The GUID of the object to extract
	 * @param int $user_guid GUID of the user accessing the row
	 *                       Defaults to logged in user if null
	 *                       Builds an access query for a logged out user if 0
	 * @return stdClass|false
	 * @access private
	 */
	public function getRow($guid, $user_guid = null) {

		if (!$guid) {
			return false;
		}

		$access = _elgg_get_access_where_sql([
			'table_alias' => '',
			'user_guid' => $user_guid,
		]);

		$sql = "SELECT * FROM {$this->db->prefix}entities
			WHERE guid = :guid AND $access";

		$params = [
			':guid' => (int) $guid,
		];

		return $this->db->getDataRow($sql, null, $params);
	}

	/**
	 * Adds a new row to the entity table
	 *
	 * @param stdClass $row        Entity base information
	 * @param array    $attributes All primary table attributes
	 *                             Used by database mock services to allow mocking
	 *                             entities that were instantiated using new keyword
	 *                             and calling ElggEntity::save()
	 * @return int|false
	 */
	public function insertRow(stdClass $row, array $attributes = []) {

		$sql = "
			INSERT INTO {$this->db->prefix}entities
			(type, subtype, owner_guid, container_guid, access_id, time_created, time_updated, last_action)
			VALUES
			(:type, :subtype, :owner_guid, :container_guid, :access_id, :time_created, :time_updated, :last_action)
		";

		return $this->db->insertData($sql, [
			':type' => $row->type,
			':subtype' => $row->subtype,
			':owner_guid' => $row->owner_guid,
			':container_guid' => $row->container_guid,
			':access_id' => $row->access_id,
			':time_created' => $row->time_created,
			':time_updated' => $row->time_updated,
			':last_action' => $row->last_action,
		]);
	}

	/**
	 * Update entity table row
	 *
	 * @param int      $guid Entity guid
	 * @param stdClass $row  Updated data
	 * @return int|false
	 */
	public function updateRow($guid, stdClass $row) {
		$sql = "
			UPDATE {$this->db->prefix}entities
			SET owner_guid = :owner_guid,
			    access_id = :access_id,
				container_guid = :container_guid,
				time_created = :time_created,
				time_updated = :time_updated
			WHERE guid = :guid
		";

		$params = [
			':owner_guid' => $row->owner_guid,
			':access_id' => $row->access_id,
			':container_guid' => $row->container_guid,
			':time_created' => $row->time_created,
			':time_updated' => $row->time_updated,
			':guid' => $guid,
		];

		return $this->db->updateData($sql, false, $params);
	}

	/**
	 * Create an Elgg* object from a given entity row.
	 *
	 * Handles loading all tables into the correct class.
	 *
	 * @see    get_entity_as_row()
	 * @see    get_entity()
	 *
	 * @access private
	 *
	 * @param stdClass $row The row of the entry in the entities table.
	 * @return ElggEntity|false
	 * @throws ClassException
	 * @throws InstallationException
	 */
	public function rowToElggStar($row) {
		if (!$row instanceof stdClass) {
			return $row;
		}

		if (!isset($row->guid) || !isset($row->subtype)) {
			return $row;
		}

		$class_name = $this->getEntityClass($row->type, $row->subtype);
		if ($class_name && !class_exists($class_name)) {
			$this->logger->error("Class '$class_name' was not found, missing plugin?");
			$class_name = '';
		}

		if (!$class_name) {
			$map = [
				'object' => ElggObject::class,
				'user' => ElggUser::class,
				'group' => ElggGroup::class,
				'site' => ElggSite::class,
			];

			if (isset($map[$row->type])) {
				$class_name = $map[$row->type];
			} else {
				throw new InstallationException("Entity type {$row->type} is not supported.");
			}
		}

		$entity = new $class_name($row);
		if (!$entity instanceof ElggEntity) {
			throw new ClassException("$class_name must extend " . ElggEntity::class);
		}

		return $entity;
	}

	/**
	 * Get an entity from the in-memory or memcache caches
	 *
	 * @param int $guid GUID
	 *
	 * @return \ElggEntity|false
	 */
	protected function getFromCache($guid) {
		$entity = $this->entity_cache->get($guid);
		if ($entity) {
			return $entity;
		}

		$memcache = _elgg_get_memcache('new_entity_cache');
		$entity = $memcache->load($guid);
		if (!$entity instanceof ElggEntity) {
			return false;
		}

		// Validate accessibility if from memcache
		if (!elgg_get_ignore_access() && !has_access_to_entity($entity)) {
			return false;
		}

		$this->entity_cache->set($entity);
		return $entity;
	}

	/**
	 * Loads and returns an entity object from a guid.
	 *
	 * @param int    $guid The GUID of the entity
	 * @param string $type The type of the entity. If given, even an existing entity with the given GUID
	 *                     will not be returned unless its type matches.
	 *
	 * @return ElggEntity|stdClass|false The correct Elgg or custom object based upon entity type and subtype
	 * @throws ClassException
	 * @throws InstallationException
	 */
	public function get($guid, $type = '') {
		// We could also use: if (!(int) $guid) { return false },
		// but that evaluates to a false positive for $guid = true.
		// This is a bit slower, but more thorough.
		if (!is_numeric($guid) || $guid === 0 || $guid === '0') {
			return false;
		}

		$guid = (int) $guid;

		$entity = $this->getFromCache($guid);
		if ($entity && (!$type || elgg_instanceof($entity, $type))) {
			return $entity;
		}

		$row = $this->getRow($guid);
		if (!$row) {
			return false;
		}

		if ($type && $row->type != $type) {
			return false;
		}

		$entity = $this->rowToElggStar($row);

		if ($entity instanceof ElggEntity) {
			$entity->storeInPersistedCache(_elgg_get_memcache('new_entity_cache'));
		}

		return $entity;
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
	 * @return bool
	 */
	public function exists($guid) {

		// need to ignore access and show hidden entities to check existence
		$ia = $this->session->setIgnoreAccess(true);
		$show_hidden = access_show_hidden_entities(true);

		$result = $this->getRow($guid);

		$this->session->setIgnoreAccess($ia);
		access_show_hidden_entities($show_hidden);

		return !empty($result);
	}

	/**
	 * Enable an entity.
	 *
	 * @param int  $guid      GUID of entity to enable
	 * @param bool $recursive Recursively enable all entities disabled with the entity?
	 * @return bool
	 */
	public function enable($guid, $recursive = true) {

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
	 * @see elgg_get_entities_from_metadata()
	 * @see elgg_get_entities_from_relationship()
	 * @see elgg_get_entities_from_access_id()
	 * @see elgg_get_entities_from_annotations()
	 * @see elgg_list_entities()
	 *
	 * @param array $options Array in format:
	 *
	 * 	types => null|STR entity type (type IN ('type1', 'type2')
	 *           Joined with subtypes by AND. See below)
	 *
	 * 	subtypes => null|STR entity subtype (SQL: subtype IN ('subtype1', 'subtype2))
	 *              Use ELGG_ENTITIES_ANY_VALUE to match any subtype.
	 *              Note that all falsey values will be treated as ELGG_ENTITIES_ANY_VALUE,
	 *              all other values will be cast to string and searched for literally.
	 *              All values within a non-empty subtypes array will be cast to string and searched for literally
	 *
	 * 	type_subtype_pairs => null|ARR (array('type' => 'subtype'))
	 *                        array(
	 *                            'object' => array('blog', 'file'), // All objects with subtype of 'blog' or 'file'
	 *                            'user' => ELGG_ENTITY_ANY_VALUE, // All users irrespective of subtype
	 *                        );
	 *                       Note that all falsey subtype values will be treated as ELGG_ENTITIES_ANY_VALUE,
	 *                       all other values will be cast to string and searched for literally.
	 *                       All values within a non-empty subtypes array will be cast to string and searched for literally
	 *
	 * 	guids => null|ARR Array of entity guids
	 *
	 * 	owner_guids => null|ARR Array of owner guids
	 *
	 * 	container_guids => null|ARR Array of container_guids
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
	 * 				the MySQL query, which will improve performance in some situations.
	 * 				Avoid setting this option without a full understanding of the underlying
	 * 				SQL query Elgg creates.
	 *
	 *  batch => bool (false) If set to true, an Elgg\BatchResult object will be returned instead of an array.
	 *           Since 2.3
	 *
	 *  batch_inc_offset => bool (true) If "batch" is used, this tells the batch to increment the offset
	 *                      on each fetch. This must be set to false if you delete the batched results.
	 *
	 *  batch_size => int (25) If "batch" is used, this is the number of entities/rows to pull in before
	 *                requesting more.
	 *
	 * @return \ElggEntity[]|int|mixed If count, int. Otherwise an array or an Elgg\BatchResult. false on errors.
	 *
	 * @see elgg_get_entities_from_metadata()
	 * @see elgg_get_entities_from_relationship()
	 * @see elgg_get_entities_from_access_id()
	 * @see elgg_get_entities_from_annotations()
	 * @see elgg_list_entities()
	 */
	public function getEntities(array $options = []) {
		_elgg_check_unsupported_site_guid($options);

		$defaults = [
			'types'                 => ELGG_ENTITIES_ANY_VALUE,
			'subtypes'              => ELGG_ENTITIES_ANY_VALUE,
			'type_subtype_pairs'    => ELGG_ENTITIES_ANY_VALUE,

			'guids'                 => ELGG_ENTITIES_ANY_VALUE,
			'owner_guids'           => ELGG_ENTITIES_ANY_VALUE,
			'container_guids'       => ELGG_ENTITIES_ANY_VALUE,

			'modified_time_lower'   => ELGG_ENTITIES_ANY_VALUE,
			'modified_time_upper'   => ELGG_ENTITIES_ANY_VALUE,
			'created_time_lower'    => ELGG_ENTITIES_ANY_VALUE,
			'created_time_upper'    => ELGG_ENTITIES_ANY_VALUE,

			'reverse_order_by'      => false,
			'order_by'              => 'e.time_created desc',
			'group_by'              => ELGG_ENTITIES_ANY_VALUE,
			'limit'                 => $this->config->default_limit,
			'offset'                => 0,
			'count'                 => false,
			'selects'               => [],
			'wheres'                => [],
			'joins'                 => [],

			'preload_owners'        => false,
			'preload_containers'    => false,
			'callback'              => 'entity_row_to_elggstar',
			'distinct'              => true,

			'batch'                 => false,
			'batch_inc_offset'      => true,
			'batch_size'            => 25,

			// private API
			'__ElggBatch'           => null,
		];

		$options = array_merge($defaults, $options);

		if ($options['batch'] && !$options['count']) {
			$batch_size = $options['batch_size'];
			$batch_inc_offset = $options['batch_inc_offset'];

			// clean batch keys from $options.
			unset($options['batch'], $options['batch_size'], $options['batch_inc_offset']);

			return new \ElggBatch([$this, 'getEntities'], $options, null, $batch_size, $batch_inc_offset);
		}

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

		$singulars = ['type', 'subtype', 'guid', 'owner_guid', 'container_guid'];
		$options = _elgg_normalize_plural_options_array($options, $singulars);

		// evaluate where clauses
		if (!is_array($options['wheres'])) {
			$options['wheres'] = [$options['wheres']];
		}

		$wheres = $options['wheres'];

		$wheres[] = $this->getEntityTypeSubtypeWhereSql('e', $options['types'],
			$options['subtypes'], $options['type_subtype_pairs']);

		$wheres[] = $this->getGuidBasedWhereSql('e.guid', $options['guids']);
		$wheres[] = $this->getGuidBasedWhereSql('e.owner_guid', $options['owner_guids']);
		$wheres[] = $this->getGuidBasedWhereSql('e.container_guid', $options['container_guids']);

		$wheres[] = $this->getEntityTimeWhereSql('e', $options['created_time_upper'],
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
			$options['joins'] = [$options['joins']];
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
			$query = "SELECT $distinct e.*{$selects} FROM {$this->db->prefix}entities e ";
		} else {
			// note: when DISTINCT unneeded, it's slightly faster to compute COUNT(*) than GUIDs
			$count_expr = $options['distinct'] ? "DISTINCT e.guid" : "*";
			$query = "SELECT COUNT($count_expr) as total FROM {$this->db->prefix}entities e ";
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
			$total = $this->db->getDataRow($query);
			return (int) $total->total;
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
			$results = $this->fetchFromSql($query, $options['__ElggBatch']);
		} else {
			$results = $this->db->getData($query, $options['callback']);
		}

		if (!$results) {
			// no results, no preloading
			return $results;
		}

		// populate entity and metadata caches, and prepare $entities for preloader
		$guids = [];
		foreach ($results as $item) {
			// A custom callback could result in items that aren't \ElggEntity's, so check for them
			if ($item instanceof ElggEntity) {
				$this->entity_cache->set($item);
				// plugins usually have only settings
				if (!$item instanceof ElggPlugin) {
					$guids[] = $item->guid;
				}
			}
		}
		// @todo Without this, recursive delete fails. See #4568
		reset($results);

		if ($guids) {
			// there were entities in the result set, preload metadata for them
			$this->metadata_cache->populateFromEntities($guids);
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
	 * Return entities from an SQL query generated by elgg_get_entities.
	 *
	 * @access private
	 *
	 * @param string    $sql
	 * @param ElggBatch $batch
	 * @return ElggEntity[]
	 * @throws LogicException
	 */
	public function fetchFromSql($sql, \ElggBatch $batch = null) {

		$rows = $this->db->getData($sql);

		// Second pass to finish conversion
		foreach ($rows as $i => $row) {
			if ($row instanceof ElggEntity) {
				continue;
			} else {
				try {
					$rows[$i] = $this->rowToElggStar($row);
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
	public function getEntityTypeSubtypeWhereSql($table, $types, $subtypes, $pairs) {
		// short circuit if nothing is requested
		if (!$types && !$subtypes && !$pairs) {
			return '';
		}

		// these are the only valid types for entities in elgg
		$valid_types = \Elgg\Config::getEntityTypes();

		$or_clauses = [];

		if (is_array($pairs)) {
			foreach ($pairs as $paired_type => $paired_subtypes) {
				if (!empty($paired_subtypes)) {
					$paired_subtypes = (array) $paired_subtypes;
					$paired_subtypes = array_map(function ($el) {
						$el = trim((string) $el, "\"\'");

						return "'$el'";
					}, $paired_subtypes);

					$paired_subtypes_in = implode(',', $paired_subtypes);

					$or_clauses[] = "
					(
						{$table}.type = '$paired_type'
						AND {$table}.subtype IN ($paired_subtypes_in)
					)
					";
				} else {
					$or_clauses[] = "
						({$table}.type = '$paired_type')
					";
				}
			}
		} else {
			$types = (array) $types;

			foreach ($types as $type) {
				if (!empty($subtypes)) {
					$subtypes = (array) $subtypes;
					$subtypes = array_map(function ($el) {
						$el = trim((string) $el, "\"\'");

						return "'$el'";
					}, $subtypes);

					$subtypes_in = implode(',', $subtypes);

					$or_clauses[] = "
						(
							{$table}.type = '$type' 
							AND {$table}.subtype IN ($subtypes_in)
						)";
				} else {
					$or_clauses[] = "
						({$table}.type = '$type')
					";
				}
			}
		}

		if (empty($or_clauses)) {
			return '';
		}

		return "(" . implode(' OR ', $or_clauses) . ")";
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
	public function getGuidBasedWhereSql($column, $guids) {
		// short circuit if nothing requested
		// 0 is a valid guid
		if (!$guids && $guids !== 0) {
			return '';
		}

		// normalize and sanitise owners
		if (!is_array($guids)) {
			$guids = [$guids];
		}

		$guids_sanitized = [];
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
	public function getEntityTimeWhereSql($table, $time_created_upper = null,
	$time_created_lower = null, $time_updated_upper = null, $time_updated_lower = null) {

		$wheres = [];

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
	 * @param string $order_by       Order_by SQL order by clause
	 *
	 * @return array|false Either an array months as YYYYMM, or false on failure
	 */
	public function getDates($type = '', $subtype = '', $container_guid = 0, $order_by = 'time_created') {

		$where = [];

		if ($type) {
			$type = sanitise_string($type);
			$where[] = "type='$type'";
		}

		if (is_array($subtype)) {
			$or_clauses = [];
			if (sizeof($subtype)) {
				foreach ($subtype as $typekey => $subtypearray) {
					foreach ($subtypearray as $subtypeval) {
						$subtype_str = sanitize_string($subtypeval);
						$type_str = sanitize_string($typekey);
						$or_clauses = "(type = '{$type_str}' and subtype = '{$subtype_str}')";
					}
				}
			}
			if (!empty($or_clauses)) {
				$where[] = '(' . implode(' OR ', $or_clauses) . ')';
			}
		} else {
			if ($subtype) {
				$where[] = "subtype='$subtype'";
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

		$where[] = _elgg_get_access_where_sql(['table_alias' => '']);

		$sql = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(time_created)) AS yearmonth
			FROM {$this->db->prefix}entities where ";

		foreach ($where as $w) {
			$sql .= " $w and ";
		}

		$sql .= "1=1 ORDER BY $order_by";
		if ($result = $this->db->getData($sql)) {
			$endresult = [];
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
	 * @param ElggEntity $entity Entity annotation|relationship action carried out on
	 * @param int        $posted Timestamp of last action
	 * @return int
	 * @access private
	 */
	public function updateLastAction(ElggEntity $entity, $posted = null) {

		if (!$posted) {
			$posted = $this->getCurrentTime()->getTimestamp();
		}

		$query = "
			UPDATE {$this->db->prefix}entities
			SET last_action = :last_action
			WHERE guid = :guid
		";

		$params = [
			':last_action' => (int) $posted,
			':guid' => (int) $entity->guid,
		];

		$this->db->updateData($query, true, $params);

		return (int) $posted;
	}

	/**
	 * Get a user by GUID even if the entity is hidden or disabled
	 *
	 * @param int $guid User GUID. Default is logged in user
	 *
	 * @return ElggUser|false
	 * @throws UserFetchFailureException
	 * @access private
	 */
	public function getUserForPermissionsCheck($guid = 0) {
		if (!$guid) {
			return $this->session->getLoggedInUser();
		}

		// need to ignore access and show hidden entities for potential hidden/disabled users
		$ia = $this->session->setIgnoreAccess(true);
		$show_hidden = access_show_hidden_entities(true);

		$user = $this->get($guid, 'user');

		$this->session->setIgnoreAccess($ia);
		access_show_hidden_entities($show_hidden);

		if (!$user) {
			// requested to check access for a specific user_guid, but there is no user entity, so the caller
			// should cancel the check and return false
			$message = $this->translator->translate('UserFetchFailureException', [$guid]);
			// $this->logger->warn($message);

			throw new UserFetchFailureException($message);
		}

		return $user;
	}

	/**
	 * Disables all entities owned and contained by a user (or another entity)
	 *
	 * @param int $owner_guid The owner GUID
	 * @return bool
	 */
	public function disableEntities($owner_guid) {
		$entity = get_entity($owner_guid);
		if (!$entity || !$entity->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('disable', $entity->type, $entity)) {
			return false;
		}

		$query = "
			UPDATE {$this->table}entities
			SET enabled='no'
			WHERE owner_guid = :owner_guid
			OR container_guid = :owner_guid";

		$params = [
			':owner_guid' => (int) $owner_guid,
		];

		_elgg_invalidate_cache_for_entity($entity->guid);
		_elgg_invalidate_memcache_for_entity($entity->guid);

		if ($this->db->updateData($query, true, $params)) {
			return true;
		}

		return false;
	}

}

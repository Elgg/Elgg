<?php

namespace Elgg\Database;

use ClassException;
use Elgg\Cache\EntityCache;
use Elgg\Cache\MetadataCache;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\OrderByClause;
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
	 * @var Config
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
	 * @param Config        $config         Config
	 * @param Database      $db             Database
	 * @param EntityCache   $entity_cache   Entity cache
	 * @param MetadataCache $metadata_cache Metadata cache
	 * @param EventsService $events         Events service
	 * @param ElggSession   $session        Session
	 * @param Translator    $translator     Translator
	 * @param Logger        $logger         Logger
	 */
	public function __construct(
		Config $config,
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

		$where = new EntityWhereClause();
		$where->guids = $guid;
		$where->viewer_guid = $user_guid;

		$select = Select::fromTable('entities');
		$select->select('*');
		$select->addClause($where);

		return $this->db->getDataRow($select);
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
	 * @see get_entity_as_row()
	 * @see get_entity()
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
	 * @param QueryBuilder $query   Query
	 * @param array        $options Options
	 * @return \stdClass[]|ElggEntity[]
	 */
	public function fetch(QueryBuilder $query, array $options = []) {
		if ($options['callback'] === 'entity_row_to_elggstar') {
			$results = $this->fetchFromSql($query, $options['__ElggBatch']);
		} else {
			$results = $this->db->getData($query, $options['callback']);
		}

		if (!$results) {
			// no results, no preloading
			return [];
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
	 * @param string    $sql   the SQL-query
	 * @param ElggBatch $batch the calling batch (default: null)
	 *
	 * @return ElggEntity[]
	 * @throws LogicException
	 *
	 * @access private
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
	 * Returns a list of months in which entities were updated or created.
	 *
	 * @tip Use this to generate a list of archives by month for when entities were added or updated.
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

		$options = [
			'types' => $type,
			'subtypes' => $subtype,
			'container_guids' => $container_guid,
			'callback' => false,
			'order_by' => [
				new OrderByClause($order_by),
			],
		];

		$options = new QueryOptions($options);

		$qb = Select::fromTable('entities');
		$qb->select("DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(time_created)) AS yearmonth");
		$qb->addClause(EntityWhereClause::factory($options));

		$results  = _elgg_services()->db->getData($qb);
		if (empty($results)) {
			return false;
		}

		return array_map(function($e) {
			return $e->yearmonth;
		}, $results);
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
		if ($user) {
			_elgg_services()->metadataCache->populateFromEntities([$user->guid]);
		}

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

<?php

namespace Elgg\Database;

use ClassException;
use DatabaseException;
use Elgg\Cache\EntityCache;
use Elgg\Cache\MetadataCache;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\EntityTable\UserFetchFailureException;
use Elgg\EntityPreloader;
use Elgg\EventsService;
use Elgg\I18n\Translator;
use Elgg\Logger;
use ElggBatch;
use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggSession;
use ElggSite;
use ElggUser;
use InvalidParameterException;
use Psr\Log\LoggerInterface;
use stdClass;
use Elgg\Cache\PrivateSettingsCache;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access     private
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
	 * @var PrivateSettingsCache
	 */
	protected $private_settings_cache;

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
	 * @param Config               $config                 Config
	 * @param Database             $db                     Database
	 * @param EntityCache          $entity_cache           Entity cache
	 * @param MetadataCache        $metadata_cache         Metadata cache
	 * @param PrivateSettingsCache $private_settings_cache Private Settings cache
	 * @param EventsService        $events                 Events service
	 * @param ElggSession          $session                Session
	 * @param Translator           $translator             Translator
	 * @param LoggerInterface      $logger                 Logger
	 */
	public function __construct(
		Config $config,
		Database $db,
		EntityCache $entity_cache,
		MetadataCache $metadata_cache,
		PrivateSettingsCache $private_settings_cache,
		EventsService $events,
		ElggSession $session,
		Translator $translator,
		LoggerInterface $logger
	) {
		$this->config = $config;
		$this->db = $db;
		$this->table = $this->db->prefix . 'entities';
		$this->entity_cache = $entity_cache;
		$this->metadata_cache = $metadata_cache;
		$this->private_settings_cache = $private_settings_cache;
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
	 * @throws InvalidParameterException
	 */
	public function setEntityClass($type, $subtype, $class = '') {
		if (!in_array($type, Config::getEntityTypes())) {
			throw new InvalidParameterException("$type is not a valid entity type");
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
	 * @see     entity_row_to_elggstar()
	 *
	 * @tip     Use get_entity() to return the fully loaded entity.
	 *
	 * @warning This will only return results if a) it exists, b) you have access to it.
	 * see {@link _elgg_get_access_where_sql()}.
	 *
	 * @param int $guid      The GUID of the object to extract
	 * @param int $user_guid GUID of the user accessing the row
	 *                       Defaults to logged in user if null
	 *                       Builds an access query for a logged out user if 0
	 *
	 * @return stdClass|false
	 * @access  private
	 */
	public function getRow($guid, $user_guid = null) {

		if (!$guid) {
			return false;
		}

		$where = new EntityWhereClause();
		$where->guids = (int) $guid;
		$where->viewer_guid = $user_guid;

		$select = Select::fromTable('entities', 'e');
		$select->select('e.*');
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
	 *
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
	 *
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
	 *
	 * @return ElggEntity|false
	 * @throws ClassException
	 * @throws InvalidParameterException
	 */
	public function rowToElggStar(stdClass $row) {
		if (!isset($row->guid) || !isset($row->subtype)) {
			return false;
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
				throw new InvalidParameterException("Entity type {$row->type} is not supported.");
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
	public function getFromCache($guid) {
		$entity = $this->entity_cache->load($guid);
		if ($entity) {
			return $entity;
		}

		$cache = _elgg_services()->dataCache->entities;
		$entity = $cache->load($guid);
		if (!$entity instanceof ElggEntity) {
			return false;
		}

		// Validate accessibility if from cache
		if (!elgg_get_ignore_access() && !has_access_to_entity($entity)) {
			return false;
		}

		$entity->cache(false);

		return $entity;
	}

	/**
	 * Invalidate cache for entity
	 *
	 * @param int $guid GUID
	 * @return void
	 */
	public function invalidateCache($guid) {
		$ia = $this->session->setIgnoreAccess(true);
		$ha = $this->session->getDisabledEntityVisibility();
		$this->session->setDisabledEntityVisibility(true);
		$entity = $this->get($guid);
		if ($entity) {
			$entity->invalidateCache();
		}
		$this->session->setDisabledEntityVisibility($ha);
		$this->session->setIgnoreAccess($ia);
	}

	/**
	 * Loads and returns an entity object from a guid.
	 *
	 * @param int    $guid    The GUID of the entity
	 * @param string $type    The type of the entity
	 *                        If given, even an existing entity with the given GUID
	 *                        will not be returned unless its type matches
	 * @param string $subtype The subtype of the entity
	 *                        If given, even an existing entity with the given GUID
	 *                        will not be returned unless its subtype matches
	 *
	 * @return ElggEntity|false The correct Elgg or custom object based upon entity type and subtype
	 * @throws ClassException
	 * @throws InvalidParameterException
	 */
	public function get($guid, $type = null, $subtype = null) {
		// We could also use: if (!(int) $guid) { return false },
		// but that evaluates to a false positive for $guid = true.
		// This is a bit slower, but more thorough.
		if (!is_numeric($guid) || $guid === 0 || $guid === '0') {
			return false;
		}

		$guid = (int) $guid;

		$entity = $this->getFromCache($guid);
		if ($entity && elgg_instanceof($entity, $type, $subtype)) {
			return $entity;
		}

		$row = $this->getRow($guid);
		if (!$row) {
			return false;
		}

		if ($type && $row->type != $type) {
			return false;
		}

		if ($subtype && $row->subtype !== $subtype) {
			return false;
		}

		$entity = $row;

		if ($entity instanceof \stdClass) {
			$entity = $this->rowToElggStar($entity);
		}

		$entity->cache();

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
	 *
	 * @return bool
	 */
	public function exists($guid) {

		// need to ignore access and show hidden entities to check existence
		$ia = $this->session->setIgnoreAccess(true);
		$show_hidden = $this->session->setDisabledEntityVisibility(true);

		$result = $this->getRow($guid);

		$this->session->setIgnoreAccess($ia);
		$this->session->setDisabledEntityVisibility($show_hidden);

		return !empty($result);
	}

	/**
	 * Enable an entity.
	 *
	 * @param int  $guid      GUID of entity to enable
	 * @param bool $recursive Recursively enable all entities disabled with the entity?
	 *
	 * @return bool
	 */
	public function enable($guid, $recursive = true) {

		// Override access only visible entities
		$old_access_status = $this->session->getDisabledEntityVisibility();
		$this->session->setDisabledEntityVisibility(true);

		$result = false;
		$entity = get_entity($guid);
		if ($entity) {
			$result = $entity->enable($recursive);
		}

		$this->session->setDisabledEntityVisibility($old_access_status);

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
	 *
	 * @return ElggEntity[]
	 * @throws DatabaseException
	 */
	public function fetch(QueryBuilder $query, array $options = []) {
		$results = $this->db->getData($query, $options['callback']);

		if (empty($results)) {
			return [];
		}

		$preload = array_filter($results, function ($e) {
			return $e instanceof ElggEntity;
		});
		/* @var $preload ElggEntity[] */

		$this->metadata_cache->populateFromEntities($preload);
		
		if (elgg_extract('preload_private_settings', $options, false)) {
			$this->private_settings_cache->populateFromEntities($preload);
		}
		
		$props_to_preload = [];
		if (elgg_extract('preload_owners', $options, false)) {
			$props_to_preload[] = 'owner_guid';
		}
		if (elgg_extract('preload_containers', $options, false)) {
			$props_to_preload[] = 'container_guid';
		}

		if ($props_to_preload) {
			_elgg_services()->entityPreloader->preload($preload, $props_to_preload);
		}

		return $results;
	}

	/**
	 * Update the last_action column in the entities table for $guid.
	 *
	 * @warning This is different to time_updated.  Time_updated is automatically set,
	 * while last_action is only set when explicitly called.
	 *
	 * @param ElggEntity $entity Entity annotation|relationship action carried out on
	 * @param int        $posted Timestamp of last action
	 *
	 * @return int
	 * @access  private
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
	 * @throws ClassException
	 * @throws InvalidParameterException
	 * @access private
	 */
	public function getUserForPermissionsCheck($guid = 0) {
		if (!$guid) {
			return $this->session->getLoggedInUser();
		}

		// need to ignore access and show hidden entities for potential hidden/disabled users
		$ia = $this->session->setIgnoreAccess(true);
		$show_hidden = $this->session->setDisabledEntityVisibility(true);

		$user = $this->get($guid, 'user');
		if ($user) {
			$this->metadata_cache->populateFromEntities([$user->guid]);
		}

		$this->session->setIgnoreAccess($ia);
		$this->session->setDisabledEntityVisibility($show_hidden);

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
	 * @param ElggEntity $entity Owner/container entity
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function disableEntities(ElggEntity $entity) {
		if (!$entity->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('disable', $entity->type, $entity)) {
			return false;
		}

		$qb = Update::table('entities');
		$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
			->where($qb->compare('owner_guid', '=', $entity->guid, ELGG_VALUE_INTEGER))
			->orWhere($qb->compare('container_guid', '=', $entity->guid, ELGG_VALUE_INTEGER));

		$this->db->updateData($qb, true);

		$entity->invalidateCache();

		return true;
	}

	/**
	 * Delete entity and all of its properties
	 *
	 * @param ElggEntity $entity    Entity
	 * @param bool       $recursive Delete all owned and contained entities
	 *
	 * @return bool
	 * @throws DatabaseException
	 */
	public function delete(\ElggEntity $entity, $recursive = true) {
		$guid = $entity->guid;
		if (!$guid) {
			return false;
		}

		if (!_elgg_services()->events->triggerBefore('delete', $entity->type, $entity)) {
			return false;
		}

		// now trigger an event to let others know this entity is about to be deleted
		// so they can prevent it or take their own actions
		if (!_elgg_services()->events->triggerDeprecated('delete', $entity->type, $entity)) {
			return false;
		}

		if ($entity instanceof ElggUser) {
			// ban to prevent using the site during delete
			$entity->ban();
		}

		if ($recursive) {
			$this->deleteRelatedEntities($entity);
		}

		$this->deleteEntityProperties($entity);

		$qb = Delete::fromTable('entities');
		$qb->where($qb->compare('guid', '=', $guid, ELGG_VALUE_INTEGER));

		$this->db->deleteData($qb);

		_elgg_services()->events->triggerAfter('delete', $entity->type, $entity);

		return true;
	}

	/**
	 * Deletes entities owned or contained by the entity being deletes
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	protected function deleteRelatedEntities(ElggEntity $entity) {
		// Temporarily overriding access controls
		$entity_disable_override = $this->session->getDisabledEntityVisibility();
		$this->session->setDisabledEntityVisibility(true);
		$ia = $this->session->setIgnoreAccess(true);

		$options = [
			'wheres' => function (QueryBuilder $qb, $main_alias) use ($entity) {
				$ors = $qb->merge([
					$qb->compare("{$main_alias}.owner_guid", '=', $entity->guid, ELGG_VALUE_GUID),
					$qb->compare("{$main_alias}.container_guid", '=', $entity->guid, ELGG_VALUE_GUID),
				], 'OR');

				return $qb->merge([
					$ors,
					$qb->compare("{$main_alias}.guid", 'neq', $entity->guid, ELGG_VALUE_GUID),
				]);
			},
			'limit' => false,
		];

		$batch = new ElggBatch('elgg_get_entities', $options);
		$batch->setIncrementOffset(false);

		/* @var $e \ElggEntity */
		foreach ($batch as $e) {
			$this->delete($e, true);
		}

		$this->session->setDisabledEntityVisibility($entity_disable_override);
		$this->session->setIgnoreAccess($ia);
	}

	/**
	 * Clear data from secondary tables
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return void
	 */
	protected function deleteEntityProperties(ElggEntity $entity) {

		$guid = $entity->guid;

		$entity_disable_override = $this->session->getDisabledEntityVisibility();
		$this->session->setDisabledEntityVisibility(true);
		$ia = $this->session->setIgnoreAccess(true);

		elgg_delete_river(['subject_guid' => $guid, 'limit' => false]);
		elgg_delete_river(['object_guid' => $guid, 'limit' => false]);
		elgg_delete_river(['target_guid' => $guid, 'limit' => false]);

		$entity->removeAllPrivateSettings();
		$entity->deleteOwnedAccessCollections();
		$entity->deleteAccessCollectionMemberships();
		$entity->deleteRelationships();
		$entity->deleteOwnedAnnotations();
		$entity->deleteAnnotations();
		$entity->deleteMetadata();

		$this->session->setDisabledEntityVisibility($entity_disable_override);
		$this->session->setIgnoreAccess($ia);

		$dir = new \Elgg\EntityDirLocator($guid);
		$file_path = _elgg_config()->dataroot . $dir;
		delete_directory($file_path);

	}
}

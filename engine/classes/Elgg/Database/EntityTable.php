<?php

namespace Elgg\Database;

use Elgg\Cache\EntityCache;
use Elgg\Cache\MetadataCache;
use Elgg\Cache\PrivateSettingsCache;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\EntityPreloader;
use Elgg\EventsService;
use Elgg\Exceptions\Database\UserFetchFailureException;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\ClassException;
use Elgg\I18n\Translator;
use Elgg\Traits\Loggable;
use Elgg\Traits\TimeUsing;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 *
 * @since 1.10.0
 */
class EntityTable {

	use Loggable;
	use TimeUsing;

	/**
	 * @var string name of the entities database table
	 */
	const TABLE_NAME = 'entities';
	
	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var Database
	 */
	protected $db;

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
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * Constructor
	 *
	 * @param Config               $config                 Config
	 * @param Database             $db                     Database
	 * @param EntityCache          $entity_cache           Entity cache
	 * @param MetadataCache        $metadata_cache         Metadata cache
	 * @param PrivateSettingsCache $private_settings_cache Private Settings cache
	 * @param EventsService        $events                 Events service
	 * @param \ElggSession         $session                Session
	 * @param Translator           $translator             Translator
	 */
	public function __construct(
		Config $config,
		Database $db,
		EntityCache $entity_cache,
		MetadataCache $metadata_cache,
		PrivateSettingsCache $private_settings_cache,
		EventsService $events,
		\ElggSession $session,
		Translator $translator
	) {
		$this->config = $config;
		$this->db = $db;
		$this->entity_cache = $entity_cache;
		$this->metadata_cache = $metadata_cache;
		$this->private_settings_cache = $private_settings_cache;
		$this->events = $events;
		$this->session = $session;
		$this->translator = $translator;
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
		if (!in_array($type, Config::ENTITY_TYPES)) {
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
	 * @return \stdClass|false
	 */
	public function getRow($guid, $user_guid = null) {

		if (!$guid) {
			return false;
		}

		$where = new EntityWhereClause();
		$where->guids = (int) $guid;
		$where->viewer_guid = $user_guid;

		$select = Select::fromTable(self::TABLE_NAME, 'e');
		$select->select('e.*');
		$select->addClause($where);

		return $this->db->getDataRow($select);
	}

	/**
	 * Adds a new row to the entity table
	 *
	 * @param \stdClass $row        Entity base information
	 * @param array     $attributes All primary table attributes
	 *                              Used by database mock services to allow mocking
	 *                              entities that were instantiated using new keyword
	 *                              and calling ElggEntity::save()
	 *
	 * @return int|false
	 */
	public function insertRow(\stdClass $row, array $attributes = []) {
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'type' => $insert->param($row->type, ELGG_VALUE_STRING),
			'subtype' => $insert->param($row->subtype, ELGG_VALUE_STRING),
			'owner_guid' => $insert->param($row->owner_guid, ELGG_VALUE_GUID),
			'container_guid' => $insert->param($row->container_guid, ELGG_VALUE_GUID),
			'access_id' => $insert->param($row->access_id, ELGG_VALUE_ID),
			'time_created' => $insert->param($row->time_created, ELGG_VALUE_TIMESTAMP),
			'time_updated' => $insert->param($row->time_updated, ELGG_VALUE_TIMESTAMP),
			'last_action' => $insert->param($row->last_action, ELGG_VALUE_TIMESTAMP),
		]);

		return $this->db->insertData($insert);
	}

	/**
	 * Update entity table row
	 *
	 * @param int       $guid Entity guid
	 * @param \stdClass $row  Updated data
	 *
	 * @return bool
	 */
	public function updateRow(int $guid, \stdClass $row) {
		$update = Update::table(self::TABLE_NAME);
		$update->set('owner_guid', $update->param($row->owner_guid, ELGG_VALUE_GUID))
			->set('container_guid', $update->param($row->container_guid, ELGG_VALUE_GUID))
			->set('access_id', $update->param($row->access_id, ELGG_VALUE_ID))
			->set('time_created', $update->param($row->time_created, ELGG_VALUE_TIMESTAMP))
			->set('time_updated', $update->param($row->time_updated, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $guid, ELGG_VALUE_GUID));

		return $this->db->updateData($update);
	}

	/**
	 * Create an Elgg* object from a given entity row.
	 *
	 * Handles loading all tables into the correct class.
	 *
	 * @see    get_entity_as_row()
	 * @see    get_entity()
	 *
	 * @param \stdClass $row The row of the entry in the entities table.
	 *
	 * @return \ElggEntity|false
	 * @throws ClassException
	 * @throws InvalidParameterException
	 */
	public function rowToElggStar(\stdClass $row) {
		if (!isset($row->guid) || !isset($row->subtype)) {
			return false;
		}

		$class_name = $this->getEntityClass($row->type, $row->subtype);
		if ($class_name && !class_exists($class_name)) {
			$this->getLogger()->error("Class '$class_name' was not found, missing plugin?");
			$class_name = '';
		}

		if (!$class_name) {
			$map = [
				'object' => \ElggObject::class,
				'user' => \ElggUser::class,
				'group' => \ElggGroup::class,
				'site' => \ElggSite::class,
			];

			if (isset($map[$row->type])) {
				$class_name = $map[$row->type];
			} else {
				throw new InvalidParameterException("Entity type {$row->type} is not supported.");
			}
		}

		$entity = new $class_name($row);
		if (!$entity instanceof \ElggEntity) {
			throw new ClassException("$class_name must extend " . \ElggEntity::class);
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
		if (!$entity instanceof \ElggEntity) {
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
	 * @return \ElggEntity|false The correct Elgg or custom object based upon entity type and subtype
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
		if (
			$entity instanceof \ElggEntity &&
			(!isset($type) || $entity->type === $type) &&
			(!isset($subtype) || $entity->subtype === $subtype)
		) {
			return $entity;
		}

		$row = $this->getRow($guid);
		if (!$row) {
			return false;
		}

		if (isset($type) && $row->type !== $type) {
			return false;
		}

		if (isset($subtype) && $row->subtype !== $subtype) {
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
	 * @return \ElggEntity[]
	 */
	public function fetch(QueryBuilder $query, array $options = []) {
		$results = $this->db->getData($query, $options['callback']);

		if (empty($results)) {
			return [];
		}

		/* @var $preload \ElggEntity[] */
		$preload = array_filter($results, function ($e) {
			return $e instanceof \ElggEntity;
		});
		
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
	 * @param \ElggEntity $entity Entity annotation|relationship action carried out on
	 * @param int         $posted Timestamp of last action
	 *
	 * @return int
	 */
	public function updateLastAction(\ElggEntity $entity, int $posted = null) {

		if ($posted === null) {
			$posted = $this->getCurrentTime()->getTimestamp();
		}

		$update = Update::table(self::TABLE_NAME);
		$update->set('last_action', $update->param($posted, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));

		$this->db->updateData($update);

		return (int) $posted;
	}

	/**
	 * Get a user by GUID even if the entity is hidden or disabled
	 *
	 * @param int $guid User GUID. Default is logged in user
	 *
	 * @return \ElggUser|false
	 * @throws UserFetchFailureException
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

			throw new UserFetchFailureException($message);
		}

		return $user;
	}

	/**
	 * Disables all entities owned and contained by a user (or another entity)
	 *
	 * @param \ElggEntity $entity Owner/container entity
	 *
	 * @return bool
	 */
	public function disableEntities(\ElggEntity $entity) {
		if (!$entity->canEdit()) {
			return false;
		}

		if (!$this->events->trigger('disable', $entity->type, $entity)) {
			return false;
		}

		$qb = Update::table(self::TABLE_NAME);
		$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
			->where($qb->compare('owner_guid', '=', $entity->guid, ELGG_VALUE_GUID))
			->orWhere($qb->compare('container_guid', '=', $entity->guid, ELGG_VALUE_GUID));

		$this->db->updateData($qb, true);

		$entity->invalidateCache();

		return true;
	}

	/**
	 * Delete entity and all of its properties
	 *
	 * @param \ElggEntity $entity    Entity
	 * @param bool        $recursive Delete all owned and contained entities
	 *
	 * @return bool
	 */
	public function delete(\ElggEntity $entity, $recursive = true) {
		$guid = $entity->guid;
		if (!$guid) {
			return false;
		}

		if (!$this->events->triggerBefore('delete', $entity->type, $entity)) {
			return false;
		}
		
		$this->events->trigger('delete', $entity->type, $entity);

		if ($entity instanceof \ElggUser) {
			// ban to prevent using the site during delete
			$entity->ban();
		}

		if ($recursive) {
			$this->deleteRelatedEntities($entity);
		}

		$this->deleteEntityProperties($entity);

		$qb = Delete::fromTable(self::TABLE_NAME);
		$qb->where($qb->compare('guid', '=', $guid, ELGG_VALUE_GUID));

		$this->db->deleteData($qb);

		$this->events->triggerAfter('delete', $entity->type, $entity);

		return true;
	}

	/**
	 * Deletes entities owned or contained by the entity being deletes
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return void
	 */
	protected function deleteRelatedEntities(\ElggEntity $entity) {
		// Temporarily overriding access controls
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($entity) {
			/* @var $batch \ElggBatch */
			$batch = elgg_get_entities([
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
				'batch' => true,
				'batch_inc_offset' => false,
			]);
			/* @var $e \ElggEntity */
			foreach ($batch as $e) {
				if (!$this->delete($e, true)) {
					$batch->reportFailure();
				}
			}
		});
	}

	/**
	 * Clear data from secondary tables
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return void
	 */
	protected function deleteEntityProperties(\ElggEntity $entity) {
		// Temporarily overriding access controls and disable system_log to save performance
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($entity) {
			$entity->removeAllRelatedRiverItems();
			$entity->removeAllPrivateSettings();
			$entity->deleteOwnedAccessCollections();
			$entity->deleteAccessCollectionMemberships();
			// remove relationships without events
			// can't use DI provided service because of circular reference
			_elgg_services()->relationshipsTable->removeAll($entity->guid, '', false, '', false);
			_elgg_services()->relationshipsTable->removeAll($entity->guid, '', true, '', false);
			$entity->deleteOwnedAnnotations();
			$entity->deleteAnnotations();
			$entity->deleteMetadata();
		});
		
		$dir = new \Elgg\EntityDirLocator($entity->guid);
		$file_path = _elgg_services()->config->dataroot . $dir;
		elgg_delete_directory($file_path);
	}
}

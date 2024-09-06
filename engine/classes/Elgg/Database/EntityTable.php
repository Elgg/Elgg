<?php

namespace Elgg\Database;

use Elgg\Cache\EntityCache;
use Elgg\Cache\MetadataCache;
use Elgg\Config;
use Elgg\Database;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\EventsService;
use Elgg\Exceptions\Database\UserFetchFailureException;
use Elgg\Exceptions\DomainException;
use Elgg\I18n\Translator;
use Elgg\SessionManagerService;
use Elgg\Traits\Loggable;
use Elgg\Traits\TimeUsing;

/**
 * Entity table database service
 *
 * @internal
 * @since 1.10.0
 */
class EntityTable {
	
	use Loggable;
	use TimeUsing;

	/**
	 * @var string name of the entities database table
	 */
	public const TABLE_NAME = 'entities';
	
	public const DEFAULT_JOIN_ALIAS = 'e';

	protected array $deleted_guids = [];
	
	protected array $trashed_guids = [];
	
	protected array $entity_classes = [];

	/**
	 * Constructor
	 *
	 * @param Config                $config          Config
	 * @param Database              $db              Database
	 * @param EntityCache           $entity_cache    Entity cache
	 * @param MetadataCache         $metadata_cache  Metadata cache
	 * @param EventsService         $events          Events service
	 * @param SessionManagerService $session_manager Session manager
	 * @param Translator            $translator      Translator
	 */
	public function __construct(
		protected Config $config,
		protected Database $db,
		protected EntityCache $entity_cache,
		protected MetadataCache $metadata_cache,
		protected EventsService $events,
		protected SessionManagerService $session_manager,
		protected Translator $translator
	) {
	}

	/**
	 * Sets class constructor name for entities with given type and subtype
	 *
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @param string $class   Entity class
	 *
	 * @return void
	 * @throws DomainException
	 */
	public function setEntityClass(string $type, string $subtype, string $class = ''): void {
		if (!in_array($type, Config::ENTITY_TYPES)) {
			throw new DomainException("{$type} is not a valid entity type");
		}

		$this->entity_classes[$type][$subtype] = $class;
	}

	/**
	 * Returns class name registered as a constructor for a given type and subtype
	 * The classname is also validated to exist and to be an extension of an \ElggEntity
	 *
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 *
	 * @return string
	 */
	public function getEntityClass(string $type, string $subtype): string {
		
		$class_name = $this->entity_classes[$type][$subtype] ?? '';
		if ($class_name && !class_exists($class_name)) {
			$this->getLogger()->error("Class '{$class_name}' was not found");
			$class_name = '';
		}
		
		if (empty($class_name)) {
			$map = [
				'object' => \ElggObject::class,
				'user' => \ElggUser::class,
				'group' => \ElggGroup::class,
				'site' => \ElggSite::class,
			];
			if (isset($map[$type])) {
				$class_name = $map[$type];
			}
		}
		
		$parents = class_parents($class_name);
		if ($parents === false || !isset($parents[\ElggEntity::class])) {
			$this->getLogger()->error("{$class_name} must extend " . \ElggEntity::class);
			return '';
		}
		
		return $class_name;
	}

	/**
	 * Returns a database row from the entities table.
	 *
	 * @tip     Use get_entity() to return the fully loaded entity.
	 *
	 * @warning This will only return results if a) it exists, b) you have access to it.
	 *
	 * @param int $guid      The GUID of the object to extract
	 * @param int $user_guid GUID of the user accessing the row
	 *                       Defaults to logged in user if null
	 *                       Builds an access query for a logged out user if 0
	 *
	 * @return \stdClass|null
	 */
	public function getRow(int $guid, int $user_guid = null): ?\stdClass {
		if ($guid < 0) {
			return null;
		}

		$where = new EntityWhereClause();
		$where->guids = $guid;
		$where->viewer_guid = $user_guid;

		$select = Select::fromTable(self::TABLE_NAME, self::DEFAULT_JOIN_ALIAS);
		$select->select("{$select->getTableAlias()}.*");
		$select->addClause($where);

		return $this->db->getDataRow($select) ?: null;
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
	 * @return int
	 */
	public function insertRow(\stdClass $row, array $attributes = []): int {
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
	public function updateRow(int $guid, \stdClass $row): bool {
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
	 * @param \stdClass $row The row of the entry in the entities table.
	 *
	 * @return \ElggEntity|null
	 */
	public function rowToElggStar(\stdClass $row): ?\ElggEntity {
		if (!isset($row->type) || !isset($row->subtype)) {
			return null;
		}

		$class_name = $this->getEntityClass($row->type, $row->subtype);
		if (!$class_name) {
			return null;
		}

		return new $class_name($row);
	}

	/**
	 * Invalidate cache for entity
	 *
	 * @param int $guid GUID
	 *
	 * @return void
	 */
	public function invalidateCache(int $guid): void {
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($guid) {
			$entity = $this->get($guid);
			if ($entity instanceof \ElggEntity) {
				$entity->invalidateCache();
			}
		});
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
	 * @return \ElggEntity|null The correct Elgg or custom object based upon entity type and subtype
	 */
	public function get(int $guid, string $type = null, string $subtype = null): ?\ElggEntity {
		$entity = $this->entity_cache->load($guid);
		if ($entity instanceof \ElggEntity &&
			(!isset($type) || $entity->type === $type) &&
			(!isset($subtype) || $entity->subtype === $subtype)
		) {
			return $entity;
		}

		$row = $this->getRow($guid);
		if (empty($row)) {
			return null;
		}

		if (isset($type) && $row->type !== $type) {
			return null;
		}

		if (isset($subtype) && $row->subtype !== $subtype) {
			return null;
		}

		$entity = $this->rowToElggStar($row);
		if ($entity instanceof \ElggEntity) {
			$entity->cache();
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
	 *
	 * @return bool
	 */
	public function exists(int $guid): bool {
		return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($guid) {
			// need to ignore access and show hidden entities to check existence
			return !empty($this->getRow($guid));
		});
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
	public function fetch(QueryBuilder $query, array $options = []): array {
		$results = $this->db->getData($query, $options['callback']);
		if (empty($results)) {
			return [];
		}

		/* @var $preload \ElggEntity[] */
		$preload = array_filter($results, function ($e) {
			return $e instanceof \ElggEntity;
		});
		
		$this->metadata_cache->populateFromEntities($preload);
		
		$props_to_preload = [];
		if (elgg_extract('preload_owners', $options, false)) {
			$props_to_preload[] = 'owner_guid';
		}
		
		if (elgg_extract('preload_containers', $options, false)) {
			$props_to_preload[] = 'container_guid';
		}

		if (!empty($props_to_preload)) {
			_elgg_services()->entityPreloader->preload($preload, $props_to_preload);
		}

		return $results;
	}

	/**
	 * Update the time_deleted column in the entities table for $entity.
	 *
	 * @param \ElggEntity $entity  Entity to update
	 * @param int         $deleted Timestamp when the entity was deleted
	 *
	 * @return int
	 */
	public function updateTimeDeleted(\ElggEntity $entity, int $deleted = null): int {
		if ($deleted === null) {
			$deleted = $this->getCurrentTime()->getTimestamp();
		}

		$update = Update::table(self::TABLE_NAME);
		$update->set('time_deleted', $update->param($deleted, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));

		$this->db->updateData($update);

		return (int) $deleted;
	}

	/**
	 * Update the last_action column in the entities table for $entity.
	 *
	 * @warning This is different to time_updated.  Time_updated is automatically set,
	 * while last_action is only set when explicitly called.
	 *
	 * @param \ElggEntity $entity Entity annotation|relationship action carried out on
	 * @param int         $posted Timestamp of last action
	 *
	 * @return int
	 */
	public function updateLastAction(\ElggEntity $entity, int $posted = null): int {
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
	 * @return \ElggUser|null
	 * @throws UserFetchFailureException
	 */
	public function getUserForPermissionsCheck(int $guid = null): ?\ElggUser {
		if (empty($guid) || $guid === $this->session_manager->getLoggedInUserGuid()) {
			return $this->session_manager->getLoggedInUser();
		}

		$user = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($guid) {
			// need to ignore access and show hidden entities for potential hidden/disabled users
			return $this->get($guid, 'user');
		});

		if (!$user instanceof \ElggUser) {
			// requested to check access for a specific user_guid, but there is no user entity, so the caller
			// should cancel the check and return false
			$message = $this->translator->translate('UserFetchFailureException', [$guid]);

			throw new UserFetchFailureException($message);
		}

		return $user;
	}

	/**
	 * Restore entity
	 *
	 * @param \ElggEntity $entity Entity to restore
	 *
	 * @return bool
	 */
	public function restore(\ElggEntity $entity): bool {
		$qb = Update::table(self::TABLE_NAME);
		$qb->set('deleted', $qb->param('no', ELGG_VALUE_STRING))
			->set('time_deleted', $qb->param(0, ELGG_VALUE_TIMESTAMP))
			->where($qb->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));

		return $this->db->updateData($qb);
	}

	/**
	 * Enables entity
	 *
	 * @param \ElggEntity $entity Entity to enable
	 *
	 * @return bool
	 */
	public function enable(\ElggEntity $entity): bool {
		$qb = Update::table(self::TABLE_NAME);
		$qb->set('enabled', $qb->param('yes', ELGG_VALUE_STRING))
			->where($qb->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));

		return $this->db->updateData($qb);
	}

	/**
	 * Disables entity
	 *
	 * @param \ElggEntity $entity Entity to disable
	 *
	 * @return bool
	 */
	public function disable(\ElggEntity $entity): bool {
		$qb = Update::table(self::TABLE_NAME);
		$qb->set('enabled', $qb->param('no', ELGG_VALUE_STRING))
			->where($qb->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));
		
		return $this->db->updateData($qb);
	}

	/**
	 * Delete entity and all of its properties
	 *
	 * @param \ElggEntity $entity    Entity
	 * @param bool        $recursive Delete all owned and contained entities
	 *
	 * @return bool
	 */
	public function delete(\ElggEntity $entity, bool $recursive = true): bool {
		if (!$entity->guid) {
			return false;
		}
		
		set_time_limit(0);
		
		return $this->events->triggerSequence('delete', $entity->type, $entity, function(\ElggEntity $entity) use ($recursive) {
			if ($entity instanceof \ElggUser) {
				// ban to prevent using the site during delete
				$entity->ban();
			}
			
			// we're going to delete this entity, log the guid to prevent deadloops
			$this->deleted_guids[] = $entity->guid;
			
			if ($recursive) {
				$this->deleteRelatedEntities($entity);
			}
			
			$this->deleteEntityProperties($entity);
			
			$qb = Delete::fromTable(self::TABLE_NAME);
			$qb->where($qb->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));
			
			return (bool) $this->db->deleteData($qb);
		});
	}
	
	/**
	 * Trash an entity (not quite delete but close)
	 *
	 * @param \ElggEntity $entity    Entity
	 * @param bool        $recursive Trash all owned and contained entities
	 *
	 * @return bool
	 */
	public function trash(\ElggEntity $entity, bool $recursive = true): bool {
		if (!$entity->guid) {
			return false;
		}
		
		if (!$this->config->trash_enabled) {
			return $this->delete($entity, $recursive);
		}
		
		if ($entity->isDeleted()) {
			// already trashed
			return true;
		}
		
		return $this->events->triggerSequence('trash', $entity->type, $entity, function(\ElggEntity $entity) use ($recursive) {
			$unban_after = false;
			if ($entity instanceof \ElggUser && !$entity->isBanned()) {
				// temporarily ban to prevent using the site during disable
				$entity->ban();
				$unban_after = true;
			}
			
			$this->trashed_guids[] = $entity->guid;
			
			if ($recursive) {
				set_time_limit(0);
				
				$this->trashRelatedEntities($entity);
			}
			
			$deleter_guid = elgg_get_logged_in_user_guid();
			if (!empty($deleter_guid)) {
				$entity->addRelationship($deleter_guid, 'deleted_by');
			}
			
			$qb = Update::table(self::TABLE_NAME);
			$qb->set('deleted', $qb->param('yes', ELGG_VALUE_STRING))
			   ->where($qb->compare('guid', '=', $entity->guid, ELGG_VALUE_GUID));
			
			$trashed = $this->db->updateData($qb);
			
			$entity->updateTimeDeleted();
			
			if ($unban_after) {
				$entity->unban();
			}
			
			if ($trashed) {
				$entity->invalidateCache();
			}
			
			return $trashed;
		});
	}

	/**
	 * Deletes entities owned or contained by the entity being deletes
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return void
	 */
	protected function deleteRelatedEntities(\ElggEntity $entity): void {
		// Temporarily overriding access controls
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($entity) {
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
				if (in_array($e->guid, $this->deleted_guids)) {
					// prevent deadloops, doing this here in case of large deletes which could cause query length issues
					$batch->reportFailure();
					continue;
				}
				
				if (!$e->delete(true, true)) {
					$batch->reportFailure();
				}
			}
		});
	}

	/**
	 * Trash entities owned or contained by the entity being trashed
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return void
	 */
	protected function trashRelatedEntities(\ElggEntity $entity): void {
		// Temporarily overriding access controls
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() use ($entity) {
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
				if (in_array($e->guid, $this->trashed_guids)) {
					// prevent deadloops, doing this here in case of large deletes which could cause query length issues
					$batch->reportFailure();
					continue;
				}
				
				if (!$e->delete(true, false)) {
					$batch->reportFailure();
				}
				
				$e->addRelationship($entity->guid, 'deleted_with');
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
	protected function deleteEntityProperties(\ElggEntity $entity): void {
		// Temporarily overriding access controls and disable system_log to save performance
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES | ELGG_DISABLE_SYSTEM_LOG, function() use ($entity) {
			$entity->removeAllRelatedRiverItems();
			$entity->deleteOwnedAccessCollections();
			$entity->deleteAccessCollectionMemberships();
			// remove relationships without events
			// can't use DI provided service because of circular reference
			_elgg_services()->relationshipsTable->removeAll($entity->guid, '', false, '', false);
			_elgg_services()->relationshipsTable->removeAll($entity->guid, '', true, '', false);
			$entity->deleteOwnedAnnotations();
			$entity->deleteAnnotations();
			$entity->deleteMetadata();
			_elgg_services()->delayedEmailQueueTable->deleteAllRecipientRows($entity->guid);
		});
		
		$dir = new \Elgg\EntityDirLocator($entity->guid);
		$file_path = _elgg_services()->config->dataroot . $dir;
		elgg_delete_directory($file_path);
	}
}

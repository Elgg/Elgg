<?php

namespace Elgg\Cache;

use ElggEntity;
use ElggSharedMemoryCache;
use ElggUser;

/**
 * Volatile cache for entities
 *
 * @access private
 */
class EntityCache {

	// @todo Pick a less arbitrary limit
	const MAX_SIZE = 256;

	/**
	 * @var ElggEntity[] GUID keys
	 */
	protected $entities = [];

	/**
	 * @var bool[] GUID keys
	 */
	protected $disabled_guids = [];

	/**
	 * @var array
	 */
	protected $username_cache = [];

	/**
	 * @var ElggSharedMemoryCache
	 */
	protected $persisted_cache;

	/**
	 * Constructor
	 *
	 * @param ElggSharedMemoryCache $cache Cache
	 */
	public function __construct(ElggSharedMemoryCache $cache) {
		$this->persisted_cache = $cache;
	}

	/**
	 * Retrieve a entity from the cache.
	 *
	 * @param int $guid The GUID
	 *
	 * @return \ElggEntity|false false if entity not cached
	 */
	public function get($guid) {
		$guid = (int) $guid;

		if (isset($this->disabled_guids[$guid])) {
			return false;
		}

		if (isset($this->entities[$guid])) {
			$entity = $this->entities[$guid];
		} else {
			$entity = $this->persisted_cache->load($guid);
			if ($entity instanceof ElggEntity) {
				$this->set($entity);
			}
		}

		if ($entity instanceof \ElggEntity) {
			if (!elgg_get_ignore_access() && !has_access_to_entity($entity)) {
				return false;
			}
			
			return $entity;
		}

		return false;
	}

	/**
	 * Returns cached user entity by username
	 *
	 * @param string $username Username
	 *
	 * @return ElggUser|false
	 */
	public function getByUsername($username) {
		if (isset($this->username_cache[$username])) {
			return $this->get($this->username_cache[$username]);
		}

		return false;
	}

	/**
	 * Cache an entity
	 *
	 * Session state changes always flush this cache,
	 * e.g. if entity is loaded during ignored access, this cache
	 * will persist it as long as the ignored access is enabled
	 *
	 * @param ElggEntity $entity Entity to cache
	 *
	 * @return void
	 */
	public function set(ElggEntity $entity) {
		$guid = $entity->guid;

		if (!$guid) {
			return;
		}

		if (isset($this->disabled_guids[$guid])) {
			return;
		}

		$this->storeInPersistedCache($entity);

		// Don't store too many or we'll have memory problems
		if (count($this->entities) > self::MAX_SIZE) {
			$this->remove(array_rand($this->entities));
		}

		$this->entities[$guid] = $entity;

		if ($entity instanceof ElggUser) {
			$this->username_cache[$entity->username] = $entity->guid;
		}
	}

	/**
	 * Cache the entity in a persisted cache
	 *
	 * @param ElggEntity $entity      Entity
	 * @param int        $last_action Last action time
	 *
	 * @return void
	 */
	public function storeInPersistedCache(\ElggEntity $entity, $last_action = 0) {
		$this->persisted_cache->save($entity->guid, $entity->prepareForPersistedCache($last_action));
	}

	/**
	 * Invalidate this class's entry in the cache.
	 *
	 * @param int $guid The entity guid
	 *
	 * @return void
	 */
	public function remove($guid) {
		$guid = (int) $guid;

		$this->persisted_cache->delete($guid);

		if (!isset($this->entities[$guid])) {
			return;
		}

		unset($this->entities[$guid]);

		$username = array_search($guid, $this->username_cache);
		if ($username !== false) {
			unset($this->username_cache[$username]);
		}

		// Purge separate metadata cache. Original idea was to do in entity destructor, but that would
		// have caused a bunch of unnecessary purges at every shutdown. Doing it this way we have no way
		// to know that the expunged entity will be GCed (might be another reference living), but that's
		// OK; the metadata will reload if necessary.
		elgg_get_session()->metadataCache->clear($guid);
	}

	/**
	 * Clear the entity cache
	 *
	 * @return void
	 */
	public function clear() {
		$this->entities = [];
		$this->username_cache = [];
	}

	/**
	 * Remove this entity from the entity cache and make sure it is not re-added
	 *
	 * @todo this is a workaround until #5604 can be implemented
	 *
	 * @param int $guid The entity guid
	 *
	 * @return void
	 */
	public function disableCachingForEntity($guid) {
		$this->remove($guid);
		$this->disabled_guids[$guid] = true;
	}

	/**
	 * Allow this entity to be stored in the entity cache
	 *
	 * @param int $guid The entity guid
	 *
	 * @return void
	 */
	public function enableCachingForEntity($guid) {
		unset($this->disabled_guids[$guid]);
	}
}

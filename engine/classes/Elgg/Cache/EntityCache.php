<?php

namespace Elgg\Cache;

use ElggCache;
use ElggEntity;
use ElggSession;

/**
 * Volatile cache for entities
 *
 * @internal
 */
class EntityCache {

	const MAX_SIZE = 256;

	/**
	 * @var ElggSession
	 */
	private $session;

	/**
	 * @var ElggCache
	 */
	private $cache;

	/**
	 * @var int
	 */
	private $size = 0;

	/**
	 * Constructor
	 *
	 * @param ElggSession $session Session
	 * @param ElggCache   $cache   Cache
	 */
	public function __construct(ElggSession $session, ElggCache $cache) {
		$this->session = $session;
		$this->cache = $cache;
	}

	/**
	 * Retrieve a entity from the cache.
	 *
	 * @param int $guid The GUID
	 *
	 * @return \ElggEntity|null
	 */
	public function load($guid) {
		return $this->cache->load((int) $guid);
	}

	/**
	 * Cache an entity.
	 *
	 * @param ElggEntity $entity Entity to cache
	 *
	 * @return void
	 */
	public function save(ElggEntity $entity) {
		if (!$entity->guid || !$entity->isCacheable()) {
			return;
		}

		if ($this->size > self::MAX_SIZE) {
			// Don't store too many or we'll have memory problems
			return;
		}

		$this->cache->save($entity->guid, $entity);
		$this->size++;
	}

	/**
	 * Invalidate this class's entry in the cache.
	 *
	 * @param int $guid The entity guid
	 *
	 * @return void
	 */
	public function delete($guid) {
		if (!$guid) {
			return;
		}

		$entity = $this->cache->load($guid);
		if (!$entity instanceof ElggEntity) {
			return;
		}

		$this->cache->delete($guid);
		$this->size--;
	}

	/**
	 * Clear the entity cache
	 *
	 * @return void
	 */
	public function clear() {
		$this->cache->clear();
		$this->size = 0;
	}

}

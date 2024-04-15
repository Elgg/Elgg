<?php

namespace Elgg\Cache;

/**
 * Volatile cache for entities
 *
 * @internal
 */
class EntityCache {

	/**
	 * Constructor
	 *
	 * @param BaseCache $cache Cache
	 */
	public function __construct(protected BaseCache $cache) {
	}

	/**
	 * Retrieve a entity from the cache.
	 *
	 * @param int $guid The GUID
	 *
	 * @return \ElggEntity|null
	 */
	public function load(int $guid): ?\ElggEntity {
		return $this->cache->load($guid);
	}

	/**
	 * Cache an entity.
	 *
	 * @param \ElggEntity $entity Entity to cache
	 *
	 * @return void
	 */
	public function save(\ElggEntity $entity): void {
		if (!$entity->guid || !$entity->isCacheable()) {
			return;
		}

		$this->cache->save($entity->guid, $entity);
	}

	/**
	 * Invalidate this class's entry in the cache.
	 *
	 * @param int $guid The entity guid
	 *
	 * @return void
	 */
	public function delete(int $guid): void {
		$this->cache->delete($guid);
	}

	/**
	 * Clear the entity cache
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->cache->clear();
	}
}

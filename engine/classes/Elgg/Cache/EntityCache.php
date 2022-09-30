<?php

namespace Elgg\Cache;

/**
 * Volatile cache for entities
 *
 * @internal
 */
class EntityCache {

	const MAX_SIZE = 256;

	/**
	 * @var BaseCache
	 */
	protected $cache;

	/**
	 * @var int
	 */
	protected $size = 0;

	/**
	 * Constructor
	 *
	 * @param BaseCache $cache Cache
	 */
	public function __construct(BaseCache $cache) {
		$this->cache = $cache;
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
	public function delete(int $guid): void {
		if (!$guid) {
			return;
		}

		$entity = $this->cache->load($guid);
		if (!$entity instanceof \ElggEntity) {
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
	public function clear(): void {
		$this->cache->clear();
		$this->size = 0;
	}
}

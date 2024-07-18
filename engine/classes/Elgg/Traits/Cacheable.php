<?php

namespace Elgg\Traits;

use Elgg\Cache\BaseCache;
use Elgg\Cache\CompositeCache;

/**
 * Utility trait for injecting cache
 *
 * @internal
 */
trait Cacheable {

	protected BaseCache $cache;

	/**
	 * Set cache
	 *
	 * @param BaseCache $cache Cache
	 *
	 * @return void
	 */
	public function setCache(BaseCache $cache): void {
		$this->cache = $cache;
	}

	/**
	 * Get cache
	 *
	 * @return BaseCache
	 */
	public function getCache(): BaseCache {
		if (!isset($this->cache)) {
			return new CompositeCache('void', _elgg_services()->config, ELGG_CACHE_BLACK_HOLE);
		}

		return $this->cache;
	}
}

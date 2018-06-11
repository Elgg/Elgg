<?php

namespace Elgg;

use Elgg\Cache\CompositeCache;
use ElggCache;

/**
 * Utility trait for injecting cache
 */
trait Cacheable {

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * Set cache
	 *
	 * @param ElggCache $cache Cache
	 *
	 * @return void
	 */
	public function setCache(ElggCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Get cache
	 * @return ElggCache
	 */
	public function getCache() {
		if (!isset($this->cache)) {
			return new CompositeCache('void', _elgg_config(), ELGG_CACHE_BLACK_HOLE);
		}

		return $this->cache;
	}

}
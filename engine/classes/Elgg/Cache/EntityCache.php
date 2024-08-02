<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Volatile cache for entities
 *
 * @internal
 */
class EntityCache extends CacheService {
	
	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('entity_cache', $this->config, $flags);
	}
}

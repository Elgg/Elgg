<?php

namespace Elgg\Cache;

/**
 * Persistent data cache
 * Used for caching metadata
 *
 * @property-read CompositeCache $metadata
 */
class DataCache extends CacheCollection {

	/**
	 * Create a new cache under a namespace
	 *
	 * @param string $namespace Namespace
	 *
	 * @return CompositeCache
	 */
	protected function create($namespace) {
		return new CompositeCache($namespace, $this->config, ELGG_CACHE_RUNTIME);
	}
}

<?php

namespace Elgg\Cache;

/**
 * Runtime cache
 * Used to cache entities accessed by the user, access collection membership
 *
 * @property-read CompositeCache $access
 * @property-read CompositeCache $entities
 */
class SessionCache extends CacheCollection {

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

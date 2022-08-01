<?php

namespace Elgg\Cache;

/**
 * @group Cache
 * @group Memcache
 */
class PersistentCacheTest extends ElggCacheTestCase {

	function createCache(string $namespace = 'persistent_test') {
		return new CompositeCache($namespace, _elgg_services()->config, ELGG_CACHE_PERSISTENT);
	}
	
	public function allowSkip(): bool {
		return !elgg_get_config('redis', false) && !elgg_get_config('memcache', false);
	}
}
<?php

namespace Elgg\Cache;

class PersistentCacheUnitTest extends BaseCacheUnitTestCase {

	function createCache(string $namespace = 'persistent_test') {
		return new CompositeCache($namespace, _elgg_services()->config, CompositeCache::CACHE_PERSISTENT);
	}
	
	public function allowSkip(): bool {
		return true;
	}
}

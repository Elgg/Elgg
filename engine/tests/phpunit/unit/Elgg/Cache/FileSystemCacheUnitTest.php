<?php

namespace Elgg\Cache;

class FileSystemCacheUnitTest extends BaseCacheUnitTestCase {

	function createCache(string $namespace = 'filesystem_test') {
		return new CompositeCache($namespace, _elgg_services()->config, CompositeCache::CACHE_FILESYSTEM);
	}
}

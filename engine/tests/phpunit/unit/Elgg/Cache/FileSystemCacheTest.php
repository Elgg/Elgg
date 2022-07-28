<?php

namespace Elgg\Cache;

/**
 * @group Cache
 */
class FileSystemCacheTest extends ElggCacheTestCase {

	function createCache(string $namespace = 'filesystem_test') {
		return new CompositeCache($namespace, _elgg_services()->config, ELGG_CACHE_FILESYSTEM);
	}

}
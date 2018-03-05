<?php

namespace Elgg\Cache;

/**
 * @group Cache
 */
class FileSystemCacheTest extends ElggCacheTestCase {

	function createCache() {
		return new CompositeCache('filesystem_test', _elgg_config(), ELGG_CACHE_FILESYSTEM);
	}

}
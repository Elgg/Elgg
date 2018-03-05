<?php

namespace Elgg\Cache;

/**
 * @group Cache
 */
class RuntimeCacheTest extends ElggCacheTestCase {

	function createCache() {
		return new CompositeCache('test', _elgg_config(), ELGG_CACHE_RUNTIME);
	}

}
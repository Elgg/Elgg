<?php

namespace Elgg\Cache;

/**
 * @group Cache
 * @group Memcache
 */
class PersistentCacheTest extends ElggCacheTestCase {

	function createCache() {
		return new CompositeCache('persistent_test', _elgg_services()->config, ELGG_CACHE_PERSISTENT);
	}
}
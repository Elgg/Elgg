<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Access Cache
 *
 * @internal
 * @since 6.1
 */
class AccessCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('access_cache', $this->config, $flags);
	}
}

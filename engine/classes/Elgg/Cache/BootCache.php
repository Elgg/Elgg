<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Boot Cache
 *
 * @internal
 * @since 6.1
 */
class BootCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_PERSISTENT | CompositeCache::CACHE_FILESYSTEM | CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('boot_cache', $this->config, $flags);
	}
}

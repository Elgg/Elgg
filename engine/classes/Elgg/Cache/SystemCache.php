<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * System Cache
 *
 * @internal
 * @since 1.10.0
 */
class SystemCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_PERSISTENT | CompositeCache::CACHE_FILESYSTEM | CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('system_cache', $this->config, $flags);
		
		$this->enabled = (bool) $this->config->system_cache_enabled;
	}
}

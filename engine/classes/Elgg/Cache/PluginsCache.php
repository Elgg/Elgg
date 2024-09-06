<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Plugins Cache
 *
 * @internal
 * @since 6.1
 */
class PluginsCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('plugins_cache', $this->config, $flags);
	}
}

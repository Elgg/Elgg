<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Server Cache
 *
 * @internal
 * @since 6.1
 */
class ServerCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = ELGG_CACHE_LOCALFILESYSTEM | ELGG_CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('server_cache', $this->config, $flags);
		
		$this->enabled = (bool) $this->config->system_cache_enabled;
	}
}

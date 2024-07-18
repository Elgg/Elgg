<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Autoload Cache
 *
 * @internal
 * @since 6.1
 */
class AutoloadCache extends CacheService {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = ELGG_CACHE_LOCALFILESYSTEM | ELGG_CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('autoload_cache', $this->config, $flags);
	}
}

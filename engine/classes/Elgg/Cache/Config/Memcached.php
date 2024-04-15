<?php

namespace Elgg\Cache\Config;

use Phpfastcache\Drivers\Memcached\Config;

/**
 * Configuration for memcache(d) fastcache driver
 *
 * @internal
 * @since 4.2
 */
class Memcached extends Config {
	
	/**
	 * Factory to return a config object to be used when starting a driver
	 *
	 * @param string       $namespace cache namespace
	 * @param \Elgg\Config $config    Elgg configuration
	 *
	 * @return self|NULL
	 */
	public static function fromElggConfig(string $namespace, \Elgg\Config $config): ?self {
		
		if (!$config->memcache || empty($config->memcache_servers)) {
			return null;
		}
		
		$servers = [];
		foreach ($config->memcache_servers as $server) {
			$server_config = [
				'host' => '127.0.0.1',
				'port' => 11211,
				'saslUser' => false,
				'saslPassword' => false,
			];

			$servers[] = array_merge($server_config, $server);
		}
		
		$opt_prefix = (string) $config->memcache_namespace_prefix;
		$opt_prefix .= $namespace;
		
		return new self([
			'servers' => $servers,
			'useStaticItemCaching' => true,
			'itemDetailedDate' => true,
			'optPrefix' => $opt_prefix,
		]);
	}
}

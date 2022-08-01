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
			
			if (array_key_exists('host', $server)) {
				// assume correct config
				$server_config = array_merge($server_config, $server);
			} else {
				// assume old config syntax
				elgg_deprecated_notice("Memcache server({$server[0]}) configuration format has been changed. Please update the configuration in elgg-config/settings.php", '4.2');
				
				$server_config['host'] = $server[0];
				$server_config['port'] = $server[1];
			}
			
			$servers[] = $server_config;
		}
		
		$opt_prefix = (string) $config->memcache_namespace_prefix;
		$opt_prefix .= $namespace;
		
		return new self([
			'servers' => $servers,
			'preventCacheSlams' => true,
			'useStaticItemCaching' => true,
			'itemDetailedDate' => true,
			'optPrefix' => $opt_prefix,
		]);
	}
}

<?php

namespace Elgg\Cache\Config;

use Phpfastcache\Drivers\Redis\Config;

/**
 * Configuration for redis fastcache driver
 *
 * @internal
 * @since 4.2
 */
class Redis extends Config {
	
	/**
	 * Factory to return a config object to be used when starting a driver
	 *
	 * @param string       $namespace cache namespace
	 * @param \Elgg\Config $config    Elgg configuration
	 *
	 * @return self|NULL
	 */
	public static function fromElggConfig(string $namespace, \Elgg\Config $config): ?self {
		
		if (!$config->redis || empty($config->redis_servers) || !is_array($config->redis_servers)) {
			return null;
		}
		
		$options = [
			'preventCacheSlams' => true,
			'useStaticItemCaching' => true,
			'itemDetailedDate' => true,
			'optPrefix' => $namespace,
		];
		if (!empty($config->redis_options) && is_array($config->redis_options)) {
			$options = $config->redis_options;
		}
		
		if (count($config->redis_servers) > 1) {
			elgg_deprecated_notice("Multiple Redis servers are not supported. Only the first server will be used. Please update the configuration in elgg-config/settings.php", '4.2');
		}
		
		$server = $config->redis_servers[0];
		if (!array_key_exists('host', $server)) {
			// assume old config syntax
			elgg_deprecated_notice("Redis server({$server[0]}) configuration format has been changed. Please update the configuration in elgg-config/settings.php", '4.2');
			$options['host'] = $server[0];
			$options['port'] = $server[1];
		} else {
			$options = array_merge($options, $server);
		}
		
		return new self($options);
	}
}

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
			'useStaticItemCaching' => true,
			'itemDetailedDate' => true,
			'optPrefix' => $namespace,
		];
		if (!empty($config->redis_options) && is_array($config->redis_options)) {
			$options = array_merge($options, $config->redis_options);
		}
		
		if (count($config->redis_servers) > 1) {
			elgg_log('Multiple Redis servers are not supported. Only the first server will be used. Please update the configuration in elgg-config/settings.php', \Psr\Log\LogLevel::WARNING);
		}
		
		$server = $config->redis_servers[0];

		$options = array_merge($options, $server);
		
		return new self($options);
	}
}

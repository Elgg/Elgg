<?php
namespace Elgg\Config;

use Elgg\Config;
use Elgg\Database\SiteSecret;

/**
 * Creates a config service from an .env file
 */
class EnvReader {

	/**
	 * Read config values from $_ENV
	 *
	 * @param array $env Set of environment variables from $_ENV
	 *
	 * @return array
	 */
	public function getValues(array $env = null) {
		if ($env === null) {
			$env = $_ENV;
		}

		$config = [];
		$casters = [
			'memcache' => 'boolval',
			'simplecache_enabled' => 'boolval',
			'broken_mta' => 'boolval',
			'db_disable_query_cache' => 'boolval',
			'auto_disable_plugins' => 'boolval',
			'enable_profiling' => 'boolval',
			'profiling_sql' => 'boolval',
			'boot_cache_ttl' => 'intval',
			'min_password_length' => 'intval',
			'action_time_limit' => 'intval',
		];

		// ELGG_FOO_BAR becomes $config['foo_bar']
		foreach ($env as $key => $value) {
			if (0 !== strpos($key, 'ELGG_')) {
				continue;
			}

			$new_key = strtolower(substr($key, 5));

			if (isset($casters[$new_key])) {
				$new_val = $casters[$new_key]($value);
			} else {
				$new_val = $value;
			}

			$config[$new_key] = $new_val;
		}

		if (!empty($config['site_secret'])) {
			$config[SiteSecret::CONFIG_KEY] = $config['site_secret'];
			unset($config['site_secret']);
		}

		// must come before cookies because we use strtotime()
		if (isset($config['default_tz'])) {
			date_default_timezone_set($config['default_tz']);
		} else {
			date_default_timezone_set('UTC');
		}
		unset($config['default_tz']);

		// cookies
		foreach (['', 'remember_'] as $prefix) {
			$key = ($prefix === '') ? 'cookies' : 'remember_me';

			if (isset($config["{$prefix}cookie_defaults_source"])
				&& ($config["{$prefix}cookie_defaults_source"] === 'session_get_cookie_params'))
			{
				$config[$key]['session'] = session_get_cookie_params();
			}
			if (isset($config["{$prefix}cookie_name"])) {
				$config[$key]['session']['name'] = $config["{$prefix}cookie_name"];
			}
			if (isset($config["{$prefix}cookie_domain"])) {
				$config[$key]['session']['domain'] = $config["{$prefix}cookie_domain"];
			}
			if (isset($config["{$prefix}cookie_path"])) {
				$config[$key]['session']['path'] = $config["{$prefix}cookie_path"];
			}
			if (isset($config["{$prefix}cookie_secure"])) {
				$config[$key]['session']['secure'] = (bool)$config["{$prefix}cookie_secure"];
			}
			if (isset($config["{$prefix}cookie_httponly"])) {
				$config[$key]['session']['httponly'] = (bool)$config["{$prefix}cookie_httponly"];
			}
			if (isset($config["{$prefix}cookie_expire"])) {
				$time = strtotime($config["{$prefix}cookie_expire"]);
				$config[$key]['session']['expire'] = $time;
			}

			unset($config["{$prefix}cookie_defaults_source"]);
			unset($config["{$prefix}cookie_name"]);
			unset($config["{$prefix}cookie_domain"]);
			unset($config["{$prefix}cookie_secure"]);
			unset($config["{$prefix}cookie_httponly"]);
			unset($config["{$prefix}cookie_expire"]);
		}

		// split DBs
		if (isset($config['read1_dbuser'])) {
			// allow multi-DB
			$config['db']['split'] = true;
			foreach (['dbuser', 'dbpass', 'dbname', 'dbhost'] as $key) {
				$config['db']['write'][$key] = $config[$key];
			}

			$i = 1;
			while (isset($config["read{$i}_dbuser"])) {
				foreach (['dbuser', 'dbpass', 'dbname', 'dbhost'] as $key) {
					$config_key = "read{$i}_{$key}";
					$config['db']['read'][$i - 1] = $config[$config_key];
					unset($config[$config_key]);
				}
				$i++;
			}
		}

		// memcache
		$i = 1;
		while (isset($config["memcache{$i}_host"])) {
			$config['memcache_servers'][] = [
				$config["memcache{$i}_host"],
				$config["memcache{$i}_port"],
			];
			unset($config["memcache{$i}_host"]);
			unset($config["memcache{$i}_port"]);
			$i++;
		}

		return $config;
	}
}

<?php
/**
 *
 */

namespace Elgg\Mocks\Database;

class ConfigTable extends \Elgg\Database\ConfigTable {

	private $all = [
		'__site_secret__' => "zV8rmn_IwqqM2btV4UdSB7l_9gzBqcy4",
		'admin_registered' => 1,
		'allow_registration' => 1,
		'allow_user_default_access' => 1,
		'default_access' => 2,
		'default_limit' => 15,
		'installed' => 1512981149,
		'language' => 'en',
		'processed_upgrades' => [],
		'simplecache_enabled' => 0,
		'system_cache_enabled' => 0,
		'version' => 2017041200,
		'min_password_length' => 6,
	];

	public function get($name) {
		return elgg_extract($name, $this->all, []);
	}

	public function set($name, $value) {
		$this->all[$name] = $value;
		return true;
	}

	public function getAll() {
		return $this->all;
	}
}
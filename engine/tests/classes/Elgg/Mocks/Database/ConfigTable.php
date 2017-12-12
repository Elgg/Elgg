<?php

namespace Elgg\Mocks\Database;

class ConfigTable extends \Elgg\Database\ConfigTable {

	private $all = [
		'__site_secret__' => "z1234567890123456789012345678901",
		'admin_registered' => 1,
		'allow_registration' => 1,
		'allow_user_default_access' => 1,
		'default_access' => 2,
		'default_limit' => 10,
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
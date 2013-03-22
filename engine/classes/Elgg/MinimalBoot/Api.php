<?php

/**
 * @access private
 *
 * @package Elgg.Core
 */
class Elgg_MinimalBoot_Api {

	/**
	 * @var stdClass
	 */
	protected $config;

	/**
	 * @var stdClass copy of $CONFIG used to store fetched values (we don't want to alter it)
	 */
	protected $config_copy;

	/**
	 * @var string
	 */
	protected $engine_dir;

	/**
	 * Constructor
	 *
	 * @param stdClass $config configuration values
	 * @param string   $engine_dir Elgg's /engine location
	 */
	public function __construct(stdClass $config, $engine_dir) {
		$this->config = $config;
		$this->config_copy = clone $config;
		$this->engine_dir = rtrim($engine_dir, '/\\');
	}

	/**
	 * Boot the full Elgg engine via start.php
	 */
	public function bootElgg() {
		require_once $this->engine_dir . '/start.php';
		// fresh copy of config values if we need them
		$this->config_copy = clone $this->config;
	}

	/**
	 * Fetch values usually stored in datalists. If the names are present in $CONFIG, they are
	 * returned from there without a DB connection being made.
	 *
	 * Note: Try not to call this function more than once. Potentially each call could result
	 * in a DB connect/query/disconnect cycle, but the function tries to fetch all useful values
	 * if it has to query.
	 *
	 * @param string[]|string $requested_names
	 *
	 * @return string[]
	 * @throws Exception
	 */
	public function fetchDatalistValues($requested_names) {
		$names_to_fetch = array(
			'dataroot' => true,
			'default_site' => true,
			'simplecache_enabled' => true,
			'system_cache_enabled' => true,
			'lastupdate' => true,
		);
		$requested_names = (array)$requested_names;
		$return = array();

		// try config
		$must_query = false;

		foreach ($requested_names as $name) {
			if (!empty($this->config_copy->$name)) {
				$return[$name] = $this->config_copy->$name;
				unset($names_to_fetch[$name]);
			} else {
				if (empty($names_to_fetch[$name])) {
					throw new Exception("Cannot fetch key '$name'");
				}
				$must_query = true;
			}
		}
		if (!$must_query) {
			return $return;
		}

		// make fetch
		$db = new Elgg_MinimalBoot_Database($this->config);

		$quoted_names = array();
		foreach (array_keys($names_to_fetch) as $name) {
			if ($name == 'lastupdate') {
				$name = 'simplecache_lastupdate';
			}
			$quoted_names[] = $db->quote($name);
		}

		$rows = $db->getData("SELECT `name`, `value` FROM {$this->config->dbprefix}datalists "
			. "WHERE name IN (" . implode(',', $quoted_names) . ")");
		foreach ($rows as $row) {
			if ($row->name == 'simplecache_lastupdate') {
				$row->name = 'lastupdate';
			}
			$return[$row->name] = $row->value;
			$this->config_copy->{$row->name} = $row->value;
		}

		return $return;
	}
}
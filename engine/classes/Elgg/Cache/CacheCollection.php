<?php

namespace Elgg\Cache;

use Elgg\Cacheable;
use Elgg\Config;

/**
 * A collection of composite caches
 */
abstract class CacheCollection {

	/**
	 * @var CompositeCache[]
	 */
	protected $caches = [];

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(Config $config) {
		$this->config = $config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		return $this->get($name);
	}

	/**
	 * Create a new cache under a namespace
	 *
	 * @param string $namespace Namespace
	 *
	 * @return CompositeCache
	 */
	abstract protected function create($namespace);

	/**
	 * Returns an instance of composite cache,
	 * or creates one
	 *
	 * @param string $namespace Cache namespace
	 *
	 * @return CompositeCache
	 */
	public function get($namespace) {
		if (!isset($this->caches[$namespace])) {
			$this->caches[$namespace] = $this->create($namespace);
		}

		return $this->caches[$namespace];
	}

	/**
	 * Clear all persistent caches
	 * @return void
	 */
	public function clear() {
		foreach ($this->caches as $cache) {
			$cache->clear();
		}
	}

	/**
	 * Disable all persistent caches
	 * @return void
	 */
	public function disable() {
		foreach ($this->caches as $cache) {
			$cache->disable();
		}
	}

	/**
	 * Enable all persistent caches
	 * @return void
	 */
	public function enable() {
		foreach ($this->caches as $cache) {
			$cache->enable();
		}
	}

}

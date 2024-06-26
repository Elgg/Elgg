<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * A collection of composite caches
 */
abstract class CacheCollection {

	/**
	 * @var CompositeCache[]
	 */
	protected array $caches = [];

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
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
	 *
	 * @return void
	 */
	public function clear() {
		foreach ($this->caches as $cache) {
			$cache->clear();
		}
	}
	
	/**
	 * Invalidate all caches
	 *
	 * @return void
	 */
	public function invalidate() {
		foreach ($this->caches as $cache) {
			$cache->invalidate();
		}
	}
	
	/**
	 * Purge all caches
	 *
	 * @return void
	 */
	public function purge() {
		foreach ($this->caches as $cache) {
			$cache->purge();
		}
	}

	/**
	 * Disable all persistent caches
	 *
	 * @return void
	 */
	public function disable() {
		foreach ($this->caches as $cache) {
			$cache->disable();
		}
	}

	/**
	 * Enable all persistent caches
	 *
	 * @return void
	 */
	public function enable() {
		foreach ($this->caches as $cache) {
			$cache->enable();
		}
	}
}

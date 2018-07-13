<?php

namespace Elgg\Cache;

use DateTime;
use Elgg\Config;
use ElggCache;
use Stash\Driver\Apc;
use Stash\Driver\BlackHole;
use Stash\Driver\Composite;
use Stash\Driver\Ephemeral;
use Stash\Driver\FileSystem;
use Stash\Driver\Memcache;
use Stash\Driver\Redis;
use Stash\Pool;

/**
 * Composite cache pool
 *
 * @internal
 */
class CompositeCache extends ElggCache {

	/**
	 * TTL of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	protected $ttl = 86400;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var int
	 */
	protected $flags;

	/**
	 * @var Pool
	 */
	protected $pool;

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * Constructor
	 *
	 * @param string $namespace Cache namespace
	 * @param Config $config    Elgg config
	 * @param int    $flags     Start flags
	 *
	 * @throws \ConfigurationException
	 */
	public function __construct($namespace, Config $config, $flags) {
		parent::__construct();

		$this->namespace = $namespace;
		$this->config = $config;
		$this->flags = $flags;
		$this->pool = $this->createPool();
	}

	/**
	 * Returns cache pool
	 * @return Pool
	 */
	public function getPool() {
		return $this->pool;
	}

	/**
	 * Save data in a cache.
	 *
	 * @param string       $key  Name
	 * @param string       $data Value
	 * @param int|DateTime $ttl  Expire value after
	 *
	 * @return bool
	 */
	public function save($key, $data, $ttl = null) {
		if ($this->disabled) {
			return false;
		}

		if (!is_string($key) && !is_int($key)) {
			throw new \InvalidArgumentException('key must be string or integer');
		}

		$item = $this->pool->getItem($this->namespaceKey($key));
		$item->lock();

		$item->setTTL($ttl ? : $this->ttl);

		return $item->set($data)->save();
	}

	/**
	 * Load data from the cache using a given key.
	 *
	 * @param string $key                 Name
	 * @param array  $invalidation_method Stash invalidation method arguments
	 *
	 * @return mixed The stored data or false.
	 */
	public function load($key, $invalidation_method = null) {
		if ($this->disabled) {
			return null;
		}

		if (!is_string($key) && !is_int($key)) {
			throw new \InvalidArgumentException('key must be string or integer');
		}

		$item = $this->pool->getItem($this->namespaceKey($key));
	
		if (is_array($invalidation_method)) {
			call_user_func_array([$item, 'setInvalidationMethod'], $invalidation_method);
		}
		
		if ($item->isMiss()) {
			return null;
		}

		return $item->get();
	}

	/**
	 * Invalidate a key
	 *
	 * @param string $key Name
	 *
	 * @return bool
	 */
	public function delete($key) {
		if ($this->disabled) {
			return false;
		}

		if (!is_string($key) && !is_int($key)) {
			throw new \InvalidArgumentException('key must be string or integer');
		}

		$this->pool->deleteItem($this->namespaceKey($key));

		return true;
	}

	/**
	 * Clear out all the contents of the cache.
	 *
	 * @return bool
	 */
	public function clear() {
		$this->pool->deleteItems([$this->namespaceKey('')]);

		return true;
	}

	/**
	 * Set the namespace of this cache.
	 * This is useful for cache types (like memcache or static variables) where there is one large
	 * flat area of memory shared across all instances of the cache.
	 *
	 * @param string $namespace Namespace for cache
	 *
	 * @return void
	 */
	public function setNamespace($namespace = "default") {
		$this->namespace = $namespace;
	}

	/**
	 * Get the namespace currently defined.
	 *
	 * @return string
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * Namespace the key
	 *
	 * @param string $key Value name
	 *
	 * @return string
	 */
	public function namespaceKey($key) {
		return "/{$this->getNamespace()}/$key";
	}

	/**
	 * Create a new composite stash pool
	 * @return Pool
	 * @throws \ConfigurationException
	 */
	protected function createPool() {
		$drivers = [];
		$drivers[] = $this->buildApcDriver();
		$drivers[] = $this->buildRedisDriver();
		$drivers[] = $this->buildMemcachedDriver();
		$drivers[] = $this->buildFileSystemDriver();
		$drivers[] = $this->buildEphemeralDriver();
		$drivers[] = $this->buildBlackHoleDriver();
		$drivers = array_filter($drivers);

		if (empty($drivers)) {
			throw new \ConfigurationException("Unable to initialize composite cache without drivers");
		}

		if (count($drivers) > 1) {
			$driver = new Composite([
				'drivers' => $drivers,
			]);
		} else {
			$driver = array_shift($drivers);
		}

		return new Pool($driver);
	}

	/**
	 * Builds APC driver
	 * @return null|Apc
	 */
	protected function buildApcDriver() {
		if (!($this->flags & ELGG_CACHE_APC)) {
			return null;
		}

		if (!extension_loaded('apc') || !ini_get('apc.enabled')) {
			return null;
		}

		return new Apc();
	}

	/**
	 * Builds Redis driver
	 * @return null|Redis
	 */
	protected function buildRedisDriver() {
		if (!($this->flags & ELGG_CACHE_PERSISTENT)) {
			return null;
		}

		if (!$this->config->redis || !$this->config->redis_servers) {
			return null;
		}

		return new Redis([
			'servers' => $this->config->redis_servers,
		]);
	}

	/**
	 * Builds Memcached driver
	 * @return null|Memcache
	 */
	protected function buildMemcachedDriver() {
		if (!($this->flags & ELGG_CACHE_PERSISTENT)) {
			return null;
		}

		if (!$this->config->memcache || !$this->config->memcache_servers) {
			return null;
		}
			
		$has_class = class_exists('Memcache') || class_exists('Memcached');
		if (!$has_class) {
			return null;
		}

		return new Memcache([
			'servers' => $this->config->memcache_servers,
			'options' => [
				'prefix_key' => $this->config->memcache_namespace_prefix,
			]
		]);
	}

	/**
	 * Builds file system driver
	 * @return null|FileSystem
	 */
	protected function buildFileSystemDriver() {
		if (!($this->flags & ELGG_CACHE_FILESYSTEM)) {
			return null;
		}

		$path = $this->config->cacheroot ? : $this->config->dataroot;
		if (!$path) {
			return null;
		}

		return new FileSystem([
			'path' => $path,
		]);
	}

	/**
	 * Builds in-memory driver
	 * @return Ephemeral
	 */
	protected function buildEphemeralDriver() {
		if (!($this->flags & ELGG_CACHE_RUNTIME)) {
			return null;
		}

		return new Ephemeral();
	}

	/**
	 * Builds null cache driver
	 * @return BlackHole
	 */
	protected function buildBlackHoleDriver() {
		if (!($this->flags & ELGG_CACHE_BLACK_HOLE)) {
			return null;
		}

		return new BlackHole();
	}
}

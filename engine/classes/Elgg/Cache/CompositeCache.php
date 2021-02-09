<?php

namespace Elgg\Cache;

use DateTime;
use ElggCache;
use Elgg\Config;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Values;
use Stash\Pool;
use Stash\Driver\Apc;
use Stash\Driver\BlackHole;
use Stash\Driver\Composite;
use Stash\Driver\Ephemeral;
use Stash\Driver\FileSystem;
use Stash\Driver\Memcache;
use Stash\Driver\Redis;

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
	 * @var bool
	 */
	protected $validate_lastcache;

	/**
	 * Constructor
	 *
	 * @param string $namespace          Cache namespace
	 * @param Config $config             Elgg config
	 * @param int    $flags              Start flags
	 * @param bool   $validate_lastcache During load validate ElggConfig::lastcache
	 */
	public function __construct($namespace, Config $config, $flags, bool $validate_lastcache = true) {
		parent::__construct();

		$this->namespace = $namespace;
		$this->config = $config;
		$this->flags = $flags;
		$this->pool = $this->createPool();
		$this->validate_lastcache = $validate_lastcache;
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
	 * @param mixed        $data Value
	 * @param int|DateTime $ttl  Expire value after
	 *
	 * @return bool
	 */
	public function save($key, $data, $ttl = null) {
		if ($this->disabled) {
			return false;
		}

		if (!is_string($key) && !is_int($key)) {
			throw new InvalidArgumentException('key must be string or integer');
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
			throw new InvalidArgumentException('key must be string or integer');
		}

		$item = $this->pool->getItem($this->namespaceKey($key));
	
		if (is_array($invalidation_method)) {
			call_user_func_array([$item, 'setInvalidationMethod'], $invalidation_method);
		}
		
		try {
			if ($item->isMiss()) {
				return null;
			}
			
			if ($this->validate_lastcache && $this->config->lastcache) {
				$expiration_date = Values::normalizeTime($this->config->lastcache);
				$creation = $item->getCreation();
				if ($creation instanceof \DateTime) {
					if ($creation->getTimestamp() < $expiration_date->getTimestamp()) {
						$this->delete($key);
						return null;
					}
				}
			}
			
			return $item->get();
		} catch (\Error $e) {
			// catching parsing errors in file driver, because of potential race conditions during write
			// this will cause corrupted data in the file and will crash the site when reading the file
			elgg_log(__METHOD__ . " failed for key: {$this->getNamespace()}/{$key} with error: {$e->getMessage()}", 'ERROR');
			
			// remove the item from the cache so it can try to generate this item again
			$this->delete($key);
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 * @see ElggCache::delete()
	 */
	public function delete($key) {
		if ($this->disabled) {
			return false;
		}

		if (!is_string($key) && !is_int($key)) {
			throw new InvalidArgumentException('key must be string or integer');
		}

		$this->pool->deleteItem($this->namespaceKey($key));

		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see ElggCache::clear()
	 */
	public function clear() {
		$this->pool->deleteItems([$this->namespaceKey('')]);

		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggCache::invalidate()
	 */
	public function invalidate() {
		// Stash doesn't have invalidation as an action.
		// This is handled during load
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggCache::purge()
	 */
	public function purge() {
		return $this->pool->purge();
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
	 * @throws ConfigurationException
	 */
	protected function createPool() {
		$drivers = [];
		$drivers[] = $this->buildEphemeralDriver();
		$drivers[] = $this->buildApcDriver();
		$drivers[] = $this->buildRedisDriver();
		$drivers[] = $this->buildMemcachedDriver();
		$drivers[] = $this->buildFileSystemDriver();
		$drivers[] = $this->buildLocalFileSystemDriver();
		$drivers[] = $this->buildBlackHoleDriver();
		$drivers = array_filter($drivers);

		if (empty($drivers)) {
			throw new ConfigurationException("Unable to initialize composite cache without drivers");
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

		if (!$this->config->redis || empty($this->config->redis_servers)) {
			return null;
		}
		
		$options = $this->config->redis_options ?: [];
		$options['servers'] = $this->config->redis_servers;
		
		return new Redis($options);
	}

	/**
	 * Builds Memcached driver
	 * @return null|Memcache
	 */
	protected function buildMemcachedDriver() {
		if (!($this->flags & ELGG_CACHE_PERSISTENT)) {
			return null;
		}

		if (!$this->config->memcache || empty($this->config->memcache_servers)) {
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
		
		// make a sepatate folder for Stash caches
		// because Stash assumes all files/folders are made by Stash
		$path .= 'stash' . DIRECTORY_SEPARATOR;

		try {
			return new FileSystem([
				'path' => $path,
			]);
		} catch (\Exception $e) {
			elgg_log(__METHOD__ . " {$e->getMessage()}: {$path}", 'ERROR');
		}
		
		return null;
	}
	
	/**
	 * Builds local file system driver
	 * @return null|FileSystem
	 */
	protected function buildLocalFileSystemDriver() {
		if (!($this->flags & ELGG_CACHE_LOCALFILESYSTEM)) {
			return null;
		}

		$path = $this->config->localcacheroot ? : ($this->config->cacheroot ? : $this->config->dataroot);
		if (!$path) {
			return null;
		}

		// make a sepatate folder for Stash caches
		// because Stash assumes all files/folders are made by Stash
		$path .= 'localstash' . DIRECTORY_SEPARATOR;
		
		try {
			return new FileSystem([
				'path' => $path,
			]);
		} catch (\Exception $e) {
			elgg_log(__METHOD__ . " {$e->getMessage()}: {$path}", 'ERROR');
		}
		
		return null;
	}

	/**
	 * Builds in-memory driver
	 * @return null|Ephemeral
	 */
	protected function buildEphemeralDriver() {
		if (!($this->flags & ELGG_CACHE_RUNTIME)) {
			return null;
		}

		return new Ephemeral();
	}

	/**
	 * Builds null cache driver
	 * @return null|BlackHole
	 */
	protected function buildBlackHoleDriver() {
		if (!($this->flags & ELGG_CACHE_BLACK_HOLE)) {
			return null;
		}

		return new BlackHole();
	}
}

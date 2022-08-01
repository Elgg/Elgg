<?php

namespace Elgg\Cache;

use DateTime;
use ElggCache;
use Elgg\Config;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Values;
use Phpfastcache\CacheManager;
use Phpfastcache\Cluster\ClusterAggregator;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;

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
	 * @var ExtendedCacheItemPoolInterface
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
		
		$ttl = $ttl ?: $this->ttl;

		$item = $this->pool->getItem($this->sanitizeItemKey($key));
		$item->set($data);
		
		if (is_int($ttl)) {
			$item->expiresAfter($ttl);
		} elseif ($ttl instanceof DateTime) {
			$item->expiresAt($ttl);
		}
			
		return $this->pool->save($item);
	}

	/**
	 * Load data from the cache using a given key.
	 *
	 * @param string $key Name
	 *
	 * @return mixed The stored data or false.
	 */
	public function load($key) {
		if ($this->disabled) {
			return null;
		}

		$item = $this->pool->getItem($this->sanitizeItemKey($key));
		if (!$item->isHit()) {
			return null;
		}
		
		if ($this->validate_lastcache && $this->config->lastcache) {
			$expiration_date = Values::normalizeTime($this->config->lastcache);
			
			if ($item->getCreationDate()->getTimestamp() < $expiration_date->getTimestamp()) {
				$this->delete($key);
				return null;
			}
		}
		
		return $item->get();
	}

	/**
	 * {@inheritDoc}
	 * @see ElggCache::delete()
	 */
	public function delete($key) {
		if ($this->disabled) {
			return false;
		}

		return $this->pool->deleteItem($this->sanitizeItemKey($key));
	}

	/**
	 * {@inheritDoc}
	 * @see ElggCache::clear()
	 */
	public function clear() {
		return $this->pool->clear();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggCache::invalidate()
	 */
	public function invalidate() {
		// Phpfastcache doesn't have invalidation as an action.
		// This is handled during load
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggCache::purge()
	 */
	public function purge() {
		// Phpfastcache doesn't have purge as an action
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
	public function setNamespace($namespace = 'default') {
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
	 * Prefixes instance ids with namespace
	 *
	 * @param string $id instance id
	 *
	 * @return string
	 *
	 * @since 4.2
	 */
	protected function prefixInstanceId(string $id): string {
		return "{$this->getNamespace()}_{$id}";
	}
	
	/**
	 * Sanitizes item key for cache
	 *
	 * @param mixed $key input key
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 *
	 * @since 4.2
	 */
	protected function sanitizeItemKey($key): string {
		if (!is_string($key) && !is_int($key)) {
			throw new InvalidArgumentException('key must be string or integer');
		}
		
		return str_replace(['{', '}', '(', ')', '/', '\\', '@', ':'], '_', "{$key}");
	}

	/**
	 * Create a new cluster/pool of drivers
	 *
	 * @return ExtendedCacheItemPoolInterface
	 * @throws ConfigurationException
	 */
	protected function createPool() {
		
		$drivers = [];
		$drivers[] = $this->buildApcDriver();
		$drivers[] = $this->buildRedisDriver();
		$drivers[] = $this->buildMemcachedDriver();
		$drivers[] = $this->buildFileSystemDriver();
		$drivers[] = $this->buildLocalFileSystemDriver();
		$drivers[] = $this->buildBlackHoleDriver();
		$drivers = array_filter($drivers);
		
		if (empty($drivers)) {
			// the memory driver can only be used as a stand-alone driver (not combined in a cluster)
			// other drivers already have a built in memory storage (default allowed in config)
			$ephemeral = $this->buildEphemeralDriver();
			if (!empty($ephemeral)) {
				$drivers[] = $ephemeral;
			}
		}
		
		if (empty($drivers)) {
			throw new ConfigurationException('Unable to initialize composite cache without drivers');
		}
				
		if (count($drivers) === 1) {
			return array_shift($drivers);
		}
		
		$cluster = new ClusterAggregator($this->getNamespace());
		foreach ($drivers as $driver) {
			$cluster->aggregateDriver($driver);
		}
		
		$cluster_driver = $cluster->getCluster();
		
		$cluster_driver->getConfig()->setPreventCacheSlams(true);
		$cluster_driver->getConfig()->setDefaultChmod(0770);
		$cluster_driver->getConfig()->setUseStaticItemCaching(true);
		$cluster_driver->getConfig()->setItemDetailedDate(true);
		
		return $cluster_driver;
	}

	/**
	 * Builds APC driver
	 * @return null
	 */
	protected function buildApcDriver() {
		if (!($this->flags & ELGG_CACHE_APC)) {
			return null;
		}

		if (!extension_loaded('apc') || !ini_get('apc.enabled')) {
			return null;
		}

		elgg_deprecated_notice('The APC driver for caching is no longer available. Switch to an alternative method for caching.', '4.1');
		
		return null;
	}

	/**
	 * Builds Redis driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildRedisDriver() {
		if (!($this->flags & ELGG_CACHE_PERSISTENT)) {
			return null;
		}

		if (!self::isRedisAvailable()) {
			return null;
		}
		
		$config = \Elgg\Cache\Config\Redis::fromElggConfig($this->namespace, $this->config);
		if (empty($config)) {
			return null;
		}
				
		return CacheManager::getInstance('Redis', $config, $this->prefixInstanceId('redis'));
	}

	/**
	 * Builds Memcached driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildMemcachedDriver() {
		if (!($this->flags & ELGG_CACHE_PERSISTENT)) {
			return null;
		}
			
		if (!self::isMemcacheAvailable()) {
			return null;
		}
		
		$config = \Elgg\Cache\Config\Memcached::fromElggConfig($this->namespace, $this->config);
		if (empty($config)) {
			return null;
		}
		
		return CacheManager::getInstance('Memcached', $config, $this->prefixInstanceId('memcache'));
	}

	/**
	 * Builds file system driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildFileSystemDriver() {
		if (!($this->flags & ELGG_CACHE_FILESYSTEM)) {
			return null;
		}
		
		$config = \Elgg\Cache\Config\Files::fromElggConfig($this->namespace, $this->config);
		if (empty($config)) {
			return null;
		}
		
		try {
			return CacheManager::getInstance('Files', $config, $this->prefixInstanceId('files'));
		} catch (\Phpfastcache\Exceptions\PhpfastcacheIOException $e) {
			if (!$this->config->installer_running) {
				elgg_log($e, 'ERROR');
			}
		}
		
		return null;
	}
	
	/**
	 * Builds local file system driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildLocalFileSystemDriver() {
		if (!($this->flags & ELGG_CACHE_LOCALFILESYSTEM)) {
			return null;
		}

		$config = \Elgg\Cache\Config\LocalFiles::fromElggConfig($this->namespace, $this->config);
		if (empty($config)) {
			return null;
		}
		
		try {
			return CacheManager::getInstance('Files', $config, $this->prefixInstanceId('local_files'));
		} catch (\Phpfastcache\Exceptions\PhpfastcacheIOException $e) {
			if (!$this->config->installer_running) {
				elgg_log($e, 'ERROR');
			}
		}
		
		return null;
	}

	/**
	 * Builds in-memory driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildEphemeralDriver() {
		if (!($this->flags & ELGG_CACHE_RUNTIME)) {
			return null;
		}
		
		$config = new \Phpfastcache\Drivers\Memstatic\Config();
		
		$config->setUseStaticItemCaching(true);
		$config->setItemDetailedDate(true);
		
		return CacheManager::getInstance('Memstatic', $config, $this->prefixInstanceId('memstatic'));
	}

	/**
	 * Builds null cache driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildBlackHoleDriver() {
		if (!($this->flags & ELGG_CACHE_BLACK_HOLE)) {
			return null;
		}
		
		$config = new \Phpfastcache\Drivers\Devnull\Config();
		
		return CacheManager::getInstance('Devnull', $config, $this->prefixInstanceId('devnull'));
	}
	
	/**
	 * Helper function to check if memcache is available
	 *
	 * @return bool
	 *
	 * @since 4.2
	 */
	public static function isMemcacheAvailable(): bool {
		return class_exists('Memcached');
	}
	
	/**
	 * Helper function to check if Redis is available
	 *
	 * @return bool
	 *
	 * @since 4.2
	 */
	public static function isRedisAvailable(): bool {
		return extension_loaded('Redis');
	}
}

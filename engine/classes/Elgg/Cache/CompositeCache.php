<?php

namespace Elgg\Cache;

use Elgg\Config;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Values;
use Phpfastcache\CacheManager;
use Phpfastcache\Cluster\ClusterAggregator;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\Exceptions\PhpfastcacheRootException;

/**
 * Composite cache pool
 *
 * @internal
 */
class CompositeCache extends BaseCache {
	
	public const CACHE_BLACK_HOLE = 1;
	public const CACHE_RUNTIME = 2;
	public const CACHE_FILESYSTEM = 4;
	public const CACHE_PERSISTENT = 8;
	public const CACHE_LOCALFILESYSTEM = 32;
	
	/**
	 * TTL of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	protected int $ttl = 86400;

	/**
	 * @var ExtendedCacheItemPoolInterface
	 */
	protected $pool;

	/**
	 * Constructor
	 *
	 * @param string $namespace          Cache namespace
	 * @param Config $config             Elgg config
	 * @param int    $flags              Start flags
	 * @param bool   $validate_lastcache During load validate ElggConfig::lastcache
	 */
	public function __construct(protected string $namespace, protected Config $config, protected int $flags, protected bool $validate_lastcache = true) {
		$this->pool = $this->createPool();
	}

	/**
	 * Save data in a cache.
	 *
	 * @param string        $key          Name
	 * @param mixed         $data         Value
	 * @param int|\DateTime $expire_after Expire value after
	 *
	 * @return bool
	 */
	public function save($key, $data, $expire_after = null) {
		if ($this->disabled) {
			return false;
		}
		
		$expire_after = $expire_after ?: $this->ttl;

		$item = $this->pool->getItem($this->sanitizeItemKey($key));
		$item->set($data);
		
		if (is_int($expire_after)) {
			$item->expiresAfter($expire_after);
		} elseif ($expire_after instanceof \DateTime) {
			$item->expiresAt($expire_after);
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
		
		try {
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
		} catch (PhpfastcacheRootException $e) {
			// something wrong with the cache
			elgg_log($e->getMessage(), 'ERROR');
			return null;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($key) {
		if ($this->disabled) {
			return false;
		}

		return $this->pool->deleteItem($this->sanitizeItemKey($key));
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		return $this->pool->clear();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function invalidate() {
		// Phpfastcache doesn't have invalidation as an action.
		// This is handled during load
		return true;
	}
	
	/**
	 * {@inheritdoc}
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
	public function setNamespace($namespace = 'default'): void {
		$this->namespace = $namespace;
	}

	/**
	 * Get the namespace currently defined.
	 *
	 * @return string
	 */
	public function getNamespace(): string {
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
	protected function createPool(): ExtendedCacheItemPoolInterface {
		
		$drivers = [];
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
		$cluster_driver->setConfig(new ConfigurationOption([
			'useStaticItemCaching' => true,
			'itemDetailedDate' => true,
		]));
		
		return $cluster_driver;
	}

	/**
	 * Builds Redis driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildRedisDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_PERSISTENT)) {
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
	protected function buildMemcachedDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_PERSISTENT)) {
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
	protected function buildFileSystemDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_FILESYSTEM)) {
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
				elgg_log($e, \Psr\Log\LogLevel::ERROR);
			}
		}
		
		return null;
	}
	
	/**
	 * Builds local file system driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildLocalFileSystemDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_LOCALFILESYSTEM)) {
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
				elgg_log($e, \Psr\Log\LogLevel::ERROR);
			}
		}
		
		return null;
	}

	/**
	 * Builds in-memory driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildEphemeralDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_RUNTIME)) {
			return null;
		}
		
		$config = new \Phpfastcache\Drivers\Memory\Config();
		
		$config->setUseStaticItemCaching(true);
		$config->setItemDetailedDate(true);
		
		return CacheManager::getInstance('Memory', $config, $this->prefixInstanceId('memory'));
	}

	/**
	 * Builds null cache driver
	 * @return null|ExtendedCacheItemPoolInterface
	 */
	protected function buildBlackHoleDriver(): ?ExtendedCacheItemPoolInterface {
		if (!($this->flags & self::CACHE_BLACK_HOLE)) {
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

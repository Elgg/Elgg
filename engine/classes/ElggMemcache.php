<?php

/**
 * Memcache wrapper class.
 */
class ElggMemcache extends \ElggSharedMemoryCache {

	/**
	 * TTL of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	private $ttl = 86400;

	/**
	 * @var \Stash\Pool
	 */
	private $stash_pool;

	/**
	 * Constructor
	 *
	 * @param string      $namespace The namespace for this cache to write to
	 * @param \Stash\Pool $pool      The cache pool to use. Default is memcache.
	 * @param int         $ttl       The TTL in seconds. Default is from $CONFIG->memcache_expires.
	 *
	 * @throws ConfigurationException
	 *
	 * @see _elgg_get_memcache() Core developers should use this instead of direct construction.
	 */
	public function __construct($namespace = 'default', \Stash\Pool $pool = null, $ttl = null) {
		parent::__construct();

		$this->setNamespace($namespace);

		if (!$pool) {
			$pool = _elgg_services()->memcacheStashPool;
			if (!$pool) {
				throw new \ConfigurationException('No memcache servers defined, please populate the $this->CONFIG->memcache_servers variable');
			}
		}
		$this->stash_pool = $pool;

		if ($ttl === null) {
			$ttl = _elgg_services()->config->get('memcache_expires');
		}
		if (isset($ttl)) {
			$this->ttl = $ttl;
		}
		
		// make sure memcache is reset
		_elgg_services()->events->registerHandler('cache:flush', 'system', array($this, 'clear'));
	}

	/**
	 * Set the default TTL.
	 *
	 * @param int $ttl The TTL in seconds from now. Default is no expiration.
	 *
	 * @return void
	 */
	public function setDefaultExpiry($ttl = 0) {
		$this->ttl = $ttl;
	}

	/**
	 * Combine a key with the namespace.
	 * Memcache can only accept <250 char key. If the given key is too long it is shortened.
	 *
	 * @param string $key The key
	 *
	 * @return string The new key.
	 */
	private function makeMemcacheKey($key) {
		// using stacks grouping http://www.stashphp.com/Grouping.html#stacks
		// this will allowing clearing the namespace later
		return "/{$this->getNamespace()}/$key";
	}

	/**
	 * Saves a name and value to the cache
	 *
	 * @param string  $key  Name
	 * @param string  $data Value
	 * @param integer $ttl  TTL of cache item (seconds), 0 for no expiration, null for default.
	 *
	 * @return bool
	 */
	public function save($key, $data, $ttl = null) {
		$key = $this->makeMemcacheKey($key);

		if ($ttl === null) {
			$ttl = $this->ttl;
		}

		$item = $this->stash_pool->getItem($key);

		$item->set($data);
		$item->expiresAfter($ttl);
		$result = $item->save();

		if ($result) {
			_elgg_services()->logger->info("MEMCACHE: SAVE SUCCESS $key");
		} else {
			_elgg_services()->logger->error("MEMCACHE: SAVE FAIL $key");
		}

		return $result;
	}

	/**
	 * Retrieves data.
	 *
	 * @param string $key     Name of data to retrieve
	 * @param int    $unused1 Unused
	 * @param int    $unused2 Unused
	 *
	 * @return mixed
	 */
	public function load($key, $unused1 = 0, $unused2 = null) {
		$key = $this->makeMemcacheKey($key);

		$item = $this->stash_pool->getItem($key);

		$item->setInvalidationMethod(\Stash\Invalidation::NONE);
		$value = $item->get();

		if ($item->isMiss()) {
			_elgg_services()->logger->info("MEMCACHE: LOAD MISS $key");
			return false;
		}

		_elgg_services()->logger->info("MEMCACHE: LOAD HIT $key");

		return $value;
	}

	/**
	 * Delete data
	 *
	 * @param string $key Name of data
	 *
	 * @return bool
	 */
	public function delete($key) {
		$key = $this->makeMemcacheKey($key);
		return $this->stash_pool->getItem($key)->clear();
	}

	/**
	 * Clears all values in the namespace of this cache
	 *
	 * @return bool
	 */
	public function clear() {
		// using stacks grouping http://www.stashphp.com/Grouping.html#stacks
		// this will clear all key keys beneath it
		return $this->stash_pool->getItem("/{$this->getNamespace()}")->clear();
	}
	
	/**
	 * Set the namespace of this cache.
	 *
	 * This will also add the Memcache namespace prefix as defined in settings.php
	 *
	 * @param string $namespace Namespace for cache
	 *
	 * @return void
	 */
	public function setNamespace($namespace = "default") {
		$config_prefix = _elgg_services()->config->getVolatile('memcache_namespace_prefix');
		if ($config_prefix) {
			$namespace = $config_prefix . $namespace;
		}
		
		parent::setNamespace($namespace);
	}
}

<?php

namespace Elgg\Cache;

/**
 * Cache Service
 *
 * @internal
 * @since 6.1
 */
abstract class CacheService {
	
	protected CompositeCache $cache;
	
	protected bool $enabled = true;

	/**
	 * Purges the caches
	 *
	 * @return void
	 */
	public function purge(): void {
		$this->cache->purge();
	}
	
	/**
	 * Invalidates the caches
	 *
	 * @return void
	 */
	public function invalidate(): void {
		$this->cache->invalidate();
	}
	
	/**
	 * Clears the caches
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->cache->clear();
	}
	
	/**
	 * Saves data in the cache
	 *
	 * @param string             $key          Identifier of the cached item
	 * @param mixed              $data         The data to be saved
	 * @param int|\DateTime|null $expire_after Number of seconds to expire the cache after
	 *
	 * @return bool
	 */
	public function save(string $key, mixed $data, int|\DateTime $expire_after = null): bool {
		return $this->isEnabled() && $this->cache->save($key, $data, $expire_after);
	}
	
	/**
	 * Retrieve the contents of a cached item
	 *
	 * @param string $key Identifier of the cached item
	 *
	 * @return mixed null if key not found in cache
	 */
	public function load(string $key): mixed {
		return $this->isEnabled() ? $this->cache->load($key) : null;
	}
	
	/**
	 * Deletes the contents of a cached item
	 *
	 * @param string $key Identifier of the cached item
	 *
	 * @return bool
	 */
	public function delete(string $key): bool {
		return $this->cache->delete($key);
	}
	
	/**
	 * Is the cache enabled
	 *
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->enabled;
	}
	
	/**
	 * Enables the cache
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->enabled = true;
	}
	
	/**
	 * Disables the cache
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->enabled = false;
	}
}

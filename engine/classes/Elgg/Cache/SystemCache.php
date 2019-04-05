<?php
namespace Elgg\Cache;

use Elgg\Cacheable;
use Elgg\Config;
use Elgg\Profilable;
use ElggCache;

/**
 * System Cache
 *
 * @access private
 * @since  1.10.0
 */
class SystemCache {

	use Profilable;
	use Cacheable;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param ElggCache $cache  Elgg disk cache
	 * @param Config    $config Elgg config
	 */
	public function __construct(ElggCache $cache, Config $config) {
		$this->cache = $cache;
		$this->config = $config;
	}

	/**
	 * Reset the system cache by deleting the caches
	 *
	 * @return void
	 */
	function reset() {
		$this->cache->clear();
	}
	
	/**
	 * Saves a system cache.
	 *
	 * @param string $type The type or identifier of the cache
	 * @param string $data The data to be saved
	 * @return bool
	 */
	function save($type, $data) {
		if ($this->isEnabled()) {
			return $this->cache->save($type, $data);
		}
	
		return false;
	}
	
	/**
	 * Retrieve the contents of a system cache.
	 *
	 * @param string $type The type of cache to load
	 * @return string
	 */
	function load($type) {
		if ($this->isEnabled()) {
			$cached_data = $this->cache->load($type);
			if ($cached_data) {
				return $cached_data;
			}
		}
	
		return null;
	}
	
	/**
	 * Deletes the contents of a system cache.
	 *
	 * @param string $type The type of cache to delete
	 * @return string
	 */
	function delete($type) {
		return $this->cache->delete($type);
	}
	
	/**
	 * Is system cache enabled
	 *
	 * @return bool
	 */
	function isEnabled() {
		return (bool) $this->config->system_cache_enabled;
	}
	
	/**
	 * Enables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' config with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function enable() {
		$this->config->save('system_cache_enabled', 1);
		$this->reset();
	}
	
	/**
	 * Disables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' config with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function disable() {
		$this->config->save('system_cache_enabled', 0);
		$this->reset();
	}
	
	/**
	 * Initializes the simplecache lastcache variable and creates system cache files
	 * when appropriate.
	 *
	 * @return void
	 *
	 * @access private
	 */
	function init() {
		if (!$this->isEnabled()) {
			return;
		}

		// cache system data if enabled and not loaded
		if (!$this->config->system_cache_loaded) {
			_elgg_services()->views->cacheConfiguration($this);
		}
	}
}

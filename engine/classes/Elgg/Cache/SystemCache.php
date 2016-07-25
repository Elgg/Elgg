<?php
namespace Elgg\Cache;

use Elgg\Profilable;
use Elgg\Config;
use ElggFileCache;
use Elgg\Database\Datalist;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Cache
 * @since      1.10.0
 */
class SystemCache {
	use Profilable;

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var ElggFileCache
	 */
	private $cache;

	/**
	 * @var Datalist
	 */
	private $datalist;

	/**
	 * Constructor
	 *
	 * @param ElggFileCache $cache    Elgg disk cache
	 * @param Config        $config   Elgg config
	 * @param Datalist      $datalist Elgg datalist
	 */
	public function __construct(ElggFileCache $cache, Config $config, Datalist $datalist) {
		$this->cache = $cache;
		$this->config = $config;
		$this->datalist = $datalist;
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
	 * Is system cache enabled
	 *
	 * @return bool
	 */
	function isEnabled() {
		return (bool)$this->config->getVolatile('system_cache_enabled');
	}
	
	/**
	 * Enables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' datalist with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function enable() {
		$this->datalist->set('system_cache_enabled', 1);
		$this->config->set('system_cache_enabled', 1);
		$this->reset();
	}
	
	/**
	 * Disables the system disk cache.
	 *
	 * Uses the 'system_cache_enabled' datalist with a boolean value.
	 * Resets the system cache.
	 *
	 * @return void
	 */
	function disable() {
		$this->datalist->set('system_cache_enabled', 0);
		$this->config->set('system_cache_enabled', 0);
		$this->reset();
	}
	
	/**
	 * Loads the system cache during engine boot
	 *
	 * @see elgg_reset_system_cache()
	 * @access private
	 */
	function loadAll() {
		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		$this->config->set('system_cache_loaded', false);

		if (!_elgg_services()->views->configureFromCache($this)) {
			return;
		}

		$data = $this->load('view_types');
		if (!is_string($data)) {
			return;
		}
		$GLOBALS['_ELGG']->view_types = unserialize($data);

		// Note: We don't need view_overrides for operation. Inspector can pull this from the cache

		$this->config->set('system_cache_loaded', true);

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}
	
	/**
	 * Initializes the simplecache lastcache variable and creates system cache files
	 * when appropriate.
	 *
	 * @access private
	 */
	function init() {
		if (!$this->isEnabled()) {
			return;
		}

		// cache system data if enabled and not loaded
		if (!$this->config->getVolatile('system_cache_loaded')) {
			$this->save('view_types', serialize($GLOBALS['_ELGG']->view_types));

			_elgg_services()->views->cacheConfiguration($this);
		}
	
		if (!$GLOBALS['_ELGG']->i18n_loaded_from_cache) {

			_elgg_services()->translator->reloadAllTranslations();

			foreach ($GLOBALS['_ELGG']->translations as $lang => $map) {
				$this->save("$lang.lang", serialize($map));
			}
		}
	}
}
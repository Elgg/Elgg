<?php
namespace Elgg\Cache;

use Elgg\Values;
use ElggCache;

/**
 * In memory cache of known private settings values stored by entity.
 *
 * @access private
 */
class PrivateSettingsCache {

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * Constructor
	 *
	 * @param ElggCache $cache Cache
	 */
	public function __construct(ElggCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Load data from the cache using a given key.
	 *
	 * @param string $key Name
	 *
	 * @return mixed|null The stored data or null if it's a miss
	 */
	public function load($key) {
		return $this->cache->load($key);
	}
	
	/**
	 * Save data in a cache.
	 *
	 * @param string $key          Name
	 * @param mixed  $data         Value
	 * @param int    $expire_after Number of seconds to expire the cache after
	 *
	 * @return bool
	 */
	public function save($key, $data, $expire_after = null) {
		return $this->cache->save($key, $data, $expire_after);
	}

	/**
	 * Populate the cache from a set of entities
	 *
	 * @param int[] ...$guids Array of or single GUIDs
	 * @return array|null [guid => [settings]]
	 */
	public function populateFromEntities(...$guids) {
		try {
			$guids = Values::normalizeGuids($guids);
		} catch (\DataFormatException $e) {
			return null;
		}

		if (empty($guids)) {
			return null;
		}

		$cached_values = [];

		foreach ($guids as $i => $guid) {
			$value = $this->load($guid);
			if ($value !== null) {
				$cached_values[$guid] = $value;
				unset($guids[$i]);
			}
		}

		if (empty($guids)) {
			return $cached_values;
		}
		
		$data = _elgg_services()->privateSettings->getAllForGUIDs($guids);
		
		// store always for every guid, even if there is no settings
		foreach ($guids as $guid) {
			$settings = elgg_extract($guid, $data, []);
			
			$this->save($guid, $settings);
			$cached_values[$guid] = $settings;
		}
		
		return $cached_values;
	}
}

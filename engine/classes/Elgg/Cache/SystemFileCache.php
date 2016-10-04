<?php
namespace Elgg\Cache;

use ElggFileCache;

/**
 * File cache customized for system cache.
 *
 * - Does not save/load when disabled
 * - Can store arbitrary data
 *
 * @since 2.3
 */
class SystemFileCache extends ElggFileCache {

	const PREFIX_STRING = '_0_';
	const PREFIX_SERIALIZED = '_1_';

	/**
	 * @var bool
	 */
	protected $enabled = true;

	/**
	 * Set whether the cache is enabled
	 *
	 * @param bool $enabled
	 * @internal For use by SystemCache only
	 * @access private
	 */
	public function setEnabled($enabled) {
		$this->enabled = (bool)$enabled;
	}

	/**
	 * Save data in cache
	 *
	 * @param string $key  Cache key
	 * @param mixed  $data Cache data (don't store the value `false` directly)
	 *
	 * @return bool
	 */
	public function save($key, $data) {
		if (!$this->enabled) {
			return true;
		}

		if (is_string($data)) {
			$data = self::PREFIX_STRING . $data;
		} else {
			$data = self::PREFIX_SERIALIZED . serialize($data);
		}

		return parent::save($key, $data);
	}

	/**
	 * Load cached data
	 *
	 * @param string $key      Cache key
	 * @param int    $ignored1 Unused
	 * @param null   $ignored2 Unused
	 *
	 * @return mixed False if not cached
	 */
	public function load($key, $ignored1 = 0, $ignored2 = null) {
		if (!$this->enabled) {
			return false;
		}

		$data = parent::load($key);
		if (!$data) {
			return false;
		}

		if (0 === strpos($data, self::PREFIX_STRING)) {
			return substr($data, strlen(self::PREFIX_STRING));
		}

		if (0 === strpos($data, self::PREFIX_SERIALIZED)) {
			$data = substr($data, strlen(self::PREFIX_SERIALIZED));
			return unserialize($data);
		}

		return false;
	}

	/**
	 * Cache the output of an expensive function, if the system cache is enabled
	 *
	 * @param string   $key  Cache key
	 * @param callable $func Function that requires no arguments. The result must be serializable.
	 * @param int      $ttl  TTL for result (seconds). 0 for no expiration
	 *
	 * @return mixed
	 */
	public function cacheCall($key, callable $func, $ttl = 0) {
		if (!$this->enabled) {
			return call_user_func($func);
		}

		$cached = $this->load($key);
		if (is_array($cached) && isset($cached['time'])) {
			if (!$ttl || (time() < $cached['time'] + $ttl)) {
				return $cached['data'];
			}
		}

		$return = call_user_func($func);
		$this->save($key, [
			'time' => time(),
			'data' => $return,
		]);
		return $return;
	}
}

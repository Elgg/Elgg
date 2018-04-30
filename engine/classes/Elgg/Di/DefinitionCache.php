<?php

namespace Elgg\Di;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ClearableCache;
use Doctrine\Common\Cache\FlushableCache;
use Doctrine\Common\Cache\MultiGetCache;
use Doctrine\Common\Cache\MultiPutCache;
use Elgg\Cacheable;

/**
 * DI cache
 *
 * @access private
 * @internal
 */
class DefinitionCache implements Cache,
								 FlushableCache,
								 ClearableCache,
								 MultiGetCache,
								 MultiPutCache {

	use Cacheable;

	/**
	 * Constructor
	 *
	 * @param \ElggCache $cache Cache
	 */
	public function __construct(\ElggCache $cache) {
		$this->cache = $cache;
	}

	/**
	 * {@inheritdoc}
	 */
	public function fetch($id) {
		$value = $this->cache->load($id);
		if (!$value) {
			return false;
		}
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function contains($id) {
		return $this->fetch($id) !== null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($id, $data, $lifeTime = 0) {
		return $this->cache->save($id, $data, $lifeTime ? : null);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($id) {
		return $this->cache->delete($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStats() {
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteAll() {
		return $this->cache->clear();
	}

	/**
	 * {@inheritdoc}
	 */
	public function flushAll() {
		return $this->cache->clear();
	}

	/**
	 * {@inheritdoc}
	 */
	function fetchMultiple(array $keys) {
		$values = [];
		foreach ($keys as $key) {
			$values[] = $this->cache->load($key);
		}

		return $values;
	}

	/**
	 * {@inheritdoc}
	 */
	function saveMultiple(array $keysAndValues, $lifetime = 0) {
		foreach ($keysAndValues as $key => $value) {
			$this->save($key, $value, $lifetime);
		}

		return true;
	}
}

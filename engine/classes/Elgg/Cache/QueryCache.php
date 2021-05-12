<?php

namespace Elgg\Cache;

use Elgg\Traits\Loggable;

/**
 * Volatile cache for select queries
 *
 * Queries and their results are stored in this cache as:
 * <code>
 * $DB_QUERY_CACHE[query hash] => array(result1, result2, ... resultN)
 * </code>
 *
 * @internal
 */
class QueryCache extends LRUCache {
	
	use Loggable;
	
	/**
	 * @var bool Is this cache disabled by a config flag
	 */
	protected $config_disabled = false;

	/**
	 * @var bool Is this cache disabled during runtime
	 */
	protected $runtime_enabled = false;
	
	/**
	 * @inheritdoc
	 *
	 * @param int  $size            max items in LRU cache
	 * @param bool $config_disabled is this cache allowed to be used
	 */
	public function __construct(int $size = 50, bool $config_disabled = false) {
		
		$this->config_disabled = $config_disabled;
		
		parent::__construct($size);
	}
	
	/**
	 * Enable the query cache
	 *
	 * This does not take precedence over the \Elgg\Database\Config setting.
	 *
	 * @return void
	 */
	public function enable() {
		$this->runtime_enabled = true;
	}
	
	/**
	 * Disable the query cache
	 *
	 * This is useful for special scripts that pull large amounts of data back
	 * in single queries.
	 *
	 * @return void
	 */
	public function disable() {
		$this->runtime_enabled = false;
		
		$this->clear();
	}
	
	/**
	 * Checks if this cache is enabled
	 *
	 * @return boolean
	 */
	public function isEnabled() {
		if ($this->config_disabled) {
			return false;
		}
		
		return $this->runtime_enabled;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function clear() {
		parent::clear();
		
		$this->getLogger()->info('Query cache invalidated');
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get($key, $default = null) {
		if (!$this->isEnabled()) {
			return $default;
		}
		
		$result = parent::get($key, $default);
		
		$this->getLogger()->info("DB query results returned from cache (hash: {$key})");
		
		return $result;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value) {
		if (!$this->isEnabled()) {
			return;
		}
		
		parent::set($key, $value);
		
		$this->getLogger()->info("DB query results cached (hash: {$key})");
	}
	
	/**
	 * Returns a hashed key for storage in the cache
	 *
	 * @param string $sql    query
	 * @param array  $params optional params
	 * @param string $extras optional extras
	 *
	 * @return string
	 */
	public function getHash(string $sql, array $params = [], string $extras = '') {
		
		$query_id = $sql . '|';
		if (!empty($params)) {
			$query_id .= serialize($params) . '|';
		}
		
		$query_id .= $extras;

		// MD5 yields smaller mem usage for cache and cleaner logs
		return md5($query_id);
	}
	
}

<?php

namespace Elgg\Cache;

use Elgg\Config;

/**
 * Volatile cache for select queries
 *
 * @internal
 */
class QueryCache extends CacheService {
	
	protected int $query_cache_limit = 50;
	
	protected array $keys = [];
	
	/**
	 * Constructor
	 *
	 * @param Config $config Elgg config
	 */
	public function __construct(protected Config $config) {
		$flags = CompositeCache::CACHE_RUNTIME;
		
		$this->cache = new CompositeCache('query_cache', $this->config, $flags);
		
		$this->enabled = $this->config->db_disable_query_cache !== true;
		if (isset($this->config->db_query_cache_limit)) {
			$this->query_cache_limit = (int) $this->config->db_query_cache_limit;
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function purge(): void {
		$this->keys = [];
		
		parent::purge();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function invalidate(): void {
		$this->keys = [];
		
		parent::invalidate();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function clear(): void {
		$this->keys = [];
		
		parent::clear();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function save(string $key, mixed $data, int|\DateTime $expire_after = null): bool {
		$result = parent::save($key, $data, $expire_after);
		if ($result && !isset($this->keys[$key])) {
			$this->keys[$key] = true;
			
			if (count($this->keys) > $this->query_cache_limit) {
				$this->delete(array_key_first($this->keys));
			}
		}
		
		return $result;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function load(string $key): mixed {
		if (isset($this->keys[$key])) {
			// when used move key to bottom
			unset($this->keys[$key]);
			$this->keys[$key] = true;
		}
		
		return parent::load($key);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function delete(string $key): bool {
		unset($this->keys[$key]);
		
		return parent::delete($key);
	}
}

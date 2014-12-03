<?php
namespace Elgg\Cache;

use Stash;

/**
 * An in-memory implementation of a cache pool.
 * 
 * NB: Data put into this cache is not persisted between requests.
 * 
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package    Elgg
 * @subpackage Cache
 * @since      1.10.0
 *
 * @access private
 */
final class MemoryPool implements Pool {
	/** @var Pool */
	private $pool;
	
	/**
	 * Happens to use Stash for the in-memory caching, but this
	 * should be considered just an implementation detail.
	 */
	public function __construct() {
		$this->pool = StashPool::createEphemeral();
	}
	
	/** @inheritDoc */
	public function get($key, callable $callback) {
		return $this->pool->get($key, $callback);
	}
	
	/** @inheritDoc */
	public function invalidate($key) {
		$this->pool->invalidate($key);
	}

	/** @inheritDoc */
	public function put($key, $value) {
		$this->pool->put($key, $value);
	}
}
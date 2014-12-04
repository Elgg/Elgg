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
	/**
	 * @var array
	 */
	private $values = array();
	
	/** @inheritDoc */
	public function get($key, callable $callback) {
		assert(is_string($key) || is_int($key));

		if (!array_key_exists($key, $this->values)) {
			$this->values[$key] = call_user_func($callback);
		}
		return $this->values[$key];
	}
	
	/** @inheritDoc */
	public function invalidate($key) {
		assert(is_string($key) || is_int($key));

		unset($this->values[$key]);
	}

	/** @inheritDoc */
	public function put($key, $value) {
		assert(is_string($key) || is_int($key));

		$this->values[$key] = $value;
	}
}

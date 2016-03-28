<?php
namespace Elgg\Cache\Pool;

use Elgg\Cache\Pool;

/**
 * Implements the caching API but doesn't actually do any caching.
 *
 * Pass an instance of this class as a cache value to turn off caching.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 1.10.0
 *
 * @access private
 */
final class Noop implements Pool {
	/** @inheritDoc */
	public function get($key, callable $callback = null, $default = null) {
		if (!$callback) {
			return $default;
		}
		return call_user_func($callback);
	}
	
	/** @inheritDoc */
	public function invalidate($key) {
		// values are always expired, so nothing to do
	}

	/** @inheritDoc */
	public function put($key, $value) {
		// values are always expired, so nothing to do
	}
}
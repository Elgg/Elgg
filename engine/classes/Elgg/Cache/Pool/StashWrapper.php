<?php
namespace Elgg\Cache\Pool;

use Elgg\Cache\Pool;

use Stash;

/**
 * Defers to Stash for the meat of the caching logic.
 * 
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @since 1.10.0
 *
 * @access private
 */
final class StashWrapper implements Pool {

	/** @var Stash\Pool */
	private $stash;

	/**
	 * Constructor
	 *
	 * @param Stash\Pool $stash The Stash instance to be decorated.
	 */
	public function __construct(Stash\Pool $stash) {
		$this->stash = $stash;
	}

	/** @inheritDoc */
	public function get($key, callable $callback = null, $default = null) {
		assert(is_string($key) || is_int($key));

		$item = $this->stash->getItem((string)$key);

		$result = $item->get();

		if ($item->isMiss()) {
			if (!$callback) {
				return $default;
			}

			$item->lock();

			$result = call_user_func($callback);

			$this->stash->save($item->set($result));
		}

		return $result;
	}

	/** @inheritDoc */
	public function invalidate($key) {
		assert(is_string($key) || is_int($key));
		
		$this->stash->getItem((string)$key)->clear();
	}
	
	/** @inheritDoc */
	public function put($key, $value) {
		assert(is_string($key) || is_int($key));

		$this->stash->getItem((string)$key)->set($value);
	}
	
	/**
	 * Create an in-memory implementation of the pool.
	 *
	 * @return StashPool
	 */
	public static function createEphemeral() {
		return new self(new Stash\Pool(new Stash\Driver\Ephemeral()));
	}
}
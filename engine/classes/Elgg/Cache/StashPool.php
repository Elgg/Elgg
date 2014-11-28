<?php
namespace Elgg\Cache;

use Stash;

/**
 * Defers to Stash for the meat of the caching logic.
 * 
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @package    Elgg
 * @subpackage Cache
 * @since      1.10.0
 *
 * @access private
 */
final class StashPool implements Pool {

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
	public function get($key, callable $callback) {
		assert(is_string($key) || is_int($key));

		$item = $this->stash->getItem((string)$key);

		$result = $item->get();

		if ($item->isMiss()) {
			$item->lock();

			$result = call_user_func($callback);

			$item->set($result);
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
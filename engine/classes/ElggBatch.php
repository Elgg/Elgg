<?php

use Elgg\BatchResult;

/**
 * A lazy-loading proxy for a result array from a fetching function
 *
 * A batch can be counted or iterated over via foreach, where the batch will
 * internally fetch results several rows at a time. This allows you to efficiently
 * work on large result sets without loading all results in memory.
 *
 * A batch can run operations for any function that supports an options array
 * and supports the keys "offset", "limit", and "count". This is usually used
 * with elgg_get_entities() and friends, elgg_get_annotations(), and
 * elgg_get_metadata(). In fact, those functions will return results as
 * batches by passing in "batch" as true.
 *
 * Unlike a real array, direct access of results is not supported.
 *
 * If you pass a valid PHP callback, all results will be run through that
 * callback. You can still foreach() through the result set after.  Valid
 * PHP callbacks can be a string, an array, or a closure.
 * {@link http://php.net/manual/en/language.pseudo-types.php}
 *
 * The callback function must accept 3 arguments: an entity, the getter
 * used, and the options used.
 *
 * Results from the callback are stored in callbackResult. If the callback
 * returns only booleans, callbackResults will be the combined result of
 * all calls. If no entities are processed, callbackResults will be null.
 *
 * If the callback returns anything else, callbackresult will be an indexed
 * array of whatever the callback returns.  If returning error handling
 * information, you should include enough information to determine which
 * result you're referring to.
 *
 * Don't combine returning bools and returning something else.
 *
 * Note that returning false will not stop the foreach.
 *
 * @warning If your callback or foreach loop deletes or disable entities
 * you MUST call setIncrementOffset(false) or set that when instantiating.
 * This forces the offset to stay what it was in the $options array.
 *
 * @example
 * <code>
 * // using foreach
 * $batch = new \ElggBatch('elgg_get_entities', array());
 * $batch->setIncrementOffset(false);
 *
 * foreach ($batch as $entity) {
 * 	   $entity->disable();
 * }
 *
 * // using both a callback
 * $callback = function($result, $getter, $options) {
 * 	   var_dump("Looking at annotation id: $result->id");
 *     return true;
 * }
 *
 * $batch = new \ElggBatch('elgg_get_annotations', array('guid' => 2), $callback);
 *
 * // get a batch from an Elgg getter function
 * $batch = elgg_get_entities([
 *     'batch' => true,
 * ]);
 * </code>
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 * @since      1.8
 */
class ElggBatch implements BatchResult {

	/**
	 * The objects to iterate over.
	 *
	 * @var array
	 */
	private $results = [];

	/**
	 * The function used to get results.
	 *
	 * @var callable
	 */
	private $getter = null;

	/**
	 * The given $options to alter and pass to the getter.
	 *
	 * @var array
	 */
	private $options = [];

	/**
	 * The number of results to grab at a time.
	 *
	 * @var int
	 */
	private $chunkSize = 25;

	/**
	 * A callback function to pass results through.
	 *
	 * @var callable
	 */
	private $callback = null;

	/**
	 * Start after this many results.
	 *
	 * @var int
	 */
	private $offset = 0;

	/**
	 * Stop after this many results.
	 *
	 * @var int
	 */
	private $limit = 0;

	/**
	 * Number of processed results.
	 *
	 * @var int
	 */
	private $retrievedResults = 0;

	/**
	 * The index of the current result within the current chunk
	 *
	 * @var int
	 */
	private $resultIndex = 0;

	/**
	 * The index of the current chunk
	 *
	 * @var int
	 */
	private $chunkIndex = 0;

	/**
	 * The number of results iterated through
	 *
	 * @var int
	 */
	private $processedResults = 0;

	/**
	 * Is the getter a valid callback
	 *
	 * @var bool
	 */
	private $validGetter = null;

	/**
	 * The result of running all entities through the callback function.
	 *
	 * @var mixed
	 */
	public $callbackResult = null;

	/**
	 * If false, offset will not be incremented. This is used for callbacks/loops that delete.
	 *
	 * @var bool
	 */
	private $incrementOffset = true;

	/**
	 * Entities that could not be instantiated during a fetch
	 *
	 * @var \stdClass[]
	 */
	private $incompleteEntities = [];

	/**
	 * Total number of incomplete entities fetched
	 *
	 * @var int
	 */
	private $totalIncompletes = 0;

	/**
	 * Batches operations on any elgg_get_*() or compatible function that supports
	 * an options array.
	 *
	 * Instead of returning all objects in memory, it goes through $chunk_size
	 * objects, then requests more from the server.  This avoids OOM errors.
	 *
	 * @param callable $getter     The function used to get objects.  Usually
	 *                           an elgg_get_*() function, but can be any valid PHP callback.
	 * @param array  $options    The options array to pass to the getter function. If limit is
	 *                           not set, 10 is used as the default. In most cases that is not
	 *                           what you want.
	 * @param mixed  $callback   An optional callback function that all results will be passed
	 *                           to upon load.  The callback needs to accept $result, $getter,
	 *                           $options.
	 * @param int    $chunk_size The number of entities to pull in before requesting more.
	 *                           You have to balance this between running out of memory in PHP
	 *                           and hitting the db server too often.
	 * @param bool   $inc_offset Increment the offset on each fetch. This must be false for
	 *                           callbacks that delete rows. You can set this after the
	 *                           object is created with {@link \ElggBatch::setIncrementOffset()}.
	 */
	public function __construct(callable $getter, $options, $callback = null, $chunk_size = 25,
			$inc_offset = true) {

		$this->getter = $getter;
		$this->options = $options;
		$this->callback = $callback;
		$this->chunkSize = $chunk_size;
		$this->setIncrementOffset($inc_offset);

		if ($this->chunkSize <= 0) {
			$this->chunkSize = 25;
		}

		// store these so we can compare later
		$this->offset = elgg_extract('offset', $options, 0);
		$this->limit = elgg_extract('limit', $options, _elgg_config()->default_limit);

		// if passed a callback, create a new \ElggBatch with the same options
		// and pass each to the callback.
		if ($callback && is_callable($callback)) {
			$batch = new \ElggBatch($getter, $options, null, $chunk_size, $inc_offset);

			$all_results = null;

			foreach ($batch as $result) {
				$result = call_user_func($callback, $result, $getter, $options);

				if (!isset($all_results)) {
					if ($result === true || $result === false || $result === null) {
						$all_results = $result;
					} else {
						$all_results = [];
					}
				}

				if (($result === true || $result === false || $result === null) && !is_array($all_results)) {
					$all_results = $result && $all_results;
				} else {
					$all_results[] = $result;
				}
			}

			$this->callbackResult = $all_results;
		}
	}

	/**
	 * Fetches the next chunk of results
	 *
	 * @return bool
	 */
	private function getNextResultsChunk() {

		// always reset results.
		$this->results = [];

		if (!isset($this->validGetter)) {
			$this->validGetter = is_callable($this->getter);
		}

		if (!$this->validGetter) {
			return false;
		}

		$limit = $this->chunkSize;

		// if someone passed limit = 0 they want everything.
		if ($this->limit != 0) {
			if ($this->retrievedResults >= $this->limit) {
				return false;
			}

			// if original limit < chunk size, set limit to original limit
			// else if the number of results we'll fetch if greater than the original limit
			if ($this->limit < $this->chunkSize) {
				$limit = $this->limit;
			} elseif ($this->retrievedResults + $this->chunkSize > $this->limit) {
				// set the limit to the number of results remaining in the original limit
				$limit = $this->limit - $this->retrievedResults;
			}
		}

		if ($this->incrementOffset) {
			$offset = $this->offset + $this->retrievedResults;
		} else {
			$offset = $this->offset + $this->totalIncompletes;
		}

		$current_options = [
			'limit' => $limit,
			'offset' => $offset,
			'__ElggBatch' => $this,
		];

		$options = array_merge($this->options, $current_options);

		$this->incompleteEntities = [];
		$this->results = call_user_func($this->getter, $options);

		// batch result sets tend to be large; we don't want to cache these.
		_elgg_services()->db->disableQueryCache();

		$num_results = count($this->results);
		$num_incomplete = count($this->incompleteEntities);

		$this->totalIncompletes += $num_incomplete;

		if ($this->incompleteEntities) {
			// pad the front of the results with nulls representing the incompletes
			array_splice($this->results, 0, 0, array_pad([], $num_incomplete, null));
			// ...and skip past them
			reset($this->results);
			for ($i = 0; $i < $num_incomplete; $i++) {
				next($this->results);
			}
		}

		if ($this->results) {
			$this->chunkIndex++;

			// let the system know we've jumped past the nulls
			$this->resultIndex = $num_incomplete;

			$this->retrievedResults += ($num_results + $num_incomplete);
			if ($num_results == 0) {
				// This fetch was *all* incompletes! We need to fetch until we can either
				// offer at least one row to iterate over, or give up.
				return $this->getNextResultsChunk();
			}
			_elgg_services()->db->enableQueryCache();
			return true;
		} else {
			_elgg_services()->db->enableQueryCache();
			return false;
		}
	}

	/**
	 * Increment the offset from the original options array? Setting to
	 * false is required for callbacks that delete rows.
	 *
	 * @param bool $increment Set to false when deleting data
	 * @return void
	 */
	public function setIncrementOffset($increment = true) {
		$this->incrementOffset = (bool) $increment;
	}

	/**
	 * Set chunk size
	 * @param int $size Size
	 * @return void
	 */
	public function setChunkSize($size = 25) {
		$this->chunkSize = $size;
	}
	/**
	 * Implements Iterator
	 */

	/**
	 * {@inheritdoc}
	 */
	public function rewind() {
		$this->resultIndex = 0;
		$this->retrievedResults = 0;
		$this->processedResults = 0;

		// only grab results if we haven't yet or we're crossing chunks
		if ($this->chunkIndex == 0 || $this->limit > $this->chunkSize) {
			$this->chunkIndex = 0;
			$this->getNextResultsChunk();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function current() {
		return current($this->results);
	}

	/**
	 * {@inheritdoc}
	 */
	public function key() {
		return $this->processedResults;
	}

	/**
	 * {@inheritdoc}
	 */
	public function next() {
		// if we'll be at the end.
		if (($this->processedResults + 1) >= $this->limit && $this->limit > 0) {
			$this->results = [];
			return false;
		}

		// if we'll need new results.
		if (($this->resultIndex + 1) >= $this->chunkSize) {
			if (!$this->getNextResultsChunk()) {
				$this->results = [];
				return false;
			}

			$result = current($this->results);
		} else {
			// the function above resets the indexes, so only inc if not
			// getting new set
			$this->resultIndex++;
			$result = next($this->results);
		}

		$this->processedResults++;
		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid() {
		if (!is_array($this->results)) {
			return false;
		}
		$key = key($this->results);
		return ($key !== null && $key !== false);
	}

	/**
	 * Count the total results available at this moment.
	 *
	 * As this performs a separate query, the count returned may not match the number of results you can
	 * fetch via iteration on a very active DB.
	 *
	 * @see Countable::count()
	 * @return int
	 */
	public function count() {
		if (!is_callable($this->getter)) {
			$inspector = new \Elgg\Debug\Inspector();
			throw new RuntimeException("Getter is not callable: " . $inspector->describeCallable($this->getter));
		}

		$options = array_merge($this->options, ['count' => true]);

		return call_user_func($this->getter, $options);
	}
}

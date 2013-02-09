<?php
/**
 * Efficiently run operations on batches of results for any function
 * that supports an options array.
 *
 * This is usually used with elgg_get_entities() and friends,
 * elgg_get_annotations(), and elgg_get_metadata().
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
 * $batch = new ElggBatch('elgg_get_entities', array());
 * $batch->setIncrementOffset(false);
 *
 * foreach ($batch as $entity) {
 * 	$entity->disable();
 * }
 *
 * // using both a callback
 * $callback = function($result, $getter, $options) {
 * 	var_dump("Looking at annotation id: $result->id");
 *  return true;
 * }
 *
 * $batch = new ElggBatch('elgg_get_annotations', array('guid' => 2), $callback);
 * </code>
 *
 * @package    Elgg.Core
 * @subpackage DataModel
 * @link       http://docs.elgg.org/DataModel/ElggBatch
 * @since      1.8
 */
class ElggBatch
	implements Iterator {

	/**
	 * The objects to interator over.
	 *
	 * @var array
	 */
	private $results = array();

	/**
	 * The function used to get results.
	 *
	 * @var mixed A string, array, or closure, or lamda function
	 */
	private $getter = null;

	/**
	 * The number of results to grab at a time.
	 *
	 * @var int
	 */
	private $chunkSize = 25;

	/**
	 * A callback function to pass results through.
	 *
	 * @var mixed A string, array, or closure, or lamda function
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
	 * Batches operations on any elgg_get_*() or compatible function that supports
	 * an options array.
	 *
	 * Instead of returning all objects in memory, it goes through $chunk_size
	 * objects, then requests more from the server.  This avoids OOM errors.
	 *
	 * @param string $getter     The function used to get objects.  Usually
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
	 *                           object is created with {@see ElggBatch::setIncrementOffset()}.
	 */
	public function __construct($getter, $options, $callback = null, $chunk_size = 25,
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
		$this->limit = elgg_extract('limit', $options, 10);

		// if passed a callback, create a new ElggBatch with the same options
		// and pass each to the callback.
		if ($callback && is_callable($callback)) {
			$batch = new ElggBatch($getter, $options, null, $chunk_size, $inc_offset);

			$all_results = null;

			foreach ($batch as $result) {
				if (is_string($callback)) {
					$result = $callback($result, $getter, $options);
				} else {
					$result = call_user_func_array($callback, array($result, $getter, $options));
				}

				if (!isset($all_results)) {
					if ($result === true || $result === false || $result === null) {
						$all_results = $result;
					} else {
						$all_results = array();
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
		// reset memory caches after first chunk load
		if ($this->chunkIndex > 0) {
			global $DB_QUERY_CACHE, $ENTITY_CACHE;
			$DB_QUERY_CACHE = $ENTITY_CACHE = array();
		}

		// always reset results.
		$this->results = array();

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
			$offset = $this->offset;
		}

		$current_options = array(
			'limit' => $limit,
			'offset' => $offset
		);

		$options = array_merge($this->options, $current_options);
		$getter = $this->getter;

		if (is_string($getter)) {
			$this->results = $getter($options);
		} else {
			$this->results = call_user_func_array($getter, array($options));
		}

		if ($this->results) {
			$this->chunkIndex++;
			$this->resultIndex = 0;
			$this->retrievedResults += count($this->results);
			return true;
		} else {
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
	 * Implements Iterator
	 */

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::rewind()
	 * @return void
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
	 * PHP Iterator Interface
	 *
	 * @see Iterator::current()
	 * @return mixed
	 */
	public function current() {
		return current($this->results);
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::key()
	 * @return int
	 */
	public function key() {
		return $this->processedResults;
	}

	/**
	 * PHP Iterator Interface
	 *
	 * @see Iterator::next()
	 * @return mixed
	 */
	public function next() {
		// if we'll be at the end.
		if (($this->processedResults + 1) >= $this->limit && $this->limit > 0) {
			$this->results = array();
			return false;
		}

		// if we'll need new results.
		if (($this->resultIndex + 1) >= $this->chunkSize) {
			if (!$this->getNextResultsChunk()) {
				$this->results = array();
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
	 * PHP Iterator Interface
	 *
	 * @see Iterator::valid()
	 * @return bool
	 */
	public function valid() {
		if (!is_array($this->results)) {
			return false;
		}
		$key = key($this->results);
		return ($key !== NULL && $key !== FALSE);
	}
}

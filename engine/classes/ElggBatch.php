<?php
/**
 * Efficiently run operations on batches of results for any function
 * that supports an options array.
 *
 * This is usually used with elgg_get_entities() and friends, elgg_get_annotations()
 * and elgg_get_metadata().
 *
 * If pass a valid PHP callback, all results will be run through that callback.
 * You can still foreach() through the result set after.  Valid PHP callbacks
 * can be a string, an array, or a closure.
 * {@link http://php.net/manual/en/language.pseudo-types.php}
 *
 * @example
 * <code>
 * $batch = new ElggBatch('elgg_get_entities', array());
 *
 * foreach ($batch as $entity) {
 * 	$entity->disable();
 * }
 *
 * $callback = function($result, $getter, $options) {
 * 	var_dump("Going to delete annotation id: $result->id");
 * }
 *
 * $batch = new ElggBatch('elgg_get_annotations', array('guid' => 2), $callback);
 *
 * foreach ($batch as $annotation) {
 * 	$annotation->delete();
 * }
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
	 * Stop of this many results.
	 *
	 * @var unknown_type
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
	 *
	 *
	 * @var unknown_type
	 */
	public $processedResults = 0;

	/**
	 * Is the getter a valid callback
	 *
	 * @var bool
	 */
	private $validGetter = null;

	/**
	 * Batches operations on any elgg_get_*() functions that supports
	 * an options array.
	 *
	 * Instead of returning all objects in memory, it goes through $chunk_size
	 * objects, then requests more from the server.  This avoids OOM errors.
	 *
	 * @param string $getter     The function used to get objects.  Usually
	 *                           an elgg_get_*() function, but can be any valid PHP callback.
	 * @param array  $options    The options array to pass to the getter function
	 * @param mixed  $callback   An optional callback function that all results will be passed
	 *                           to upon load.  The callback needs to accept $result, $getter,
	 *                           $options.
	 * @param int    $chunk_size The number of entities to pull in before requesting more.
	 *                           You have to balance this between running out of memory in PHP
	 *                           and hitting the db server too often.
	 */
	public function __construct($getter, $options, $callback = null, $chunk_size = 25) {
		$this->getter = $getter;
		$this->options = $options;
		$this->callback = $callback;
		$this->chunkSize = $chunk_size;

		// store these so we can compare later
		$this->offset = elgg_get_array_value('offset', $options, 0);
		$this->limit = elgg_get_array_value('limit', $options, 10);

		// if passed a callback, create a new ElggBatch with the same options
		// and pass each to the callback.
		if ($callback && is_callable($callback)) {
			$batch = new ElggBatch($getter, $options, null, $chunk_size);

			foreach ($batch as $result) {
				if (is_string($callback)) {
					$callback($result, $getter, $options);
				} else {
					call_user_func_array($callback, array($result, $getter, $options));
				}
			}
		}
	}

	/**
	 * Fetches the next chunk of results
	 *
	 * @return bool
	 */
	private function getNextResultsChunk() {
		$this->results = array();

		if (!isset($this->validGetter)) {
			$this->validGetter = is_callable($this->getter);
		}

		if (!$this->validGetter) {
			return false;
		}

		if ($this->retrievedResults >= $this->limit) {
			return false;
		}

		// if original limit < chunk size, set limit to original limit
		if ($this->limit < $this->chunkSize) {
			$limit = $this->limit;
		}

		// if the number of results we'll fetch is greater than the original limit,
		// set the limit to the number of results remaining in the original limit
		elseif ($this->retrievedResults + $this->chunkSize > $this->limit) {
			$limit = $this->limit - $this->retrievedResults;
		}

		// everything else is the chunk size
		else {
			$limit = $this->chunkSize;
		}

		$current_options = array(
			'limit' => $limit,
			'offset' => $this->offset + $this->retrievedResults
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
		if ($this->processedResults + 1 >= $this->limit) {
			$this->results = array();
			return false;
		}

		// if we'll need new results.
		if ($this->resultIndex + 1 >= $this->chunkSize) {
			if (!$this->getNextResultsChunk()) {
				$this->results = array();
				return false;
			}

			$result = current($this->results);
		} else {
			// the function above resets the indexes, so don't only inc if not
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
		$key = key($this->results);
		return ($key !== NULL && $key !== FALSE);
	}
}
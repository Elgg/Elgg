<?php
namespace Elgg\Database;

use Elgg\Database;

/**
 * Iterate over a DB result set, returning stdClass objects optionally filtered through a callable.
 */
final class ResultIterator implements \Countable, \Iterator {

	/**
	 * @var int
	 */
	private $position = -1;

	/**
	 * @var resource
	 */
	private $result;

	/**
	 * @var int
	 */
	private $num_rows;

	/**
	 * @var \stdClass
	 */
	private $row;

	/**
	 * @var bool
	 */
	private $is_valid = false;

	/**
	 * @var callable
	 */
	private $callback;

	/**
	 * Constructor
	 *
	 * @param resource $result   MySQL result set
	 * @param callable $callback Callable applied to each row
	 *
	 * @internal Devs should not use this class directly
	 * @access private
	 */
	public function __construct($result, $callback = null) {
		if (!is_resource($result) || get_resource_type($result) !== 'mysql result') {
			throw new \InvalidArgumentException('$result must be a MySQL result');
		}
		$this->result = $result;

		if ($callback && !is_callable($callback)) {
			throw new \InvalidArgumentException('If given, $callback must be a callable');
		}
		$this->callback = $callback;

		$this->num_rows = mysql_num_rows($result);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind() {
		if (!$this->num_rows) {
			return;
		}

		mysql_data_seek($this->result, 0);
		$this->position = -1;
		$this->fetch();
	}

	/**
	 * {@inheritdoc}
	 */
	public function current() {
		return $this->row;
	}

	/**
	 * {@inheritdoc}
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * {@inheritdoc}
	 */
	public function next() {
		$this->fetch();
	}

	/**
	 * {@inheritdoc}
	 */
	public function valid() {
		return $this->is_valid;
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		return $this->num_rows;
	}

	/**
	 * Destructor
	 *
	 * @return void
	 */
	public function __destruct() {
		if (is_resource($this->result)) {
			mysql_free_result($this->result);
		}
	}

	/**
	 * Fetch the next row
	 *
	 * @return void
	 */
	private function fetch() {
		if (!$this->num_rows) {
			return;
		}

		$this->row = mysql_fetch_object($this->result);
		$this->is_valid = ($this->row !== false);
		if ($this->callback) {
			$this->row = call_user_func($this->callback, $this->row);
		}
		$this->position += 1;
	}
}

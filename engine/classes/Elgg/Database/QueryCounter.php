<?php

/**
 * Count queries performed
 *
 * Do not use directly.
 *
 * @see _elgg_db_get_query_counter()
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
class Elgg_Database_QueryCounter {

	/**
	 * @var int
	 */
	protected $initial;

	/**
	 * @var Elgg_Database
	 */
	protected $db;

	/**
	 * @param Elgg_Database $db
	 */
	public function __construct(Elgg_Database $db) {
		$this->db = $db;
		$this->initial = $db->getQueryCount();
	}

	/**
	 * Get the number of queries performed since the object was constructed
	 *
	 * @return int
	 */
	public function getDelta() {
		return $this->db->getQueryCount() - $this->initial;
	}

	/**
	 * Create header X-ElggQueryDelta-* with the delta
	 *
	 * @see getDelta()
	 *
	 * @param string $key
	 */
	public function setDeltaHeader($key = 'Default') {
		$delta = $this->getDelta();

		header("X-ElggQueryDelta-$key: $delta", true);
	}

	/**
	 * Get SCRIPT element which sends the delta to console.log
	 *
	 * @see getDelta()
	 *
	 * @param string $key
	 *
	 * @return string markup of SCRIPT element
	 */
	public function getDeltaScript($key = 'Default') {
		$delta = $this->getDelta();

		$msg = json_encode("ElggQueryDelta-$key: $delta");
		return "<script>console.log($msg)</script>";
	}
}

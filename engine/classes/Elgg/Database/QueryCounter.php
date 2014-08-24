<?php
namespace Elgg\Database;

/**
 * Count queries performed
 *
 * Do not use directly. Use _elgg_db_get_query_counter().
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
class QueryCounter {

	/**
	 * @var int
	 */
	protected $initial;

	/**
	 * @var \Elgg\Database
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param \Elgg\Database $db Elgg's database
	 */
	public function __construct(\Elgg\Database $db) {
		$this->db = $db;
		$this->initial = $db->getQueryCount();
	}

	/**
	 * Get the number of queries performed since the object was constructed
	 *
	 * @return int # of queries
	 */
	public function getDelta() {
		return $this->db->getQueryCount() - $this->initial;
	}

	/**
	 * Create header X-ElggQueryDelta-* with the delta
	 *
	 * @see getDelta()
	 *
	 * @param string $key Key to add to HTTP header name
	 *
	 * @return void
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
	 * @param string $key Key to display in console log
	 *
	 * @return string markup of SCRIPT element
	 */
	public function getDeltaScript($key = 'Default') {
		$delta = $this->getDelta();

		$msg = json_encode("ElggQueryDelta-$key: $delta");
		return "<script>console.log($msg)</script>";
	}
}


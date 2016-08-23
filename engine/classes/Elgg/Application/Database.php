<?php
namespace Elgg\Application;

use Elgg\Application;
use Elgg\Database as ElggDb;
use Elgg\Timer;
use Elgg\Logger;

/**
 * Elgg 2.0 public database API
 *
 * This is returned by elgg()->getDb() or Application::start()->getDb(), but is only a 2.0 compatibility
 * wrapper for the real Elgg\Database.
 *
 * @todo This extends \Elgg\Database because in 2.0 we promised to return that type. In 3.0 we should
 *       no longer extend \Elgg\Database.
 *
 * @see \Elgg\Application::getDb for more details.
 *
 * @property-read string $prefix Elgg table prefix (read only)
 */
class Database extends ElggDb {

	/**
	 * The "real" database instance
	 *
	 * @var ElggDb
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param ElggDb $db The Elgg database
	 * @access private
	 */
	public function __construct(ElggDb $db) {
		$this->db = $db;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData($query, $callback = '', array $params = []) {
		return $this->db->getData($query, $callback, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDataRow($query, $callback = '', array $params = []) {
		return $this->db->getDataRow($query, $callback, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function insertData($query, array $params = []) {
		return $this->db->insertData($query, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateData($query, $getNumRows = false, array $params = []) {
		return $this->db->updateData($query, $getNumRows, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteData($query, array $params = []) {
		return $this->db->deleteData($query, $params);
	}

	/**
	 * {@inheritdoc}
	 * @deprecated 2.3 Read the "prefix" property
	 */
	public function getTablePrefix() {
		if (function_exists('elgg_deprecated_notice')) {
			elgg_deprecated_notice(__METHOD__ . ' is deprecated. Read the "prefix" property', '2.3');
		}
		return $this->db->prefix;
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitizeInt($value, $signed = true) {
		return $this->db->sanitizeInt($value, $signed);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitizeString($value) {
		return $this->db->sanitizeString($value);
	}

	/**
	 * Handle magic property reads
	 *
	 * @param string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		if ($name === 'prefix') {
			return $this->db->prefix;
		}

		throw new \RuntimeException("Cannot read property '$name'");
	}

	/**
	 * Handle magic property writes
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		throw new \RuntimeException("Cannot write property '$name'");
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function fingerprintCallback($callback) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		return $this->db->fingerprintCallback($callback);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function setTimer(Timer $timer) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->setTimer($timer);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function setLogger(Logger $logger) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->setLogger($logger);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function setupConnections() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->setupConnections();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function connect($type = "readwrite") {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->connect($type);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function runSqlScript($scriptlocation) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->runSqlScript($scriptlocation);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function registerDelayedQuery($query, $type, $handler = "", array $params = []) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		return $this->db->registerDelayedQuery($query, $type, $handler, $params);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function executeDelayedQueries() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->executeDelayedQueries();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function enableQueryCache() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->enableQueryCache();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function disableQueryCache() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->disableQueryCache();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function assertInstalled() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		$this->db->assertInstalled();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function getQueryCount() {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		return $this->db->getQueryCount();
	}

	/**
	 * {@inheritdoc}
	 *
	 * @deprecated 2.1 This method will not be available on this class in 3.0
	 */
	public function getServerVersion($type) {
		elgg_deprecated_notice(__METHOD__ . " was deprecated and will be removed in 3.0", '2.1');
		return $this->db->getServerVersion($type);
	}
}

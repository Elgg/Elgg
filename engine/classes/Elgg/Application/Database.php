<?php
namespace Elgg\Application;

use Elgg\Database as ElggDb;

/**
 * Elgg 3.0 public database API
 *
 * This is returned by elgg()->getDb() or Application::start()->getDb().
 *
 * @see \Elgg\Application::getDb for more details.
 *
 * @property-read string $prefix Elgg table prefix (read only)
 */
class Database {

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
		return $this->db->{$name};
	}

	/**
	 * Handle magic property writes
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->db->{$name} = $value;
	}
}

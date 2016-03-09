<?php
namespace Elgg\Database;

use Elgg\Database;

/**
 * Factory for query builder objects
 */
class QueryService {

	/**
	 * @var Database
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param Database $db Elgg database
	 * @access private
	 * @internal
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}

	/**
	 * Build a select query
	 *
	 * @see QueryBuilder::select
	 *
	 * @param mixed $select The selection expressions.
	 *
	 * @return QueryBuilder
	 */
	public function select($select = null) {
		return $this->db->getQueryBuilder('read')->select($select);
	}

	/**
	 * Build an update query
	 *
	 * @see QueryBuilder::update
	 *
	 * @param string $update The table whose rows are subject to the update.
	 * @param string $alias  The table alias used in the constructed query.
	 *
	 * @return QueryBuilder
	 */
	public function update($update = null, $alias = null) {
		return $this->db->getQueryBuilder('write')->update($update, $alias);
	}

	/**
	 * Build an insert query
	 *
	 * @see QueryBuilder::insert
	 *
	 * @param string $insert The table into which the rows should be inserted.
	 *
	 * @return QueryBuilder
	 */
	public function insert($insert = null) {
		return $this->db->getQueryBuilder('write')->insert($insert);
	}

	/**
	 * Build a delete query
	 *
	 * @see QueryBuilder::delete
	 *
	 * @param string $delete The table whose rows are subject to the deletion.
	 * @param string $alias  The table alias used in the constructed query.
	 *
	 * @return QueryBuilder
	 */
	public function delete($delete = null, $alias = null) {
		return $this->db->getQueryBuilder('write')->delete($delete, $alias);
	}
}

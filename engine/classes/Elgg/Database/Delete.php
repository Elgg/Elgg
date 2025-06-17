<?php

namespace Elgg\Database;

/**
 * Query builder for updating data in the database
 */
class Delete extends QueryBuilder {
	
	/**
	 * Returns a QueryBuilder for deleting data from a given table
	 *
	 * @param string $table table name
	 *
	 * @return static
	 */
	public static function fromTable(string $table): static {
		$connection = _elgg_services()->db->getConnection(DbConfig::WRITE);
		$qb = new static($connection);
		$qb->delete($table);

		return $qb;
	}
}

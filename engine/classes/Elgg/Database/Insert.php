<?php

namespace Elgg\Database;

/**
 * Query builder for inserting data into the database
 */
class Insert extends QueryBuilder {
	
	/**
	 * Returns a QueryBuilder for inserting data in a given table
	 *
	 * @param string $table table name
	 *
	 * @return static
	 */
	public static function intoTable(string $table): static {
		$connection = _elgg_services()->db->getConnection(DbConfig::WRITE);
		$qb = new static($connection);
		$qb->insert($table);

		return $qb;
	}
}

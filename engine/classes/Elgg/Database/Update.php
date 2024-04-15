<?php

namespace Elgg\Database;

/**
 * Query builder for updating data in the database
 */
class Update extends QueryBuilder {
	
	/**
	 * Returns a QueryBuilder for updating data in a given table
	 *
	 * @param string $table table name
	 *
	 * @return static
	 */
	public static function table(string $table): static {
		$connection = _elgg_services()->db->getConnection('write');
		$qb = new static($connection);
		$qb->update($table);

		return $qb;
	}
}

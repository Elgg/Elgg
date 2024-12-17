<?php

namespace Elgg\Database;

/**
 * Query builder for fetching data from the database
 */
class Select extends QueryBuilder {
	
	/**
	 * Returns a QueryBuilder for selecting data from a given table
	 *
	 * @param string      $table table name
	 * @param string|null $alias table alias
	 *
	 * @return static
	 */
	public static function fromTable(string $table, ?string $alias = null): static {
		$connection = _elgg_services()->db->getConnection('read');

		$qb = new static($connection);
		$qb->from($table, $alias);

		return $qb;
	}
}

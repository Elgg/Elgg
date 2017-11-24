<?php

namespace Elgg\Database;

/**
 * Query builder for fetching data from the database
 */
class Select extends QueryBuilder {

	/**
	 * {@inheritdoc}
	 */
	public static function fromTable($table, $alias = null) {
		$connection = _elgg_services()->db->getConnection('read');

		$qb = new static($connection);
		$qb->from($table, $alias);

		return $qb;
	}

}

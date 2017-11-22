<?php

namespace Elgg\Database;

/**
 * Query builder for inserting data into the database
 */
class Insert extends QueryBuilder {

	/**
	 * {@inheritdoc}
	 */
	public static function create($table, $alias = null) {
		$connection = _elgg_services()->db->getConnection('write');
		$qb = new static($connection);
		$qb->insert($table);

		return $qb;
	}

}

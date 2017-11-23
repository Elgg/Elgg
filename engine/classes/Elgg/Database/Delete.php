<?php

namespace Elgg\Database;

/**
 * Query builder for updating data in the database
 */
class Delete extends QueryBuilder {

	/**
	 * {@inheritdoc}
	 */
	public static function create($table, $alias = null) {
		$connection = _elgg_services()->db->getConnection('write');
		$qb = new static($connection);
		$qb->delete($table, $alias);

		return $qb;
	}
}

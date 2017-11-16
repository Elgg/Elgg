<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds clauses for filtering entities by their properties in entity_relationships table
 */
class RelationshipWhereClause extends WhereClause {

	/**
	 * @var int[]
	 */
	public $ids;

	/**
	 * @var string[]
	 */
	public $names;

	/**
	 * @var int[]|\ElggEntity[]
	 */
	public $subject_guids;

	/**
	 * @var int[]|\ElggEntity[]
	 */
	public $object_guids;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_before;

	/**
	 * @var bool
	 */
	public $inverse;

	/**
	 * @var string
	 */
	public $join_on = 'guid';

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];

		$wheres[] = parent::prepare($qb, $table_alias);

		$wheres[] = $qb->compare($alias('id'), '=', $this->ids, ELGG_VALUE_ID);
		$wheres[] = $qb->compare($alias('relationship'), '=', $this->names, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('guid_one'), '=', $this->subject_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('guid_two'), '=', $this->object_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		return $qb->merge($wheres);
	}

}

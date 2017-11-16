<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds clauses for filtering entities by properties in metadata table
 */
class MetadataWhereClause extends WhereClause {

	/**
	 * @var int[]
	 */
	public $ids;

	/**
	 * @var int[]
	 */
	public $entity_guids;

	/**
	 * @var string[]
	 */
	public $names;

	/**
	 * @var string
	 */
	public $comparison = '=';

	/**
	 * @var mixed
	 */
	public $values;

	/**
	 * @var string
	 */
	public $value_type = ELGG_VALUE_STRING;

	/**
	 * @var bool
	 */
	public $case_sensitive = true;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_before;

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
		$wheres[] = $qb->compare($alias('name'), '=', $this->names, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('value'), $this->comparison, $this->values, $this->value_type, $this->case_sensitive);
		$wheres[] = $qb->compare($alias('entity_guid'), '=', $this->entity_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		return $qb->merge($wheres);
	}

}

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
	 *
	 * @note previously called subject_guids
	 */
	public $guid_one;

	/**
	 * @var int[]|\ElggEntity[]
	 *
	 * @note previously called object_guids
	 */
	public $guid_two;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_before;

	public bool $inverse = false;

	public string $join_on = 'guid';

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
		$wheres[] = $qb->compare($alias('guid_one'), '=', $this->guid_one, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('guid_two'), '=', $this->guid_two, ELGG_VALUE_GUID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		return $qb->merge($wheres);
	}

	/**
	 * Build a new RelationshipWhereClause
	 *
	 * @param array $attributes parameters for clause
	 *
	 * @return static
	 *
	 * @since 6.3
	 */
	public static function factory(array $attributes): static {
		$result = new static();

		$array_attributes = [
			'ids',
			'names',
			'guid_one',
			'guid_two',
		];
		foreach ($array_attributes as $array_key) {
			if (isset($attributes[$array_key])) {
				$result->{$array_key} = (array) $attributes[$array_key];
			}
		}

		$singular_attributes = [
			'join_on',
			'inverse',
			'created_after',
			'created_before',
		];
		foreach ($singular_attributes as $array_key) {
			if (isset($attributes[$array_key])) {
				$result->{$array_key} = $attributes[$array_key];
			}
		}

		return $result;
	}
}

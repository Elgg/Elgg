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
	 * @var mixed
	 */
	public $values;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_before;

	public string $value_type = ELGG_VALUE_STRING;

	public bool $case_sensitive = true;

	public string $comparison = '=';

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

	/**
	 * Build a new MetadataWhereClause
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
			'entity_guids',
			'names',
			'values',
		];
		foreach ($array_attributes as $array_key) {
			if (isset($attributes[$array_key])) {
				$result->{$array_key} = (array) $attributes[$array_key];
			}
		}

		$singular_attributes = [
			'comparison',
			'value_type',
			'case_sensitive',
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

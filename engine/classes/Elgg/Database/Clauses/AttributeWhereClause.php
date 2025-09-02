<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds queries for matching entities by their attributes
 */
class AttributeWhereClause extends WhereClause {

	/**
	 * @var string[]
	 */
	public $names;

	/**
	 * @var mixed
	 */
	public $values;

	public string $comparison = '=';

	public string $value_type = ELGG_VALUE_STRING;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);

		foreach ((array) $this->names as $name) {
			$wheres[] = $qb->compare($alias($name), $this->comparison, $this->values, $this->value_type);
		}

		return $qb->merge($wheres);
	}

	/**
	 * Build a new AttributeWhereClause
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
		];
		foreach ($singular_attributes as $array_key) {
			if (isset($attributes[$array_key])) {
				$result->{$array_key} = $attributes[$array_key];
			}
		}

		return $result;
	}
}

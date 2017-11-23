<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds quereis for matching entities by their attributes
 */
class AttributeWhereClause extends WhereClause {

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
	public $case_sensitive;

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
			$wheres[] = $qb->compare($alias($name), $this->comparison, $this->values, $this->value_type, $this->case_sensitive);
		}

		return $qb->merge($wheres);
	}

}

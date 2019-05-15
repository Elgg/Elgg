<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds clauses for filtering entities by their type and subtype
 */
class TypeSubtypeWhereClause extends WhereClause {

	/**
	 * @var string
	 */
	public $type_column = 'type';

	/**
	 * @var string
	 */
	public $subtype_column = 'subtype';

	/**
	 * @var array
	 */
	public $type_subtype_pairs = [];

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {

		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$types_where = [];

		if (!empty($this->type_subtype_pairs)) {
			foreach ($this->type_subtype_pairs as $type => $subtypes) {
				if (is_array($subtypes) && !empty($subtypes)) {
					$types_where[] = $qb->merge([
						$qb->compare($alias($this->type_column), '=', $type, ELGG_VALUE_STRING),
						$qb->compare($alias($this->subtype_column), '=', $subtypes, ELGG_VALUE_STRING),
					]);
				} else {
					$types_where[] = $qb->compare($alias($this->type_column), '=', $type, ELGG_VALUE_STRING);
				}
			}
		}

		$wheres = [];
		if (!empty($types_where)) {
			$wheres[] = $qb->merge($types_where, 'OR');
		}

		$wheres[] = parent::prepare($qb, $table_alias);

		return $qb->merge($wheres);
	}

}

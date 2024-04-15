<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\DomainException;
use Elgg\Values;

/**
 * Utility class for building composite comparison expression
 */
class ComparisonClause extends Clause {

	/**
	 * Constructor
	 *
	 * @param string $x              Comparison value (e.g. prefixed column name)
	 * @param string $comparison     Comparison operator
	 * @param mixed  $y              Value to compare against
	 * @param string $type           Value type for sanitization/casting
	 * @param bool   $case_sensitive Use case sensitive comparison for strings
	 */
	public function __construct(
		public string $x,
		public string $comparison,
		public mixed $y = null,
		public ?string $type = null,
		public ?bool $case_sensitive = null
	) {
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws DomainException
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$x = $this->x;
		$y = $this->y;
		$type = $this->type;
		$case_sensitive = $this->case_sensitive;

		$compare_with = function ($func, $boolean = 'OR') use ($x, $y, $type, $case_sensitive, $qb) {
			if (!isset($y)) {
				return null;
			}

			$y = is_array($y) ? $y : [$y];
			$parts = [];
			foreach ($y as $val) {
				$val = isset($type) ? $qb->param($val, $type) : $val;
				if ($case_sensitive && $type === ELGG_VALUE_STRING) {
					$val = "BINARY {$val}";
				}
				
				$parts[] = $qb->expr()->$func($x, $val);
			}

			return $qb->merge($parts, $boolean);
		};

		$match_expr = null;
		$comparison = strtolower($this->comparison);
		switch ($comparison) {
			case '=':
			case 'eq':
			case 'in':
				if ($this->case_sensitive && $this->type == ELGG_VALUE_STRING) {
					$x = "CAST($x as BINARY)";
				}
				
				if (is_array($y) || $comparison === 'in') {
					if (!Values::isEmpty($y)) {
						$param = isset($type) ? $qb->param($y, $type) : $y;
						$match_expr = $qb->expr()->in($x, $param);
					}
				} elseif (isset($y)) {
					$param = isset($type) ? $qb->param($y, $type) : $y;
					$match_expr = $qb->expr()->eq($x, $param);
				}
				return $match_expr;

			case '!=':
			case '<>':
			case 'neq':
			case 'not in':
				if ($this->case_sensitive && $this->type == ELGG_VALUE_STRING) {
					$x = "CAST($x as BINARY)";
				}
				
				if (is_array($y) || $comparison === 'not in') {
					if (!Values::isEmpty($y)) {
						$param = isset($type) ? $qb->param($y, $type) : $y;
						$match_expr = $qb->expr()->notIn($x, $param);
					}
				} elseif (isset($y)) {
					$param = isset($type) ? $qb->param($y, $type) : $y;
					$match_expr = $qb->expr()->neq($x, $param);
				}
				return $match_expr;

			case 'like':
				return $compare_with('like');

			case 'not like':
				return $compare_with('notLike', 'AND');

			case '>':
			case 'gt':
				return $compare_with('gt');

			case '<':
			case 'lt':
				return $compare_with('lt');

			case '>=':
			case 'gte':
				return $compare_with('gte');

			case '<=':
			case 'lte':
				return $compare_with('lte');

			case 'is null':
				return $qb->expr()->isNull($x);

			case 'is not null':
				return $qb->expr()->isNotNull($x);

			case 'exists':
				return "EXISTS ($y)";

			case 'not exists':
				return "NOT EXISTS ($y)";

			default:
				throw new DomainException("'{$this->comparison}' is not a supported comparison operator");
		}
	}
}

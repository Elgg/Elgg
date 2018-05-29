<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Values;

/**
 * Utility class for building composite comparison expression
 */
class ComparisonClause implements Clause {

	/**
	 * @var string
	 */
	public $x;

	/**
	 * @var string
	 */
	public $comparison;

	/**
	 * @var mixed|null
	 */
	public $y;

	/**
	 * @var null|string
	 */
	public $type;

	/**
	 * @var bool|null
	 */
	public $case_sensitive;

	/**
	 * Constructor
	 *
	 * @param string $x              Comparison value (e.g. prefixed column name)
	 * @param string $comparison     Comparison operator
	 * @param mixed  $y              Value to compare against
	 * @param string $type           Value type for sanitization/casting
	 * @param bool   $case_sensitive Use case sensitive comparison for strings
	 */
	public function __construct($x, $comparison, $y = null, $type = null, $case_sensitive = null) {
		$this->x = $x;
		$this->comparison = $comparison;
		$this->y = $y;
		$this->type = $type;
		$this->case_sensitive = $case_sensitive;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \InvalidParameterException
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$x = $this->x;
		$y = $this->y;
		$type = $this->type;
		$case_sensitive = $this->case_sensitive;

		switch ($type) {
			case ELGG_VALUE_TIMESTAMP :
				$y = Values::normalizeTimestamp($y);
				$type = ELGG_VALUE_INTEGER;
				break;

			case ELGG_VALUE_GUID :
				$y = Values::normalizeGuids($y);
				$type = ELGG_VALUE_INTEGER;
				break;

			case ELGG_VALUE_ID :
				$y = Values::normalizeIds($y);
				$type = ELGG_VALUE_INTEGER;
				break;
		}

		if (is_array($y) && count($y) === 1) {
			$y = array_shift($y);
		}

		$match_expr = null;

		$comparison = strtolower($this->comparison);


		$compare_with = function ($func, $boolean = 'OR') use ($x, $y, $type, $case_sensitive, $qb) {
			if (!isset($y)) {
				return;
			}

			$parts = [];
			foreach ((array) $y as $val) {
				$val = $qb->param($val, $type);
				if ($case_sensitive && $type == ELGG_VALUE_STRING) {
					$val = "BINARY $val";
				}
				$parts[] = $qb->expr()->$func($x, $val);
			}

			return $qb->merge($parts, $boolean);
		};

		switch (strtolower($this->comparison)) {
			case '=' :
			case 'eq' :
			case 'in' :
				if ($this->case_sensitive && $this->type == ELGG_VALUE_STRING) {
					$x = "CAST($x as BINARY)";
				}
				if (is_array($y) || $comparison === 'in') {
					if (!empty($y)) {
						$param = isset($type) ? $qb->param($y, $type) : $y;
						$match_expr = $qb->expr()->in($x, $param);
					}
				} else if (isset($y)) {
					$param = isset($type) ? $qb->param($y, $type) : $y;
					$match_expr = $qb->expr()->eq($x, $param);
				}

				return $match_expr;

			case '!=' :
			case '<>' :
			case 'neq' :
			case 'not in' :
				if ($this->case_sensitive && $this->type == ELGG_VALUE_STRING) {
					$x = "CAST($x as BINARY)";
				}
				if (is_array($y) || $comparison === 'not in') {
					if (!empty($y)) {
						$param = isset($type) ? $qb->param($y, $type) : $y;
						$match_expr = $qb->expr()->notIn($x, $param);
					}
				} else if (isset($y)) {
					$param = isset($type) ? $qb->param($y, $type) : $y;
					$match_expr = $qb->expr()->neq($x, $param);
				}

				return $match_expr;

			case 'like' :
				return $compare_with('like');

			case 'not like' :
				return $compare_with('notLike', 'AND');

			case '>':
			case 'gt' :
				return $compare_with('gt');

			case '<' :
			case 'lt' :
				return $compare_with('lt');

			case '>=' :
			case 'gte' :
				return $compare_with('gte');

			case '<=' :
			case 'lte' :
				return $compare_with('lte');

			case 'is null' :
				return $qb->expr()->isNull($x);

			case 'is not null' :
				return $qb->expr()->isNotNull($x);

			case 'exists' :
				return "EXISTS ($y)";

			case 'not exists' :
				return "NOT EXISTS ($y)";

			default :
				throw new \InvalidParameterException("'{$this->comparison}' is not a supported comparison operator");
		}
	}
}

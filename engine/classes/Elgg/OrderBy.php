<?php
namespace Elgg;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Representation of an SQL ordering
 *
 * @internal
 * @access private
 */
class OrderBy {
	private $expression = '';
	private $direction = '';

	/**
	 * Constructor
	 *
	 * @param string $expression SQL expression to order by. E.g. "e.guid"
	 * @param string $direction  "ASC" or "DESC"
	 */
	public function __construct($expression, $direction) {
		if (empty($expression)) {
			throw new \InvalidArgumentException('$expression cannot be empty');
		}
		if (!in_array($direction, ['ASC', 'DESC'])) {
			throw new \InvalidArgumentException('$direction must be ASC or DESC');
		}

		$this->expression = $expression;
		$this->direction = $direction;
	}

	/**
	 * Get the expression
	 *
	 * @return string
	 */
	public function getExpression() {
		return $this->expression;
	}

	/**
	 * Get the direction
	 *
	 * @return string
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * Make an OrderBy from a string
	 *
	 * @param string $str Order by. E.g. "expr", "expr ASC", or "expr DESC"
	 * @return OrderBy
	 */
	public static function fromString($str) {
		$str = trim($str);
		if (!preg_match('~^(\S+)(?:\s+(asc|desc))?\\z~i', $str, $m)) {
			throw new \InvalidArgumentException('An ordering must be "expr", "expr ASC", or "expr DESC"');
		}
		$expression = $m[1];
		$direction = isset($m[2]) ? strtoupper($m[2]) : 'ASC';
		return new self($expression, $direction);
	}

	/**
	 * Apply orderings from an options array to a query builder
	 *
	 * @param QueryBuilder $qb      Query builder
	 * @param array        $options Options array
	 * @param string       $key     Options key
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public static function addToQueryBuilder(QueryBuilder $qb, array $options, $key = 'order_by') {
		if (empty($options['order_by'])) {
			return;
		}

		if (is_string($options[$key])) {
			$options[$key] = explode(',', $options[$key]);
		}
		if (!is_array($options[$key])) {
			throw new \InvalidArgumentException("option '$key' should be a set of orderings");
		}

		$order_bys = array_map(function ($el) use ($key) {
			if ($el instanceof OrderBy) {
				return $el;
			}
			if (is_string($el)) {
				return OrderBy::fromString($el);
			}

			throw new \InvalidArgumentException("option '$key' should be a set of orderings");
		}, $options[$key]);
		/* @var OrderBy[] $order_bys */

		foreach ($order_bys as $order_by) {
			$qb->addOrderBy($order_by->expression, $order_by->direction);
		}
	}
}

<?php
namespace Elgg\Database;

use Doctrine\DBAL\Connection;

/**
 * Query builder based on Doctrine DBAL
 *
 * Arguments accepting table names can auto-prepend the Elgg prefix. Simply pass these in as "{table}".
 *
 * <code>
 * $qb = elgg_get_query_builder();
 * $qb->select('e.*')
 *    ->from('{entities}', 'e')
 *    ->setMaxResults(2);
 * $rows = get_data($qb);
 * </code>
 *
 * @link http://doctrine-orm.readthedocs.org/projects/doctrine-dbal/en/stable/reference/query-builder.html
 */
class QueryBuilder extends \Doctrine\DBAL\Query\QueryBuilder {

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * Constructor
	 *
	 * @param Connection $connection Connection
	 * @param string     $prefix     Table prefix
	 */
	public function __construct(Connection $connection, $prefix) {
		$this->prefix = $prefix;
		parent::__construct($connection);
	}

	/**
	 * If a string starts with "{" assume "{table}" and return "elgg_table"
	 *
	 * @param string $table Table name. E.g. "custom_table" or "{table}"
	 * @return string
	 */
	private function prefixTable($table) {
		// note we pass through empty values because some methods pass null through this
		if ($table && $table[0] === '{') {
			return $this->prefix . trim($table, '{}');
		}
		return $table;
	}

	/**
	 * Create a set for use in with an IN operator
	 *
	 * @param mixed $values Value(s) to place in set
	 * @param int   $type   SQL type (e.g. \PDO::PARAM_INT)
	 *
	 * @return string
	 */
	public function createSet($values, $type = \PDO::PARAM_STR) {
		if (!is_array($values)) {
			$values = [$values];
		}

		$placeHolders = array_map(function ($value) use ($type) {
			return $this->createNamedParameter($value, $type);
		}, $values);

		return "(" . implode(',', $placeHolders) . ")";
	}

	/**
	 * Not implemented.
	 *
	 * @throws \BadMethodCallException
	 * @return void
	 * @internal
	 */
	public function execute() {
		throw new \BadMethodCallException(__METHOD__ . ' is not implemented. Pass this object to one of '
			. 'Elgg\'s functions that accepts queries, like get_data(), insert_data(), etc.');
	}

	/**
	 * {@inheritdoc}
	 */
	public function from($from, $alias = null) {
		return parent::from($this->prefixTable($from), $alias);
	}

	/**
	 * {@inheritdoc}
	 */
	public function insert($insert = null) {
		return parent::insert($this->prefixTable($insert));
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($delete = null, $alias = null) {
		return parent::delete($this->prefixTable($delete), $alias);
	}

	/**
	 * {@inheritdoc}
	 */
	public function update($update = null, $alias = null) {
		return parent::update($this->prefixTable($update), $alias);
	}

	/**
	 * {@inheritdoc}
	 */
	public function innerJoin($fromAlias, $join, $alias, $condition = null) {
		return parent::innerJoin($fromAlias, $this->prefixTable($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function join($fromAlias, $join, $alias, $condition = null) {
		return parent::join($fromAlias, $this->prefixTable($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function leftJoin($fromAlias, $join, $alias, $condition = null) {
		return parent::leftJoin($fromAlias, $this->prefixTable($join), $alias, $condition);
	}
}

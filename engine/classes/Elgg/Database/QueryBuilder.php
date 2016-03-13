<?php
namespace Elgg\Database;

use Elgg\Database;
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
	 * @var Database
	 */
	private $db;

	/**
	 * @var string
	 */
	private $append = '';

	/**
	 * Constructor
	 *
	 * @param Connection $connection Active connection
	 * @param Database   $db         Database
	 */
	public function __construct(Connection $connection, Database $db) {
		$this->db = $db;
		parent::__construct($connection);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSQL() {
		return parent::getSQL() . $this->append;
	}

	/**
	 * Append raw SQL to the output query
	 *
	 * @param string $sql SQL to append. E.g. "ON DUPLICATE ..."
	 *
	 * @return self
	 */
	public function appendSql($sql) {
		$this->append = $sql;
		return $this;
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
			return $this->db->getTablePrefix() . trim($table, '{}');
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
	 * Execute the query. This is a shortcut for the get_data(), get_data_row(), insert_data(),
	 * update_data(), and delete_data() functions.
	 *
	 * @param callable $callback   If a SELECT query, the function applied to each row
	 * @param bool     $single_row If a SELECT query, return only the first row?
	 *
	 * @return array|int|\stdClass
	 */
	public function execute($callback = null, $single_row = false) {
		switch ($this->getType()) {
			case self::SELECT:
				if ($single_row) {
					return $this->db->getDataRow($this, $callback);
				} else {
					return $this->db->getData($this, $callback);
				}

			case self::UPDATE:
				return $this->db->updateData($this, true);

			case self::INSERT:
				return $this->db->insertData($this);

			case self::DELETE:
				return $this->db->deleteData($this);
		}
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

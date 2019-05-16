<?php

namespace Elgg\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder as DbalQueryBuilder;
use Elgg\Database\Clauses\Clause;
use Elgg\Database\Clauses\ComparisonClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\WhereClause;

/**
 * Database abstraction query builder
 */
abstract class QueryBuilder extends DbalQueryBuilder {

	const TABLE_ENTITIES = 'entities';
	const TABLE_METADATA = 'metadata';
	const TABLE_ANNOTATIONS = 'annotations';
	const TABLE_RELATIONSHIPS = 'entity_relationships';
	const TABLE_PRIVATE_SETTINGS = 'private_settings';

	static $calculations = [
		'avg',
		'count',
		'greatest',
		'least',
		'max',
		'min',
		'sum',
	];

	/**
	 * @var array
	 */
	protected $joins = [];

	/**
	 * @var int
	 */
	protected $join_index = 0;

	/**
	 * @var string
	 */
	protected $table_name;

	/**
	 * @var string
	 */
	protected $table_alias;

	/**
	 * Creates a new SelectQueryBuilder for join/where subqueries using the DB connection of the primary QueryBuilder
	 *
	 * @param string $table Main table name
	 * @param string $alias Select alias
	 *
	 * @return Select
	 */
	public function subquery($table, $alias = null) {
		$qb = new Select($this->getConnection());
		$qb->from($table, $alias);

		return $qb;
	}

	/**
	 * Apply clause to this instance
	 *
	 * @param Clause $clause Clause
	 * @param string $alias  Table alias
	 *
	 * @return static
	 */
	public function addClause(Clause $clause, $alias = null) {
		if (!isset($alias)) {
			$alias = $this->getTableAlias();
		}
		$expr = $clause->prepare($this, $alias);
		if ($clause instanceof WhereClause && ($expr instanceof CompositeExpression || is_string($expr))) {
			$this->andWhere($expr);
		}

		return $this;
	}

	/**
	 * Prefixes the table name with installation DB prefix
	 *
	 * @param string $table
	 *
	 * @return string
	 */
	public function prefix($table) {
		$prefix = _elgg_services()->db->prefix;
		if ($prefix === '') {
			return $table;
		}
		
		if (strpos($table, $prefix) !== 0) {
			return "{$prefix}{$table}";
		}

		return $table;
	}

	/**
	 * Returns the name of the primary table
	 *
	 * @return string
	 */
	public function getTableName() {
		return $this->table_name;
	}

	/**
	 * Returns the alias of the primary table
	 * @return string
	 */
	public function getTableAlias() {
		return $this->table_alias;
	}

	/**
	 * Sets a new parameter assigning it a unique parameter key/name if none provided
	 * Returns the name of the new parameter
	 *
	 * @param mixed  $value Parameter value
	 * @param string $type  Parameter type
	 * @param string $key   Parameter key/index
	 *
	 * @return string
	 */
	public function param($value, $type = null, $key = null) {
		if (!$key) {
			$parameters = $this->getParameters();
			$key = ":qb" . (count($parameters) + 1);
		}

		if (is_array($value)) {
			if ($type === ELGG_VALUE_INTEGER) {
				$type = Connection::PARAM_INT_ARRAY;
			} else if ($type === ELGG_VALUE_STRING) {
				$type = Connection::PARAM_STR_ARRAY;
			}
		}

		$this->setParameter($key, $value, $type);

		return $key;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param bool $track_query should the query be tracked by timers and loggers
	 */
	public function execute(bool $track_query = true) {
		
		if (!$track_query) {
			return parent::execute();
		}
		
		return _elgg_services()->db->trackQuery($this, [], function() {
			return parent::execute();
		});
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @access private Use create() method on the extending class
	 */
	public function from($table, $alias = null) {
		$this->table_name = $table;
		$this->table_alias = $alias;

		return parent::from($this->prefix($table), $alias);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @access private Use create() method on the extending class
	 */
	public function insert($insert = null) {
		$this->table_name = $insert;

		return parent::insert($this->prefix($insert));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @access private Use create() method on the extending class
	 */
	public function update($table = null, $alias = null) {
		$this->table_name = $table;
		$this->table_alias = $alias;

		return parent::update($this->prefix($table), $alias);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @access private Use create() method on the extending class
	 */
	public function delete($table = null, $alias = null) {
		$this->table_name = $table;
		$this->table_alias = $alias;

		return parent::delete($this->prefix($table), $alias);
	}

	/**
	 * {@inheritdoc}
	 */
	public function join($fromAlias, $join, $alias, $condition = null) {
		return parent::join($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function innerJoin($fromAlias, $join, $alias, $condition = null) {
		return parent::innerJoin($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function leftJoin($fromAlias, $join, $alias, $condition = null) {
		return parent::leftJoin($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rightJoin($fromAlias, $join, $alias, $condition = null) {
		return parent::rightJoin($fromAlias, $this->prefix($join), $alias, $condition); // TODO: Change the autogenerated stub
	}

	/**
	 * Merges multiple composite expressions with a boolean
	 *
	 * @param mixed  $parts   Composite expression(s) or string(s)
	 * @param string $boolean AND|OR
	 *
	 * @return CompositeExpression|string
	 */
	public function merge($parts = null, $boolean = 'AND') {
		if (empty($parts)) {
			return;
		}

		$parts = (array) $parts;

		$parts = array_filter($parts, function ($e) {
			if (empty($e)) {
				return false;
			}
			if (!$e instanceof CompositeExpression && !is_string($e)) {
				return false;
			}

			return true;
		});
		if (empty($parts)) {
			return;
		}

		if (count($parts) === 1) {
			return array_shift($parts);
		}

		if (strtoupper($boolean) === 'OR') {
			return $this->expr()->orX()->addMultiple($parts);
		} else {
			return $this->expr()->andX()->addMultiple($parts);
		}
	}

	/**
	 * Build value comparison clause
	 *
	 * @param string $x              Comparison value (e.g. prefixed column name)
	 * @param string $comparison     Comparison operator
	 * @param mixed  $y              Value to compare against
	 *                               If the value is an array, comparisons will be performed in such as a way as
	 *                               to ensure that either all or none of the elements of the array meet the criteria,
	 *                               e.g. in case of LIKE will return results where at least one element matches the
	 *                               criteria, where as with NOT LIKE will return results where none of the criteria
	 *                               are met
	 * @param string $type           Value type for sanitization/casting
	 * @param bool   $case_sensitive Use case sensitive comparison for strings
	 *
	 * @return CompositeExpression|null|string
	 */
	public function compare($x, $comparison, $y = null, $type = null, $case_sensitive = null) {
		return (new ComparisonClause($x, $comparison, $y, $type, $case_sensitive))->prepare($this);
	}

	/**
	 * Build a between clause
	 *
	 * @param string $x     Comparison value (e.g. prefixed column name)
	 * @param mixed  $lower Lower bound
	 * @param mixed  $upper Upper bound
	 *
	 * @return CompositeExpression|null|string
	 */
	public function between($x, $lower = null, $upper = null, $type = null) {
		$wheres = [];
		if ($lower) {
			$wheres[] = $this->compare($x, '>=', $lower, $type);
		}
		if ($upper) {
			$wheres[] = $this->compare($x, '<=', $upper, $type);
		}

		return $this->merge($wheres);
	}

	/**
	 * Get an index of the next available join alias
	 * @return string
	 */
	public function getNextJoinAlias() {
		$this->join_index++;

		return "qbt{$this->join_index}";
	}

	/**
	 * Join entity table from alias and return joined table alias
	 *
	 * @param string $from_alias   Main table alias
	 * @param string $from_column  Guid column name in the main table
	 * @param string $join_type    JOIN type
	 * @param string $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinEntitiesTable($from_alias = '', $from_column = 'guid', $join_type = 'inner', $joined_alias = null) {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "$from_alias.$from_column";
		}

		$hash = sha1(serialize([
			$join_type,
			self::TABLE_ENTITIES,
			$from_column,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column) {
			return $qb->compare("$joined_alias.guid", '=', $from_column);
		};

		$clause = new JoinClause(self::TABLE_ENTITIES, $joined_alias, $condition, $join_type);
		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}

	/**
	 * Join metadata table from alias and return joined table alias
	 *
	 * @param string          $from_alias   Alias of the main table
	 * @param string          $from_column  Guid column name in the main table
	 * @param string|string[] $name         Metadata name(s)
	 * @param string          $join_type    JOIN type
	 * @param string          $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinMetadataTable($from_alias = '', $from_column = 'guid', $name = null, $join_type = 'inner', $joined_alias = null) {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "$from_alias.$from_column";
		}

		$hash = sha1(serialize([
			$join_type,
			self::TABLE_METADATA,
			$from_column,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name) {
			return $qb->merge([
				$qb->compare("$joined_alias.entity_guid", '=', $from_column),
				$qb->compare("$joined_alias.name", '=', $name, ELGG_VALUE_STRING),
			]);
		};

		$clause = new JoinClause(self::TABLE_METADATA, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}

	/**
	 * Join annotations table from alias and return joined table alias
	 *
	 * @param string          $from_alias   Main table alias
	 * @param string          $from_column  Guid column name in the main table
	 * @param string|string[] $name         Annotation name
	 * @param string          $join_type    JOIN type
	 * @param string          $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinAnnotationTable($from_alias = '', $from_column = 'guid', $name = null, $join_type = 'inner', $joined_alias = null) {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "$from_alias.$from_column";
		}

		$hash = sha1(serialize([
			$join_type,
			self::TABLE_ANNOTATIONS,
			$from_column,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name) {
			return $qb->merge([
				$qb->compare("$joined_alias.entity_guid", '=', $from_column),
				$qb->compare("$joined_alias.name", '=', $name, ELGG_VALUE_STRING),
			]);
		};

		$clause = new JoinClause(self::TABLE_ANNOTATIONS, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}

	/**
	 * Join private settings table from alias and return joined table alias
	 *
	 * @param string          $from_alias   Main table alias
	 * @param string          $from_column  Guid column name in the main table
	 * @param string|string[] $name         Private setting name
	 * @param string          $join_type    JOIN type
	 * @param string          $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinPrivateSettingsTable($from_alias = '', $from_column = 'guid', $name = null, $join_type = 'inner', $joined_alias = null) {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "$from_alias.$from_column";
		}

		$hash = sha1(serialize([
			$join_type,
			self::TABLE_PRIVATE_SETTINGS,
			$from_column,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name) {
			return $qb->merge([
				$qb->compare("$joined_alias.entity_guid", '=', $from_column),
				$qb->compare("$joined_alias.name", '=', $name, ELGG_VALUE_STRING),
			]);
		};

		$clause = new JoinClause(self::TABLE_PRIVATE_SETTINGS, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}

	/**
	 * Join relationship table from alias and return joined table alias
	 *
	 * @param string $from_alias   Main table alias
	 * @param string $from_column  Guid column name in the main table
	 * @param string $name         Relationship name
	 * @param bool   $inverse      Join on guid_two column
	 * @param string $join_type    JOIN type
	 * @param string $joined_alias Joined table alias
	 *
	 * @return string
	 * @throws \InvalidParameterException
	 */
	public function joinRelationshipTable($from_alias = '', $from_column = 'guid', $name = null, $inverse = false, $join_type = 'inner', $joined_alias = null) {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "$from_alias.$from_column";
		}

		$hash = sha1(serialize([
			$join_type,
			self::TABLE_RELATIONSHIPS,
			$from_column,
			$inverse,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name, $inverse) {
			$parts = [];
			if ($inverse) {
				$parts[] = $qb->compare("$joined_alias.guid_one", '=', $from_column);
			} else {
				$parts[] = $qb->compare("$joined_alias.guid_two", '=', $from_column);
			}
			$parts[] = $qb->compare("$joined_alias.relationship", '=', $name, ELGG_VALUE_STRING);
			return $qb->merge($parts);
		};

		$clause = new JoinClause(self::TABLE_RELATIONSHIPS, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}
}

<?php

namespace Elgg\Database;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder as DbalQueryBuilder;
use Elgg\Database\Clauses\Clause;
use Elgg\Database\Clauses\ComparisonClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\WhereClause;
use Elgg\Values;

/**
 * Database abstraction query builder
 */
abstract class QueryBuilder extends DbalQueryBuilder {
	
	const CALCULATIONS = [
		'avg',
		'count',
		'greatest',
		'least',
		'max',
		'min',
		'sum',
	];

	protected array $joins = [];

	protected int $join_index = 0;

	protected ?string $table_name = null;

	protected ?string $table_alias = null;
	
	/**
	 * Initializes a new QueryBuilder.
	 *
	 * @param Connection $backup_connection Connection used for this query
	 */
	public function __construct(protected readonly Connection $backup_connection) {
		parent::__construct($backup_connection);
	}
	
	/**
	 * Returns the connection. Need to do it this way because DBAL Query Builder does not expose the connection in 4.x
	 *
	 * @return Connection
	 *
	 * @since 6.0
	 */
	public function getConnection(): Connection {
		return $this->backup_connection;
	}

	/**
	 * Creates a new SelectQueryBuilder for join/where sub queries using the DB connection of the primary QueryBuilder
	 *
	 * @param string      $table Main table name
	 * @param string|null $alias Select alias
	 *
	 * @return Select
	 */
	public function subquery(string $table, string $alias = null): Select {
		$qb = new Select($this->getConnection());
		$qb->from($table, $alias);

		return $qb;
	}

	/**
	 * Apply clause to this instance
	 *
	 * @param Clause      $clause Clause
	 * @param string|null $alias  Table alias
	 *
	 * @return static
	 */
	public function addClause(Clause $clause, string $alias = null): static {
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
	 * @param string $table the table to prefix
	 *
	 * @return string
	 */
	public function prefix(string $table): string {
		$prefix = _elgg_services()->db->prefix;
		if ($prefix === '') {
			return $table;
		}
		
		if (!str_starts_with($table, $prefix)) {
			return "{$prefix}{$table}";
		}

		return $table;
	}

	/**
	 * Returns the name of the primary table
	 *
	 * @return string
	 */
	public function getTableName(): string {
		return (string) $this->table_name;
	}

	/**
	 * Returns the alias of the primary table
	 *
	 * @return null|string
	 */
	public function getTableAlias(): ?string {
		return $this->table_alias;
	}

	/**
	 * Sets a new parameter assigning it a unique parameter key/name if none provided
	 * Returns the name of the new parameter
	 *
	 * @param mixed       $value Parameter value
	 * @param string      $type  Parameter type
	 * @param string|null $key   Parameter key/index
	 *
	 * @return string
	 */
	public function param($value, string $type = ELGG_VALUE_STRING, string $key = null): string {
		if (!$key) {
			$parameters = $this->getParameters();
			$key = ':qb' . (count($parameters) + 1);
		}

		switch ($type) {
			case ELGG_VALUE_GUID:
				$value = Values::normalizeGuids($value);
				$type = ParameterType::INTEGER;
				
				break;
			case ELGG_VALUE_ID:
				$value = Values::normalizeIds($value);
				$type = ParameterType::INTEGER;
				
				break;
			case ELGG_VALUE_INTEGER:
				$type = ParameterType::INTEGER;
				
				break;
			case ELGG_VALUE_BOOL:
				$type = ParameterType::INTEGER;
				$value = (int) $value;
				
				break;
			case ELGG_VALUE_STRING:
				$type = ParameterType::STRING;
				
				break;
			case ELGG_VALUE_TIMESTAMP:
				$value = Values::normalizeTimestamp($value);
				$type = ParameterType::INTEGER;
				
				break;
		}
		
		// convert array value or type based on array
		if (is_array($value)) {
			if (count($value) === 1) {
				$value = array_shift($value);
			} else {
				if ($type === ParameterType::INTEGER) {
					$type = ArrayParameterType::INTEGER;
				} elseif ($type === ParameterType::STRING) {
					$type = ArrayParameterType::STRING;
				}
			}
		}

		return $this->createNamedParameter($value, $type, $key);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param bool $track_query should the query be tracked by timers and loggers
	 */
	public function execute(bool $track_query = true) {
		if (!$track_query) {
			if ($this instanceof Select) {
				return parent::executeQuery();
			} else {
				return parent::executeStatement();
			}
		}
		
		return _elgg_services()->db->trackQuery($this, function() {
			if ($this instanceof Select) {
				return parent::executeQuery();
			} else {
				return parent::executeStatement();
			}
		});
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @internal Use create() method on the extending class
	 */
	public function from(string $table, ?string $alias = null): self {
		$this->table_name = $table;
		$this->table_alias = $alias;

		return parent::from($this->prefix($table), $alias);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @internal Use create() method on the extending class
	 */
	public function insert(string $table): self {
		$this->table_name = $table;

		return parent::insert($this->prefix($table));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @internal Use create() method on the extending class
	 */
	public function update(string $table): self {
		$this->table_name = $table;

		return parent::update($this->prefix($table));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @internal Use create() method on the extending class
	 */
	public function delete(string $table): self {
		$this->table_name = $table;

		return parent::delete($this->prefix($table));
	}

	/**
	 * {@inheritdoc}
	 */
	public function join(string $fromAlias, string $join, string $alias, ?string $condition = null): self {
		return parent::join($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function innerJoin(string $fromAlias, string $join, string $alias, ?string $condition = null): self {
		return parent::innerJoin($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function leftJoin(string $fromAlias, string $join, string $alias, ?string $condition = null): self {
		return parent::leftJoin($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rightJoin(string $fromAlias, string $join, string $alias, ?string $condition = null): self {
		return parent::rightJoin($fromAlias, $this->prefix($join), $alias, $condition);
	}

	/**
	 * Merges multiple composite expressions with a boolean
	 *
	 * @param mixed  $parts   Composite expression(s) or string(s)
	 * @param string $boolean AND|OR
	 *
	 * @return CompositeExpression|string|null
	 */
	public function merge($parts = null, $boolean = 'AND') {
		if (empty($parts)) {
			return null;
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
			return null;
		}

		if (count($parts) === 1) {
			return array_shift($parts);
		}

		// PHP 8 can use named arguments in call_user_func_array(), this causes issues
		// @see: https://www.php.net/manual/en/function.call-user-func-array.php#125953
		$parts = array_values($parts);
		if (strtoupper($boolean) === 'OR') {
			return call_user_func_array([$this->expr(), 'or'], $parts);
		}
		
		return call_user_func_array([$this->expr(), 'and'], $parts);
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
	public function compare(string $x, string $comparison, $y = null, string $type = null, bool $case_sensitive = null) {
		return (new ComparisonClause($x, $comparison, $y, $type, $case_sensitive))->prepare($this);
	}

	/**
	 * Build a between clause
	 *
	 * @param string $x     Comparison value (e.g. prefixed column name)
	 * @param mixed  $lower Lower bound
	 * @param mixed  $upper Upper bound
	 * @param string $type  Value type for sanitization/casting
	 *
	 * @return CompositeExpression|null|string
	 */
	public function between(string $x, $lower = null, $upper = null, string $type = null) {
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
	 *
	 * @return string
	 */
	public function getNextJoinAlias(): string {
		$this->join_index++;

		return "qbt{$this->join_index}";
	}

	/**
	 * Join entity table from alias and return joined table alias
	 *
	 * @param string      $from_alias   Main table alias
	 * @param string      $from_column  Guid column name in the main table
	 * @param string|null $join_type    JOIN type
	 * @param string|null $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinEntitiesTable(string $from_alias = '', string $from_column = 'guid', ?string $join_type = 'inner', string $joined_alias = null): string {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "{$from_alias}.{$from_column}";
		}

		$hash = sha1(serialize([
			$join_type,
			EntityTable::TABLE_NAME,
			$from_column,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column) {
			return $qb->compare("{$joined_alias}.guid", '=', $from_column);
		};

		$clause = new JoinClause(EntityTable::TABLE_NAME, $joined_alias, $condition, $join_type);
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
	 * @param string|null     $join_type    JOIN type
	 * @param string|null     $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinMetadataTable(string $from_alias = '', string $from_column = 'guid', $name = null, ?string $join_type = 'inner', string $joined_alias = null): string {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "{$from_alias}.{$from_column}";
		}

		$hash = sha1(serialize([
			$join_type,
			MetadataTable::TABLE_NAME,
			$from_column,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name) {
			return $qb->merge([
				$qb->compare("{$joined_alias}.entity_guid", '=', $from_column),
				$qb->compare("{$joined_alias}.name", '=', $name, ELGG_VALUE_STRING),
			]);
		};

		$clause = new JoinClause(MetadataTable::TABLE_NAME, $joined_alias, $condition, $join_type);

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
	 * @param string|null     $join_type    JOIN type
	 * @param string|null     $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinAnnotationTable(string $from_alias = '', string $from_column = 'guid', $name = null, ?string $join_type = 'inner', string $joined_alias = null): string {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "{$from_alias}.{$from_column}";
		}

		$hash = sha1(serialize([
			$join_type,
			AnnotationsTable::TABLE_NAME,
			$from_column,
			(array) $name,
		]));

		if (!isset($joined_alias) && !empty($this->joins[$hash])) {
			return $this->joins[$hash];
		}

		$condition = function (QueryBuilder $qb, $joined_alias) use ($from_column, $name) {
			return $qb->merge([
				$qb->compare("{$joined_alias}.entity_guid", '=', $from_column),
				$qb->compare("{$joined_alias}.name", '=', $name, ELGG_VALUE_STRING),
			]);
		};

		$clause = new JoinClause(AnnotationsTable::TABLE_NAME, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}

	/**
	 * Join relationship table from alias and return joined table alias
	 *
	 * @param string      $from_alias   Main table alias
	 * @param string      $from_column  Guid column name in the main table
	 * @param string      $name         Relationship name
	 * @param bool        $inverse      Join on guid_two column
	 * @param string|null $join_type    JOIN type
	 * @param string|null $joined_alias Joined table alias
	 *
	 * @return string
	 */
	public function joinRelationshipTable(string $from_alias = '', string $from_column = 'guid', $name = null, bool $inverse = false, ?string $join_type = 'inner', string $joined_alias = null): string {
		if (in_array($joined_alias, $this->joins)) {
			return $joined_alias;
		}

		if ($from_alias) {
			$from_column = "{$from_alias}.{$from_column}";
		}

		$hash = sha1(serialize([
			$join_type,
			RelationshipsTable::TABLE_NAME,
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
				$parts[] = $qb->compare("{$joined_alias}.guid_one", '=', $from_column);
			} else {
				$parts[] = $qb->compare("{$joined_alias}.guid_two", '=', $from_column);
			}
			
			$parts[] = $qb->compare("{$joined_alias}.relationship", '=', $name, ELGG_VALUE_STRING);
			return $qb->merge($parts);
		};

		$clause = new JoinClause(RelationshipsTable::TABLE_NAME, $joined_alias, $condition, $join_type);

		$joined_alias = $clause->prepare($this, $from_alias);

		$this->joins[$hash] = $joined_alias;

		return $joined_alias;
	}
}

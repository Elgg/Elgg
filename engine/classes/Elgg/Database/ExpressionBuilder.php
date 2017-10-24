<?php

namespace Elgg\Database;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\AttributeLoader;
use Elgg\Config;

/**
 * API IN FLUX
 *
 * @todo This class expects that values have been validated and normalized. When adding public equivalents,
 *       we need to make sure that we validate values before calling methods in this class
 *
 * @internal
 */
class ExpressionBuilder {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	/**
	 * ExpressionBuilder constructor.
	 *
	 * @param QueryBulder $this ->qb QueryBuilder
	 */
	public function __construct(QueryBuilder $qb) {
		$this->qb = $qb;
	}

	/**
	 * Process an array of 'selects' clauses
	 *
	 * @param array $clauses Clauses
	 *
	 * @return null
	 */
	public function applySelectClauses(array $clauses = null) {

		if (empty($clauses)) {
			return;
		}

		foreach ($clauses as $clause) {
			if ($clause instanceof Closure) {
				call_user_func($clause, $this->qb);
			} else {
				$this->qb->addSelect($clause);
			}
		}
	}

	/**
	 * Processes an array of 'where' clauses
	 *
	 * @param array  $wheres   Where clauses
	 * @param string $operator Merge operator 'AND'|'OR'
	 *
	 * @return CompositeExpression|null
	 */
	public function applyWhereClauses(array $clauses = null, $operator = 'AND') {
		if (empty($clauses)) {
			return;
		}

		foreach ($clauses as $clause) {
			if (empty($clause)) {
				continue;
			}

			if ($clause instanceof CompositeExpression) {
				$wheres[] = $clause;
			} else if ($clause instanceof Closure) {
				$wheres[] = call_user_func($clause, $this->qb);
			} else {
				elgg_deprecated_notice("
					Using literal MySQL statements in 'wheres' options parameter is deprecated.
					Instead use a closure that receives an instanceof of QueryBuilder
					and returns a composite DBAL expression.
					
					{{ $clause }}
				", '3.0');
				$wheres[] = $clause;
			}
		}

		$wheres = array_filter($wheres);
		if (empty($wheres)) {
			return;
		}

		if (strtoupper($operator) == 'OR') {
			return $this->qb->expr()->orX()->addMultiple($wheres);
		} else {
			return $this->qb->expr()->andX()->addMultiple($wheres);
		}
	}

	/**
	 * Process an array of 'join' clauses
	 *
	 * @param array $clauses Join clauses
	 *
	 * @return null
	 */
	public function applyJoinClauses(array $clauses = null) {
		if (empty($clauses)) {
			return;
		}

		foreach ($clauses as $join) {
			if (empty($join)) {
				continue;
			}

			$table = false;

			if ($join instanceof Closure) {
				$result = call_user_func($join, $this->qb);
				if (is_array($result)) {
					$type = elgg_extract('join_type', $result);
					$from_alias = elgg_extract('from_alias', $result, $this->qb->getFromAlias());
					$table = elgg_extract('table', $result);
					$alias = elgg_extract('alias', $result);
					$condition = elgg_extract('condition', $result);
				}
			} else if (is_string($join)) {
				preg_match('/((LEFT|INNER|RIGHT)\s+)?JOIN\s+(.*?)\s+((.*?)\s+)ON\s+(.*)\s*/im', $join, $parts);

				$from_alias = $this->qb->getFromAlias();
				$type = $parts[2] ? : 'inner';
				$table = $parts[3];
				$alias = $parts[5];
				$condition = $parts[6];

				$dbprefix = elgg_get_config('dbprefix');
				if (strpos($table, $dbprefix) === 0) {
					$table = substr($table, strlen($dbprefix));
				}

				elgg_deprecated_notice("
					Using literal MySQL statements in 'joins' options parameter is deprecated.
					Instead use a closure that receives an instanceof of QueryBuilder and returns an array
					of join options
					
					{{ $join }}
					
					should be changed to
					
					{{
						function(QueryBuilder \$qb) {
						   return [
							  'join_type' => '$type',
							  'from_alias' => '$from_alias'',
							  'table' => '$table',
							  'alias' => '$alias',
							  'condition' => '$condition', // Use \$qb->expr()->eq() instead
						   ];
						} 
					}}
				
				", '3.0');
			}

			if (!$table) {
				continue;
			}

			switch (strtoupper($type)) {
				case 'INNER' :
					$this->qb->join($from_alias, $table, $alias, $condition);
					break;

				case 'LEFT' :
					$this->qb->leftJoin($from_alias, $table, $alias, $condition);
					break;

				case 'RIGHT' :
					$this->qb->rightJoin($from_alias, $table, $alias, $condition);
					break;
			}
		}
	}

	/**
	 * Process sorting options
	 *
	 * @param string         $alias            Main table alias
	 * @param string         $guid_column      GUID column name in main table
	 * @param array          $sorts            Sorting options
	 * @param Closure|string $order_by         Closure or legacy 'order_by' string
	 * @param bool           $reverse_order_by Legacy 'reverse_order_by' flag
	 */
	public function applyOrderByClauses($alias, $guid_column, array $sorts = null, $order_by = null, $reverse_order_by = null) {

		if (empty($sorts) && empty($order_by)) {
			$sorts = [
				'field' => 'time_created',
				'direction' => 'desc',
				'signed' => true,
			];
		};

		if (!empty($sorts)) {
			usort($sorts, function ($a, $b) {
				$pa = elgg_extract('priority', $a, 500);
				$pb = elgg_extract('priority', $b, 500);
				if ($pa == $pb) {
					return 0;
				}

				return ($pa < $pb) ? -1 : 1;
			});

			$attributes = AttributeLoader::$primary_attr_names;
			foreach ($sorts as $sort) {
				$field = elgg_extract('field', $sort);
				if (!$field) {
					continue;
				}

				$direction = elgg_extract('direction', $sort, 'asc');
				$direction = strtoupper($direction);
				$direction = in_array($direction, ['ASC', 'DESC']) ? $direction : null;

				$signed = elgg_extract('signed', $sort, false);
				$join_type = elgg_extract('join_type', $sort, 'inner');

				if (in_array($field, $attributes)) {
					$column = "$alias.$field";
				} else {
					$md_alias = $this->qb->joinMetadataTable($alias, $guid_column, $field, $join_type);
					$column = "$md_alias.value";
				}

				if ($signed) {
					$column = "CAST($column AS SIGNED)";
				}

				$this->qb->addOrderBy($column, $direction);
			}
		}

		if ($order_by) {
			if ($order_by instanceof Closure) {
				call_user_func($order_by, $this->qb);
			} else {
				elgg_deprecated_notice("
					Using literal MySQL statements in 'order_by' options parameter is deprecated.
					Instead use a closure that receives an instanceof of QueryBuilder
					and modifies it with addOrderBy calls.
					
					{{ $order_by }}
				", '3.0');

				$orders = explode(',', $order_by);
				foreach ($orders as $order_by) {
					$order_by = trim($order_by);
					list($column, $direction) = explode(' ', $order_by, 2);
					if (!$column) {
						continue;
					}

					$direction = in_array(strtoupper($direction), [
						'ASC',
						'DESC'
					]) ? strtoupper($direction) : 'ASC';
					if ($reverse_order_by) {
						$direction = $direction == 'ASC' ? 'DESC' : 'ASC';
					}

					$this->qb->addOrderBy($column, $direction);
				}
			}
		}
	}

	/**
	 * Process 'group_by' clauses
	 *
	 * @param mixed $clauses Clauses
	 *
	 * @return null
	 */
	public function applyGroupByClauses($clauses = null) {

		if (empty($clauses)) {
			return;
		}

		if (is_string($clauses)) {
			elgg_deprecated_notice("
					Using literal MySQL statements in 'group_by' options parameter is deprecated.
					Instead use a closure that receives an instanceof of QueryBuilder
					and modifies it with addGroupBy calls.
					
					{{ $clauses }}
				", '3.0');

			$clauses = explode(',', $clauses);
		}

		foreach ($clauses as $clause) {
			if ($clause instanceof Closure) {
				call_user_func($clause, $this->qb);
			} else {
				$this->qb->addGroupBy($clause);
			}
		}

	}

	/**
	 * Builds SQL where clause for type and subtype on main entity table
	 *
	 * @param string $alias       Table alias
	 * @param string $guid_column GUID column name
	 * @param int[]  $guids       GUIDs to match against
	 *
	 * @return CompositeExpression|null
	 */
	public function buildGuidClause($alias, $guid_column, array $guids = null) {
		if (empty($guids)) {
			return;
		}

		return $this->buildAttributeClause($alias, $guid_column, 'in', (array) $guids, 'integer');
	}

	/**
	 * Builds SQL where clause for type and subtype on main entity table
	 *
	 * @param string     $alias          Table alias of the main table
	 * @param string     $type_column    Type column name in the main table
	 * @param string     $subtype_column Subtype column name in the main table
	 * @param null|array $pairs          Array of pairs of types and subtypes
	 *                                   ['object' => ['blog'], 'user' => null]
	 *
	 * @return CompositeExpression|null
	 */
	public function buildTypeClause($alias, $type_column, $subtype_column, array $pairs = null) {

		if (empty($pairs)) {
			return;
		}

		if ($alias) {
			$type_column = "$alias.$type_column";
			$subtype_column = "$alias.$subtype_column";
		}

		$wheres = [];

		$valid_types = Config::getEntityTypes();

		foreach ($pairs as $type => $subtypes) {
			if (!in_array($type, $valid_types)) {
				continue;
			}

			if (is_array($subtypes) && !empty($subtypes)) {
				$subtype_ids = array_map(function ($e) use ($type) {
					return (int) get_subtype_id($type, $e);
				}, $subtypes);

				$wheres[] = $this->qb->expr()->andX()->addMultiple([
					$this->qb->expr()->eq($type_column, $this->qb->param($type, 'string')),
					$this->qb->expr()->in($subtype_column, $this->qb->param($subtype_ids, 'integer'))
				]);
			} else {
				$wheres[] = $this->qb->expr()->eq($type_column, $this->qb->param($type, 'string'));
			}
		}

		if (empty($wheres)) {
			return;
		}

		return $this->qb->expr()->orX()->addMultiple($wheres);
	}

	/**
	 * Adds SQL where clause for entity time limits.
	 *
	 * @param string $alias       Table prefix
	 * @param string $time_column Time column name
	 * @param int    $after       Lower time bound
	 * @param int    $before      Upper time bound
	 *
	 * @return CompositeExpression|null
	 */
	public function buildTimeClause($alias, $time_column, $after = null, $before = null) {

		$wheres = [];

		if ($after) {
			$wheres[] = $this->buildAttributeClause($alias, $time_column, '>=', $after, 'integer');
		}

		if ($before) {
			$wheres[] = $this->buildAttributeClause($alias, $time_column, '<=', $after, 'integer');
		}

		if (empty($wheres)) {
			return;
		}

		return $this->qb->expr()->andX()->addMultiple($wheres);
	}

	/**
	 * Add clauses filtering entities by enabled status
	 *
	 * @param string $alias          Main table alias
	 * @param string $enabled_column Enabled column name
	 * @param bool   $enabled        Enabled status
	 *
	 * @return CompositeExpression|null
	 */
	public function buildEnabledClause($alias, $enabled_column, $enabled = true) {
		$value = $enabled ? 'yes' : 'no';
		return $this->buildAttributeClause($alias, $enabled_column, '=', $value, 'string');
	}

	/**
	 * Adds clauses for enforcing read access to data.
	 *
	 *
	 * Plugin authors can hook into the 'get_sql', 'access' plugin hook to modify,
	 * remove, or add to the where clauses. The plugin hook will pass an array with the current
	 * ors and ands to the function in the form:
	 *  array(
	 *      'ors' => array(),
	 *      'ands' => array()
	 *  )
	 *
	 * @param string $alias             Optional table alias. This is based on the select and join clauses
	 * @param string $access_column     Optional access column name. Default is 'access_id'
	 * @param string $owner_guid_column Optional owner_guid column. Default is 'owner_guid'
	 * @param string $guid_column       Optional guid_column. Default is 'guid'
	 * @param null   $user_guid         Optional GUID for the user that we are retrieving data for
	 *
	 * @return CompositeExpression|null
	 */
	public function buildAccessClause(
		$alias,
		$access_column,
		$owner_guid_column,
		$user_guid
	) {

		if ($alias) {
			$access_column = "$alias.$access_column";
			$owner_guid_column = "$alias.$owner_guid_column";
		}

		$wheres = [];

		if ($user_guid) {
			// include content of user's friends
			$wheres['friends_access'] = $this->qb->expr()->andX()->addMultiple([
				$this->qb->expr()->eq($access_column, $this->qb->param(ACCESS_FRIENDS, 'integer')),
				$this->qb->expr()->in(
					$owner_guid_column,
					$this->qb->selectSubquery('entity_relationships')
						->select('guid_one')
						->where($this->qb->expr()->eq('relationship', $this->qb->param('friend', 'string')))
						->andWhere($this->qb->expr()->eq('guid_two', $this->qb->param($user_guid, 'integer')))
						->getSQL()
				)
			]);

			// include user's content
			$wheres['owner_access'] = $this->qb->expr()->eq($owner_guid_column, $this->qb->param($user_guid, 'integer'));
		}

		// include standard accesses (public, logged in, access collections)
		$access_list = get_access_array($user_guid);
		$wheres['acl_access'] = $this->qb->expr()->in($access_column, $this->qb->param($access_list, 'integer'));

		if (empty($wheres)) {
			return;
		}

		return $this->qb->expr()->orX()->addMultiple($wheres);
	}

	/**
	 * Build attribute clause
	 *
	 * @param string $alias          Main table alias
	 * @param string $column_name    Column name in the main table
	 * @param string $comparison     Comparison operator
	 * @param mixed  $value          Value(s) to compare against
	 * @param string $type           Value type
	 * @param bool   $case_sensitive Case sensitive comparison
	 *
	 * @return CompositeExpression|null
	 */
	public function buildAttributeClause(
		$alias,
		$column_name,
		$comparison,
		$value,
		$type = 'string',
		$case_sensitive = true
	) {
		if ($alias) {
			$column_name = "{$alias}.{$column_name}";
		}

		return $this->buildComparisonClause($column_name, $comparison, $value, $type, $case_sensitive);
	}


	/**
	 * Build metadata clause
	 * Will join metadata table on main table's GUID column
	 *
	 * @param string $alias          Main table alias
	 * @param string $guid_column    GUID column in main table
	 * @param string $metadata_name  Column name in the main table
	 * @param string $comparison     Comparison operator
	 * @param mixed  $value          Value(s) to compare against
	 * @param string $type           Value type
	 * @param bool   $case_sensitive Case sensitive comparison
	 *
	 * @return CompositeExpression|null
	 */
	public function buildMetadataClause(
		$alias,
		$guid_column,
		$metadata_name,
		$comparison,
		$value,
		$type = 'string',
		$case_sensitive = true
	) {

		$joined_alias = $this->qb->joinMetadataTable($alias, $guid_column, $metadata_name);
		$value_column = "{$joined_alias}.value";

		return $this->buildComparisonClause($value_column, $comparison, $value, $type, $case_sensitive);
	}

	/**
	 * Build a composite clause from an array of metadata/attribute pairs
	 * This method will check pair names to determine whether it's an attribute or metadata
	 * Expressions will be merged using 'AND' or 'OR' operator
	 *
	 * @param string     $alias       Entity table alias
	 * @param string     $guid_column GUID column name in the main table
	 * @param array|null $pairs       Metadata/attribute pairs
	 * @param string     $operator    Merge operator
	 *
	 * @return CompositeExpression|null
	 */
	public function buildEntityPropClause($alias, $guid_column, array $pairs = null, $operator = 'AND') {
		if (empty($pairs)) {
			return;
		}

		$wheres = [];

		foreach ($pairs as $pair) {
			$name = elgg_extract('name', $pair);
			$value = elgg_extract('value', $pair);
			$type = elgg_extract('type', $pair, 'string');
			$case_sensitive = elgg_extract('case_sensitive', $pair, true);
			$comparison = elgg_extract('operand', $pair, '=');

			if (in_array($name, AttributeLoader::$primary_attr_names)) {
				$wheres[] = $this->buildAttributeClause($alias, $name, $comparison, $value, $type, $case_sensitive);
			} else {
				$wheres[] = $this->buildMetadataClause($alias, $guid_column, $name, $comparison, $value, $type, $case_sensitive);
			}
		}

		$wheres = array_filter($wheres);
		if (empty($wheres)) {
			return;
		}

		if (strtoupper($operator) == 'OR') {
			return $this->qb->expr()->orX()->addMultiple($wheres);
		} else {
			return $this->qb->expr()->andX()->addMultiple($wheres);
		}
	}

	/**
	 * Add a clause stating existence of a relationship
	 *
	 * @param string $alias             Main table alias
	 * @param string $guid_column       GUID column name in the main table
	 * @param string $name              Relationship name
	 * @param int[]  $relationship_guid Relation GUID(s)
	 * @param bool   $inverse           Inverse relationship
	 * @param int    $created_after     Lower bound of relationship created time
	 * @param int    $created_before    Upper bound of relationship created time
	 *
	 * @return CompositeExpression|void
	 */
	public function buildRelationshipClause(
		$alias,
		$guid_column,
		$name = null,
		$relationship_guid = null,
		$inverse = false,
		$created_after = null,
		$created_before = null
	) {

		if (!$name && !$relationship_guid) {
			return;
		}

		$wheres = [];

		$target_alias = $this->qb->joinRelationshipTable($alias, $guid_column, $name, $inverse);

		if ($relationship_guid) {
			if ($inverse) {
				$wheres[] = $this->buildComparisonClause("{$target_alias}.guid_one", 'in', (array) $relationship_guid, 'integer');
			} else {
				$wheres[] = $this->buildComparisonClause("{$target_alias}.guid_two", 'in', (array) $relationship_guid, 'integer');
			}
		}

		if ($created_after) {
			$wheres[] = $this->buildComparisonClause("{$target_alias}.time_created", 'gte', $created_after, 'integer');
		}

		if ($created_before) {
			$wheres[] = $this->buildComparisonClause("{$target_alias}.time_created", 'lte', $created_after, 'integer');
		}

		if (empty($wheres)) {
			return;
		}

		return $this->qb->expr()->andX()->addMultiple($wheres);
	}

	/**
	 * Add comparison clause
	 *
	 * @param string $x              Comparison value (e.g. prefixed column name)
	 * @param string $comparison     Comparison operator
	 * @param mixed  $y              Value to compare against
	 * @param string $type           Value type for sanitization/casting
	 * @param bool   $case_sensitive Use case sensitive comparison for strings
	 *
	 * @return CompositeExpression|null
	 */
	public function buildComparisonClause($x, $comparison = 'eq', $y = null, $type = 'string', $case_sensitive = false) {

		if (!$case_sensitive && $type === 'string') {
			$x = "LOWER($x)";
			if (is_array($y)) {
				$y = array_map(function ($e) {
					return is_string($e) ? strtolower($e) : $e;
				});
			} else if (is_string($y)) {
				$y = strtolower($y);
			}
		}

		$match_expr = null;

		switch ($comparison) {
			case '=' :
			case 'eq' :
			case 'in' :
				if (is_array($y)) {
					$match_expr = $this->qb->expr()->in($x, $this->qb->param($y, $type));
				} else {
					$match_expr = $this->qb->expr()->eq($x, $this->qb->param($y, $type));
				}
				break;

			case '!=' :
			case 'neq' :
			case 'not in' :
				if (is_array($y)) {
					$match_expr = $this->qb->expr()->notIn($x, $this->qb->param($y, $type));
				} else {
					$match_expr = $this->qb->expr()->neq($x, $this->qb->param($y, $type));
				}
				break;

			case 'like' :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->like($x, $this->qb->param($val, 'string')));
					}
				}
				break;

			case '>';
			case 'gt' :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->gt($x, $this->qb->param($val, 'integer')));
					}
				}
				break;

			case '<' :
			case 'lt' :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->lt($x, $this->qb->param($val, 'integer')));
					}
				}
				break;

			case '>=' :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->gte($x, $this->qb->param($val, 'integer')));
					}
				}
				break;

			case '<=' :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->lte($x, $this->qb->param($val, 'integer')));
					}
				}
				break;

			case 'is null' :
				$match_expr = $this->qb->expr()->isNull($x);
				break;

			case 'is not null' :
				$match_expr = $this->qb->expr()->isNotNull($x);
				break;

			default :
				if (isset($y)) {
					$match_expr = $this->qb->expr()->andX();
					foreach ((array) $y as $val) {
						$match_expr->add($this->qb->expr()->comparison($x, $comparison, $this->qb->param($val, $type)));
					}
				}
				break;
		}

		return $match_expr;
	}
}

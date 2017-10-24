<?php

namespace Elgg\Database;

use Closure;
use DateTime;
use Elgg\AttributeLoader;

/**
 * Utility class for building DB queries for fetching Elgg entities
 */
class EntityQuery {

	/**
	 * @var string
	 */
	protected $alias;

	/**
	 * @var EntityQueryOptions
	 */
	protected $options;

	/**
	 * @var array
	 */
	protected $filters = [];

	/**
	 * Constructor
	 *
	 * @param EntityQueryOptions $options List options
	 * @param string             $alias   entities table alias
	 */
	public function __construct(EntityQueryOptions $options, $alias = 'e') {
		$this->alias = $alias;
		$this->options = $options;
	}

	/**
	 * Build a query from an array of options
	 *
	 * @param array $options Legacy ege* options
	 *
	 * @return static
	 */
	public static function create(array $options = []) {
		return new static(EntityQueryOptions::factory($options));
	}

	/**
	 * Get entities from an array of options
	 *
	 * @param array $options Options
	 *
	 * @return \ElggEntity[]|int|mixed If count, int. Otherwise an array or an Elgg\BatchResult. false on errors.
	 */
	public static function getEntities(array $options) {
		$query = new static(EntityQueryOptions::factory($options));

		return $query->execute();
	}

	/**
	 * Returns a normalized options array
	 *
	 * @return EntityQueryOptions
	 */
	public function getOptions() {
		return $this->options->normalize();
	}

	/**
	 * Filter by attribute or metadata value
	 * Adds a required condition
	 *
	 * The resulting query will contain entities that match all conditions added with EntityQuery::where(),
	 * as well as at least one condition set with EntityQuery::orWhere()
	 *
	 * The code below will interpret to:
	 * (status = 'published')
	 *     AND
	 * (LOWER(category) = 'social')
	 *     AND
	 * (title LIKE '%elgg%' OR tags IN ('elgg', 'network'))
	 *
	 * <code>
	 * $query->where('status', 'eq', 'published', 'string', true)
	 *       ->where('category', 'eq', 'social', 'string', false)
	 *       ->orWhere('title', 'like', '%elgg%')
	 *       ->orWhere('tags', 'in', ['elgg', 'network'])
	 * </code>
	 *
	 * @tip For more complex combination logic use EntityQuery::callback()
	 *
	 * @param string $field          Attribute or metadata name
	 * @param string $comparison     Comparison operator
	 * @param mixed  $value          Attribute or metadata value(s)
	 * @param string $type           Value(s) type 'integer'|'string'
	 * @param bool   $case_sensitive Case sensitive comparison
	 *
	 * @return static
	 */
	public function where($field, $comparison = 'eq', $value = null, $type = null, $case_sensitive = true) {
		$this->options->metadata_name_value_pairs[] = [
			'name' => $field,
			'value' => $value,
			'type' => $type,
			'operand' => $comparison,
			'case_sensitive' => $case_sensitive,
		];

		return $this;
	}

	/**
	 * Filter by attribute or metadata value
	 * Adds an optional condition to a set of requirements where at least one condition should be true
	 *
	 * @see EntityQuery::where()
	 *
	 * @param string $field          Attribute or metadata name
	 * @param string $comparison     Comparison operator
	 * @param mixed  $value          Attribute or metadata value(s)
	 * @param string $type           Value(s) type 'integer'|'string'
	 * @param bool   $case_sensitive Case sensitive comparison
	 *
	 * @return static
	 */
	public function orWhere($field, $comparison = 'eq', $value = null, $type = null, $case_sensitive = true) {
		$this->options->search_name_value_pairs[] = [
			'name' => $field,
			'value' => $value,
			'type' => $type,
			'operand' => $comparison,
			'case_sensitive' => $case_sensitive,
		];

		return $this;
	}

	/**
	 * Filter by type and subtype combination
	 *
	 * @param string               $type     Entity type
	 * @param null|string|string[] $subtypes Subtypes of entity type
	 *
	 * @return static
	 * @throws \InvalidParameterException
	 */
	public function whereType($type, $subtypes = ELGG_ENTITIES_ANY_VALUE) {
		$this->options->type_subtype_pairs[$type] = $subtypes;

		return $this;
	}

	/**
	 * Filter by guid
	 *
	 * @param mixed ...$guids Entities or GUIDs
	 *
	 * @return static
	 */
	public function whereGuid(...$guids) {
		$this->options->guids[] = $guids;

		return $this;
	}


	/**
	 * Filter by owner
	 *
	 * @param mixed ...$guids Entities or GUIDs
	 *
	 * @return static
	 */
	public function whereOwnerGuid(...$guids) {
		$this->options->owner_guids[] = $guids;

		return $this;
	}

	/**
	 * Filter by container
	 *
	 * @param mixed ...$guids Entities or GUIDs
	 *
	 * @return static
	 */
	public function whereContainerGuid(...$guids) {
		$this->options->container_guids[] = $guids;

		return $this;
	}

	/**
	 * Filter by created time
	 *
	 * @param DateTime|int|string $after  After date
	 * @param DateTime|int|string $before Before date
	 *
	 * @return static
	 */
	public function createdBetween($after = null, $before = null) {
		$this->options->created_time_lower = $after;
		$this->options->created_time_upper = $before;

		return $this;
	}

	/**
	 * Filter by updated time
	 *
	 * @param DateTime|int|string $after  After date
	 * @param DateTime|int|string $before Before date
	 *
	 * @return static
	 */
	public function updatedBetween($after = null, $before = null) {
		$this->options->modified_time_lower = $after;
		$this->options->modified_time_upper = $before;

		return $this;
	}

	/**
	 * Filter by existing relationship
	 *
	 * @param string              $name               Relationship name(s)
	 * @param int[]|ElggEntity[]  $relationship_guids Target entities
	 * @param bool                $inverse            Inverse relationship
	 * @param DateTime|int|string $created_after      After date
	 * @param DateTime|int|string $created_before     Before date
	 *
	 * @return static
	 */
	public function hasRelationship($name, $relationship_guid = null, $inverse = false, $created_after = null, $created_before = null) {

		$this->options->relationship = $name;
		$this->options->relationship_guid = $relationship_guid;
		$this->options->inverse_relationship = $inverse;
		$this->options->relationship_created_time_lower = $created_after;
		$this->options->relationship_created_time_upper = $created_before;
		$this->options->relationship_join_on = 'guid';

		return $this;
	}

	/**
	 * Filter QueryBuilder instance using a callback
	 *
	 * <code>
	 * function(QueryBuilder $qb) use ($access_ids) {
	 *     $alias = $qb->getFromAlias();
	 *     return $qb->andWhere($qb->expr()->in("{$alias}.access_id", $qb->param($access_ids, 'integer'));
	 * }
	 *
	 * function (QueryBuilder $qb) {
	 *     $alias = $qb->getFromAlias();
	 *     $md_alias = $qb->joinMetadataTable($alias, 'guid', 'status');
	 *     return $qb->andWhere($qb->expr()->eq("$md_alias.value", $qb->params('published', 'string'));
	 * }
	 *
	 * function(QueryBuilder $qb) {
	 *     $alias = $qb->getFromAlias();
	 *     $md_alias = $qb->joinMetadataTable($alias, 'guid', 'priority', 'left');
	 *     $qb->addSelect("$md_alias.value");
	 *     $qb->addOrderBy("CAST($md_alias.value as SIGNED)", 'asc');
	 *     return $qb;
	 * }
	 * </code>
	 *
	 * @param callable $func Callback function or sql expression
	 *                       Callback function receives an
	 *                       instanceof QueryBuilder as argument

	 *
	 * @return static
	 */
	public function filter(callable $func) {
		$this->options->wheres[] = $func;

		return $this;
	}

	/**
	 * Add sorting by attribute or metadata field
	 *
	 * @param string $field     Field name
	 * @param string $direction 'asc'|'desc'
	 * @param bool   $signed    If signed, the values will be cast to integer before sorting
	 * @param string $join_type Type of JOIN to use for metadata sorting
	 * @param int    $priority  Sort priority
	 *
	 * @return static
	 */
	public function sortBy($field, $direction, $signed = false, $join_type = 'inner', $priority = 500) {
		$this->options->sort_by[] = [
			'field' => $field,
			'direction' => $direction,
			'signed' => $signed,
			'join_type' => $join_type,
			'priority' => $priority,
		];

		return $this;
	}

	/**
	 * Execute query
	 * @return \ElggEntity[]|int|mixed If count, int. Otherwise an array or an Elgg\BatchResult. false on errors.
	 */
	public function execute() {

		$options = $this->options->normalize();

		if ($options->batch && !$options->count) {
			$batch_size = $options->batch_size;
			$batch_inc_offset = $options->batch_inc_offset;
			unset($options->batch, $options->batch_size, $options->batch_inc_offset);

			return new \ElggBatch([$this, 'getEntities'], $options, null, $batch_size, $batch_inc_offset);
		}

		$query = $this->buildQuery();

		return _elgg_services()->entityTable->fetch($query, $options);
	}

	/**
	 * Build query
	 *
	 * @param QueryBuilder $qb QueryBuilder object to apply options to
	 *                         If not set, will create a new query
	 *
	 * @return QueryBuilder
	 */
	public function buildQuery(QueryBuilder $qb = null) {

		if (!isset($qb)) {
			$qb = QueryBuilder::createSelect('entities', $this->alias);
		}

		$options = $this->options->normalize();

		$builder = new ExpressionBuilder($qb);

		$builder->applyJoinClauses($options['joins']);

		if ($options->count) {
			$count_expr = $options->distinct ? "DISTINCT {$this->alias}.guid" : "*";
			$qb->select("COUNT({$count_expr}) AS total");
		} else {
			$distinct = $options->distinct ? "DISTINCT" : "";
			$qb->select("$distinct {$this->alias}.*");

			$builder->applySelectClauses($options['selects']);
			$builder->applyOrderByClauses($this->alias, 'guid', $options->sort_by, $options->order_by, $options->reverse_order_by);
			$builder->applyGroupByClauses($options->group_by);

			if ($options->limit) {
				$qb->setMaxResults((int) $options->limit);
				$qb->setFirstResult((int) $options->offset);
			}
		}

		$wheres = [];

		$wheres[] = $builder->applyWhereClauses($options['wheres']);

		if (!$options->ignore_access) {
			$wheres[] = $builder->buildAccessClause($this->alias, 'access_id', 'owner_guid', $options->access_user_guid);
		}

		if ($options->use_enabled_clause) {
			$wheres[] = $builder->buildEnabledClause($this->alias, 'enabled', true);
		}

		$wheres[] = $builder->buildGuidClause($this->alias, 'guid', $options->guids);
		$wheres[] = $builder->buildGuidClause($this->alias, 'owner_guid', $options->owner_guids);
		$wheres[] = $builder->buildGuidClause($this->alias, 'container_guid', $options->container_guids);

		$wheres[] = $builder->buildTypeClause($this->alias, 'type', 'subtype', $options->type_subtype_pairs);

		$wheres[] = $builder->buildTimeClause($this->alias, 'time_created', $options->created_time_lower, $options->created_time_upper);
		$wheres[] = $builder->buildTimeClause($this->alias, 'time_updated', $options->modified_time_lower, $options->modified_time_upper);

		$wheres[] = $builder->buildEntityPropClause($this->alias, 'guid', $options->metadata_name_value_pairs, $options->metadata_name_value_pairs_operator);
		$wheres[] = $builder->buildEntityPropClause($this->alias, 'guid', $options->search_name_value_pairs, 'OR');

		$wheres[] = $builder->buildRelationshipClause(
			$this->alias,
			$options->relationship_join_on,
			$options->relationship,
			$options->inverse_relationship,
			$options->relationship_created_time_lower,
			$options->relationship_created_time_upper
		);

		$wheres = array_filter($wheres);

		if (!empty($wheres)) {
			$qb->andWhere($qb->expr()->andX()->addMultiple($wheres));
		}

		return $qb;
	}
}

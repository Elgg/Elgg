<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\QueryOptions;

/**
 * Builds queries for filtering entities by their properties in the entities table
 */
class EntityWhereClause extends WhereClause {

	/**
	 * @var int|int[]
	 */
	public $guids;

	/**
	 * @var int|int[]
	 */
	public $owner_guids;

	/**
	 * @var int|int[]
	 */
	public $container_guids;

	/**
	 * @var array
	 */
	public $type_subtype_pairs;

	/**
	 * @var int|int[]
	 */
	public $access_ids;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $created_before;

	/**
	 * @var \DateTime|string|int
	 */
	public $updated_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $updated_before;

	/**
	 * @var \DateTime|string|int
	 */
	public $last_action_after;

	/**
	 * @var \DateTime|string|int
	 */
	public $last_action_before;

	/**
	 * @var string
	 */
	public $enabled;

	/**
	 * @var bool
	 */
	public $ignore_access;

	/**
	 * @var bool
	 */
	public $use_enabled_clause;

	/**
	 * @var int
	 */
	public $viewer_guid;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = '') {

		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);

		$access = new AccessWhereClause();
		$access->use_enabled_clause = $this->use_enabled_clause;
		$access->ignore_access = $this->ignore_access;
		$access->viewer_guid = $this->viewer_guid;
		$wheres[] = $access->prepare($qb, $table_alias);

		$type = new TypeSubtypeWhereClause();
		$type->type_subtype_pairs = $this->type_subtype_pairs;
		$wheres[] = $type->prepare($qb, $table_alias);

		$wheres[] = $qb->compare($alias('guid'), '=', $this->guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('owner_guid'), '=', $this->owner_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('container_guid'), '=', $this->container_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);
		$wheres[] = $qb->between($alias('time_updated'), $this->updated_after, $this->updated_before, ELGG_VALUE_TIMESTAMP);
		$wheres[] = $qb->between($alias('last_action'), $this->last_action_after, $this->last_action_before, ELGG_VALUE_TIMESTAMP);
		$wheres[] = $qb->compare($alias('enabled'), '=', $this->enabled, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('access_id'), '=', $this->access_ids, ELGG_VALUE_ID);

		return $qb->merge($wheres);
	}

	/**
	 * Build new clause from options
	 *
	 * @param QueryOptions $options Options
	 * @return static
	 */
	public static function factory(QueryOptions $options) {
		$where = new static();
		$where->guids = $options->guids;
		$where->owner_guids = $options->owner_guids;
		$where->container_guids = $options->container_guids;
		$where->type_subtype_pairs = $options->type_subtype_pairs;
		$where->created_after = $options->created_after;
		$where->created_before = $options->created_before;
		$where->updated_after = $options->updated_after;
		$where->updated_before = $options->updated_before;
		$where->last_action_after = $options->last_action_after;
		$where->last_action_before = $options->last_action_before;
		$where->access_ids = $options->access_ids;

		return $where;
	}
}

<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * Builds queries for filtering entities by their properties in the entities table
 */
class EntityWhereClause extends AccessWhereClause {

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
	 * @var string
	 */
	public $access_column = 'access_id';

	/**
	 * @var string
	 */
	public $owner_guid_column = 'owner_guid';

	/**
	 * @var string
	 */
	public $guid_column = 'guid';

	/**
	 * @var string
	 */
	public $enabled_column = 'enabled';

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = '') {

		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);

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
}

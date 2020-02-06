<?php

namespace Elgg\Database\Clauses;

use DateTime;
use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\InvalidParameterException;

/**
 * Builds queries for matching annotations against their properties
 */
class AnnotationWhereClause extends WhereClause {

	/**
	 * @var int|int[]
	 */
	public $ids;

	/**
	 * @var int|int[]|\ElggEntity[]
	 */
	public $entity_guids;

	/**
	 * @var int|int[]|\ElggEntity[]
	 */
	public $owner_guids;

	/**
	 * @var string
	 */
	public $enabled;

	/**
	 * @var int|int[]
	 */
	public $access_ids;

	/**
	 * @var string|string[]
	 */
	public $names;
	
	/**
	 * @var string
	 */
	public $comparison = '=';

	/**
	 * @var string|string[]
	 */
	public $values;

	/**
	 * @var string
	 */
	public $value_type = ELGG_VALUE_STRING;

	/**
	 * @var bool
	 */
	public $case_sensitive = true;

	/**
	 * @var int|string|DateTime
	 */
	public $created_after;

	/**
	 * @var int|string|DateTime
	 */
	public $created_before;

	/**
	 * @var string
	 */
	public $sort_by_direction;

	/**
	 * @var string
	 */
	public $sort_by_calculation;

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
	 * @throws InvalidParameterException
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);

		$access = new AccessWhereClause();
		$access->use_enabled_clause = $this->use_enabled_clause;
		$access->ignore_access = $this->ignore_access;
		$access->viewer_guid = $this->viewer_guid;
		$access->guid_column = 'entity_guid';
		$wheres[] = $access->prepare($qb, $table_alias);

		$wheres[] = $qb->compare($alias('id'), '=', $this->ids, ELGG_VALUE_ID);
		$wheres[] = $qb->compare($alias('name'), '=', $this->names, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('value'), $this->comparison, $this->values, $this->value_type, $this->case_sensitive);
		$wheres[] = $qb->compare($alias('entity_guid'), '=', $this->entity_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('owner_guid'), '=', $this->owner_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('enabled'), '=', $this->enabled, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('access_id'), '=', $this->access_ids, ELGG_VALUE_ID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		if ($this->sort_by_calculation) {
			if (!in_array(strtolower($this->sort_by_calculation), QueryBuilder::$calculations)) {
				throw new InvalidParameterException("'$this->sort_by_calculation' is not a valid numeric calculation formula");
			}

			$calculation = "{$this->sort_by_calculation}(CAST({$alias('value')} AS DECIMAL(10, 2)))";
			$select_alias = "annotation_calculation";

			$qb->addSelect("$calculation AS $select_alias");
			$qb->addGroupBy($alias('entity_guid'));
			$qb->addOrderBy($select_alias, $this->sort_by_direction);
		} else if ($this->sort_by_direction) {
			$column = $alias('value');
			if ($this->value_type == ELGG_VALUE_INTEGER) {
				$column = "CAST($column AS SIGNED)";
			}
			$qb->addOrderBy($column, $this->sort_by_direction);
		}

		return $qb->merge($wheres);
	}

}

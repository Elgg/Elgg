<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\DomainException;

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
	 * @var int|int[]
	 */
	public $access_ids;

	/**
	 * @var string|string[]
	 */
	public $names;
	
	public string $comparison = '=';

	/**
	 * @var string|string[]
	 */
	public $values;

	public string $value_type = ELGG_VALUE_STRING;

	public bool $case_sensitive = true;

	/**
	 * @var int|string|\DateTime
	 */
	public $created_after;

	/**
	 * @var int|string|\DateTime
	 */
	public $created_before;

	public ?string $sort_by_direction = null;

	public ?string $sort_by_calculation = null;

	public ?bool $ignore_access = null;

	public ?int $viewer_guid = null;

	/**
	 * {@inheritdoc}
	 * @throws DomainException
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);

		$access = new AccessWhereClause();
		$access->ignore_access = $this->ignore_access;
		$access->use_enabled_clause = false;
		$access->viewer_guid = $this->viewer_guid;
		$access->guid_column = 'entity_guid';
		$wheres[] = $access->prepare($qb, $table_alias);

		$wheres[] = $qb->compare($alias('id'), '=', $this->ids, ELGG_VALUE_ID);
		$wheres[] = $qb->compare($alias('name'), '=', $this->names, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('value'), $this->comparison, $this->values, $this->value_type, $this->case_sensitive);
		$wheres[] = $qb->compare($alias('entity_guid'), '=', $this->entity_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('owner_guid'), '=', $this->owner_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('access_id'), '=', $this->access_ids, ELGG_VALUE_ID);
		$wheres[] = $qb->between($alias('time_created'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		if ($this->sort_by_calculation) {
			if (!in_array(strtolower($this->sort_by_calculation), QueryBuilder::CALCULATIONS)) {
				throw new DomainException("'{$this->sort_by_calculation}' is not a valid numeric calculation formula");
			}

			$calculation = "{$this->sort_by_calculation}(CAST({$alias('value')} AS DECIMAL(10, 2)))";
			$select_alias = 'annotation_calculation';

			$qb->addSelect("{$calculation} AS {$select_alias}");
			$qb->addGroupBy($alias('entity_guid'));
			$qb->addOrderBy($select_alias, $this->sort_by_direction);
		} elseif ($this->sort_by_direction) {
			$column = $alias('value');
			if ($this->value_type == ELGG_VALUE_INTEGER) {
				$column = "CAST({$column} AS SIGNED)";
			}
			
			$qb->addOrderBy($column, $this->sort_by_direction);
		}

		return $qb->merge($wheres);
	}
}

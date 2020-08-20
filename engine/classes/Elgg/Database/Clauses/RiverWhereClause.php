<?php

namespace Elgg\Database\Clauses;

use DateTime;
use Elgg\Database\QueryBuilder;
use ElggAnnotation;
use ElggEntity;

/**
 * Builds queries for matching river items against their properties
 */
class RiverWhereClause extends WhereClause {

	/**
	 * @var int[]
	 */
	public $ids;

	/**
	 * @var array
	 */
	public $type_subtype_pairs;

	/**
	 * @var string[]
	 */
	public $action_types;

	/**
	 * @var string[]
	 */
	public $views;

	/**
	 * @var int[]|ElggEntity[]
	 */
	public $subject_guids;

	/**
	 * @var int[]|ElggEntity[]
	 */
	public $object_guids;

	/**
	 * @var int[]|ElggEntity[]
	 */
	public $target_guids;

	/**
	 * @var int[]|ElggAnnotation[]
	 */
	public $annotation_ids;

	/**
	 * @var int|string|DateTime
	 */
	public $created_after;

	/**
	 * @var int|string|DateTime
	 */
	public $created_before;

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$alias = function ($column) use ($table_alias) {
			return $table_alias ? "{$table_alias}.{$column}" : $column;
		};

		$wheres = [];
		$wheres[] = parent::prepare($qb, $table_alias);
		
		$types = new TypeSubtypeWhereClause();
		$types->type_subtype_pairs = $this->type_subtype_pairs;
		$wheres[] = $types->prepare($qb, $table_alias);

		$wheres[] = $qb->compare($alias('id'), '=', $this->ids, ELGG_VALUE_ID);
		$wheres[] = $qb->compare($alias('annotation_id'), '=', $this->annotation_ids, ELGG_VALUE_ID);
		$wheres[] = $qb->compare($alias('view'), '=', $this->views, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('action_type'), '=', $this->action_types, ELGG_VALUE_STRING);
		$wheres[] = $qb->compare($alias('subject_guid'), '=', $this->subject_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('object_guid'), '=', $this->object_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->compare($alias('target_guid'), '=', $this->target_guids, ELGG_VALUE_GUID);
		$wheres[] = $qb->between($alias('posted'), $this->created_after, $this->created_before, ELGG_VALUE_TIMESTAMP);

		return $qb->merge($wheres);
	}

}

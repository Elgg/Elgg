<?php

namespace Elgg\Database;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\RiverWhereClause;
use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\LogicException;

/**
 * River repository contains methods for fetching/counting river items
 *
 * @internal
 */
class River extends Repository {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $options = []) {
		$singulars = [
			'id',
			'subject_guid',
			'object_guid',
			'target_guid',
			'annotation_id',
			'action_type',
			'type',
			'subtype',
			'view',
		];

		$options = QueryOptions::normalizePluralOptions($options, $singulars);

		$defaults = [
			'ids' => null,
			'subject_guids' => null,
			'object_guids' => null,
			'target_guids' => null,
			'annotation_ids' => null,
			'views' => null,
			'action_types' => null,
			'created_after' => null,
			'created_before' => null,
			'limit' => 20,
			'offset' => 0,
		];

		$options = array_merge($defaults, $options);
		
		// prevent conflicts with annotation ids for annotation where clause
		$options['river_annotation_ids'] = elgg_extract('river_annotation_ids', $options, $options['annotation_ids']);
		unset($options['annotation_ids']);
		
		parent::__construct($options);
	}

	/**
	 * Build and execute a new query from an array of legacy options
	 *
	 * @param array $options Options
	 *
	 * @return \ElggRiverItem[]|int|mixed
	 */
	public static function find(array $options = []) {
		return parent::find($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$qb = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);

		$count_expr = $this->options->distinct ? "DISTINCT {$qb->getTableAlias()}.id" : '*';
		$qb->select("COUNT({$count_expr}) AS total");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		return $result ? (int) $result->total : 0;
	}

	/**
	 * Performs a mathematical calculation on river annotations
	 *
	 * @param string $function      Valid numeric function
	 * @param string $property      Property name
	 * @param string $property_type 'annotation'
	 *
	 * @return int|float
	 * @throws DomainException
	 */
	public function calculate($function, $property, $property_type = 'annotation') {
		if (!in_array(strtolower($function), QueryBuilder::CALCULATIONS)) {
			throw new DomainException("'{$function}' is not a valid numeric function");
		}

		$qb = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);

		$alias = AnnotationsTable::DEFAULT_JOIN_ALIAS;
		if (!empty($this->options->annotation_name_value_pairs) && $this->options->annotation_name_value_pairs[0]->names != $property) {
			$alias = $qb->getNextJoinAlias();

			$annotation = AnnotationWhereClause::factory(['names' => $property]);
			$qb->addClause($annotation, $alias);
		}

		$qb->joinAnnotationTable($qb->getTableAlias(), 'annotation_id', null, 'inner', $alias);
		$qb->select("{$function}({$alias}.value) AS calculation");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		return $result ? (int) $result->calculation : 0;
	}

	/**
	 * Fetch river items
	 *
	 * @param int      $limit    Limit
	 * @param int      $offset   Offset
	 * @param callable $callback Custom callback
	 *
	 * @return \ElggEntity[]
	 */
	public function get($limit = null, $offset = null, $callback = null) {
		$qb = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);

		$distinct = $this->options->distinct ? 'DISTINCT ' : '';
		$qb->select("{$distinct}{$qb->getTableAlias()}.*");

		$this->expandInto($qb, $qb->getTableAlias());

		$qb = $this->buildQuery($qb);

		// add default ordering
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($this->options->order_by) && $original_order !== false) {
			$qb->addOrderBy("{$qb->getTableAlias()}.posted", 'desc');
		}

		if ($limit > 0) {
			$qb->setMaxResults((int) $limit);
			$qb->setFirstResult((int) $offset);
		}

		$callback = $callback ?: $this->options->callback;
		if (!isset($callback)) {
			$callback = function ($row) {
				return new \ElggRiverItem($row);
			};
		}

		$items = _elgg_services()->db->getData($qb, $callback);

		if (!empty($items)) {
			$preload = array_filter($items, function($e) {
				return $e instanceof \ElggRiverItem;
			});

			_elgg_services()->entityPreloader->preload($preload, [
				'subject_guid',
				'object_guid',
				'target_guid',
			]);
		}

		return $items;
	}

	/**
	 * Execute the query resolving calculation, count and/or batch options
	 *
	 * @return array|\ElggData[]|\ElggEntity[]|int|\ElggBatch
	 * @throws LogicException
	 */
	public function execute() {
		if ($this->options->annotation_calculation) {
			$clauses = $this->options->annotation_name_value_pairs;
			if (count($clauses) > 1 && $this->options->annotation_name_value_pairs_operator !== 'OR') {
				throw new LogicException('Annotation calculation can not be performed on multiple annotation name value pairs merged with AND');
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->annotation_calculation, $clause->names, 'annotation');
		} elseif ($this->options->count) {
			return $this->count();
		} elseif ($this->options->batch) {
			return $this->batch($this->options->limit, $this->options->offset, $this->options->callback);
		} else {
			return $this->get($this->options->limit, $this->options->offset, $this->options->callback);
		}
	}

	/**
	 * Build a database query
	 *
	 * @param QueryBuilder $qb the Elgg QueryBuilder
	 *
	 * @return QueryBuilder
	 */
	protected function buildQuery(QueryBuilder $qb) {
		$ands = [];

		foreach ($this->options->joins as $join) {
			$join->prepare($qb, $qb->getTableAlias());
		}

		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, $qb->getTableAlias());
		}

		$ands[] = $this->buildRiverClause($qb);
		$ands[] = $this->buildEntityClauses($qb);
		$ands[] = $this->buildPairedAnnotationClause($qb, $this->options->annotation_name_value_pairs, $this->options->annotation_name_value_pairs_operator);
		$ands[] = $this->buildPairedRelationshipClause($qb, $this->options->relationship_pairs);

		$ands = $qb->merge($ands);

		if (!empty($ands)) {
			$qb->andWhere($ands);
		}

		return $qb;
	}

	/**
	 * Process river properties
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return CompositeExpression|mixed|null|string
	 */
	protected function buildRiverClause(QueryBuilder $qb) {
		$where = new RiverWhereClause();
		$where->ids = $this->options->ids;
		$where->views = $this->options->views;
		$where->action_types = $this->options->action_types;
		$where->subject_guids = $this->options->subject_guids;
		$where->object_guids = $this->options->object_guids;
		$where->target_guids = $this->options->target_guids;
		$where->created_after = $this->options->created_after;
		$where->created_before = $this->options->created_before;
		$where->annotation_ids = $this->options->river_annotation_ids;

		return $where->prepare($qb, $qb->getTableAlias());
	}

	/**
	 * Add subject, object and target clauses
	 * Make sure all three are accessible by the user
	 *
	 * @param QueryBuilder $qb Query builder
	 *
	 * @return CompositeExpression|mixed|null|string
	 */
	public function buildEntityClauses($qb) {
		$use_access_clause = !_elgg_services()->userCapabilities->canBypassPermissionsCheck();

		$ands = [];

		if (!empty($this->options->subject_guids) || $use_access_clause) {
			$qb->joinEntitiesTable($qb->getTableAlias(), 'subject_guid', 'inner', 'se');
			$subject = new EntityWhereClause();
			$subject->guids = $this->options->subject_guids;
			$ands[] = $subject->prepare($qb, 'se');
		}

		if (!empty($this->options->object_guids) || $use_access_clause || !empty($this->options->type_subtype_pairs)) {
			$qb->joinEntitiesTable($qb->getTableAlias(), 'object_guid', 'inner', 'oe');
			$object = new EntityWhereClause();
			$object->guids = $this->options->object_guids;
			$object->type_subtype_pairs = $this->options->type_subtype_pairs;
			$ands[] = $object->prepare($qb, 'oe');
		}

		if (!empty($this->options->target_guids) || $use_access_clause) {
			$target_ors = [];
			$qb->joinEntitiesTable($qb->getTableAlias(), 'target_guid', 'left', 'te');
			$target = new EntityWhereClause();
			$target->guids = $this->options->target_guids;
			$target_ors[] = $target->prepare($qb, 'te');
			// Note the LEFT JOIN
			$target_ors[] = $qb->compare('te.guid', 'IS NULL');
			$ands[] = $qb->merge($target_ors, 'OR');
		}

		return $qb->merge($ands);
	}

	/**
	 * Process annotation name value pairs
	 * Joins the annotation table on entity guid in the entities table and applies annotation where clauses
	 *
	 * @param QueryBuilder            $qb      Query builder
	 * @param AnnotationWhereClause[] $clauses Where clauses
	 * @param string                  $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedAnnotationClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinAnnotationTable($qb->getTableAlias(), 'annotation_id');
			} else {
				$joined_alias = $qb->joinAnnotationTable($qb->getTableAlias(), 'annotation_id', $clause->names);
			}

			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}

	/**
	 * Process relationship pairs
	 *
	 * @param QueryBuilder              $qb      Query builder
	 * @param RelationshipWhereClause[] $clauses Where clauses
	 * @param string                    $boolean Merge boolean
	 *
	 * @return CompositeExpression|string
	 */
	protected function buildPairedRelationshipClause(QueryBuilder $qb, $clauses, $boolean = 'AND') {
		$parts = [];

		foreach ($clauses as $clause) {
			$join_on = $clause->join_on === 'guid' ? 'subject_guid' : $clause->join_on;
			if (strtoupper($boolean) === 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinRelationshipTable($qb->getTableAlias(), $join_on, null, $clause->inverse, 'inner', RelationshipsTable::DEFAULT_JOIN_ALIAS);
			} else {
				$joined_alias = $qb->joinRelationshipTable($qb->getTableAlias(), $join_on, $clause->names, $clause->inverse);
			}
			
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}
}

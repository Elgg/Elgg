<?php

namespace Elgg\Database;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\RiverWhereClause;
use ElggEntity;
use ElggRiverItem;
use InvalidArgumentException;
use InvalidParameterException;

/**
 * River repository contains methods for fetching/counting river items
 *
 * API IN FLUX Do not access the methods directly, use elgg_get_river() instead
 *
 * @access private
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

		$options = LegacyQueryOptionsAdapter::normalizePluralOptions($options, $singulars);

		$defaults = [
			'ids' => null,
			'subject_guids' => null,
			'object_guids' => null,
			'target_guids' => null,
			'annotation_ids' => null,
			'views' => null,
			'action_types' => null,
			'posted_time_lower' => null,
			'posted_time_upper' => null,
			'limit' => 20,
			'offset' => 0,
		];

		$options = array_merge($defaults, $options);
		parent::__construct($options);
	}

	/**
	 * Build and execute a new query from an array of legacy options
	 *
	 * @param array $options Options
	 *
	 * @return ElggRiverItem[]|int|mixed
	 */
	public static function find(array $options = []) {
		return parent::find($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$qb = Select::fromTable('river', 'rv');

		$count_expr = $this->options->distinct ? "DISTINCT rv.id" : "*";
		$qb->select("COUNT({$count_expr}) AS total");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		if (!$result) {
			return 0;
		}

		return (int) $result->total;
	}

	/**
	 * Performs a mathematical calculation on river annotations
	 *
	 * @param string $function      Valid numeric function
	 * @param string $property      Property name
	 * @param string $property_type 'annotation'
	 *
	 * @return int|float
	 * @throws InvalidParameterException
	 */
	public function calculate($function, $property, $property_type = 'annotation') {

		if (!in_array(strtolower($function), QueryBuilder::$calculations)) {
			throw new InvalidArgumentException("'$function' is not a valid numeric function");
		}

		$qb = Select::fromTable('river', 'rv');

		$alias = 'n_table';
		if (!empty($this->options->annotation_name_value_pairs) && $this->options->annotation_name_value_pairs[0]->names != $property) {
			$alias = $qb->getNextJoinAlias();

			$annotation = new AnnotationWhereClause();
			$annotation->names = $property;
			$qb->addClause($annotation, $alias);
		}

		$qb->join('rv', 'annotations', $alias, "rv.annotation_id = $alias.id");
		$qb->select("{$function}(n_table.value) AS calculation");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		if (!$result) {
			return 0;
		}

		return (int) $result->calculation;
	}

	/**
	 * Fetch river items
	 *
	 * @param int      $limit    Limit
	 * @param int      $offset   Offset
	 * @param callable $callback Custom callback
	 *
	 * @return ElggEntity[]
	 * @throws \DatabaseException
	 */
	public function get($limit = null, $offset = null, $callback = null) {

		$qb = Select::fromTable('river', 'rv');

		$distinct = $this->options->distinct ? "DISTINCT" : "";
		$qb->select("$distinct rv.*");

		$this->expandInto($qb, 'rv');

		$qb = $this->buildQuery($qb);

		// Keeping things backwards compatible
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($original_order) && $original_order !== false) {
			$qb->addOrderBy('rv.posted', 'desc');
		}

		if ($limit) {
			$qb->setMaxResults((int) $limit);
			$qb->setFirstResult((int) $offset);
		}

		$callback = $callback ? : $this->options->callback;
		if (!isset($callback)) {
			$callback = function ($row) {
				return new ElggRiverItem($row);
			};
		}

		$items = _elgg_services()->db->getData($qb, $callback);

		if ($items) {
			$preload = array_filter($items, function($e) {
				return $e instanceof ElggRiverItem;
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
	 * @return array|\ElggData[]|ElggEntity[]|false|int
	 * @throws \LogicException
	 */
	public function execute() {

		if ($this->options->annotation_calculation) {
			$clauses = $this->options->annotation_name_value_pairs;
			if (count($clauses) > 1 && $this->options->annotation_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Annotation calculation can not be performed on multiple annotation name value pairs merged with AND");
			}

			$clause = array_shift($clauses);

			return $this->calculate($this->options->annotation_calculation, $clause->names, 'annotation');
		} else if ($this->options->count) {
			return $this->count();
		} else if ($this->options->batch) {
			return $this->batch($this->options->limit, $this->options->offset, $this->options->callback);
		} else {
			return $this->get($this->options->limit, $this->options->offset, $this->options->callback);
		}
	}

	/**
	 * Build a database query
	 *
	 * @param QueryBuilder $qb
	 *
	 * @return QueryBuilder
	 */
	protected function buildQuery(QueryBuilder $qb) {

		$ands = [];

		foreach ($this->options->joins as $join) {
			$join->prepare($qb, 'rv');
		}

		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, 'rv');
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

		return $where->prepare($qb, 'rv');
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

		if ($this->options->subject_guids || $use_access_clause) {
			$qb->joinEntitiesTable('rv', 'subject_guid', 'inner', 'se');
			$subject = new EntityWhereClause();
			$subject->guids = $this->options->subject_guids;
			$ands[] = $subject->prepare($qb, 'se');
		}

		if ($this->options->object_guids || $use_access_clause || $this->options->type_subtype_pairs) {
			$qb->joinEntitiesTable('rv', 'object_guid', 'inner', 'oe');
			$object = new EntityWhereClause();
			$object->guids = $this->options->object_guids;
			$object->type_subtype_pairs = $this->options->type_subtype_pairs;
			$ands[] = $object->prepare($qb, 'oe');
		}

		if ($this->options->target_guids || $use_access_clause) {
			$target_ors = [];
			$qb->joinEntitiesTable('rv', 'target_guid', 'left', 'te');
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
				$joined_alias = 'n_table';
			} else {
				$joined_alias = $qb->getNextJoinAlias();
			}
			$joins = $qb->getQueryPart('join');
			$is_joined = false;
			if (!empty($joins['rv'])) {
				foreach ($joins['rv'] as $join) {
					if ($join['joinAlias'] === $joined_alias) {
						$is_joined = true;
					}
				}
			}

			if (!$is_joined) {
				$qb->join('rv', 'annotations', $joined_alias, "$joined_alias.id = rv.annotation_id");
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
			if (strtoupper($boolean) == 'OR' || count($clauses) === 1) {
				$joined_alias = $qb->joinRelationshipTable('rv', $join_on, null, $clause->inverse, 'inner', 'r');
			} else {
				$joined_alias = $qb->joinRelationshipTable('rv', $join_on, $clause->names, $clause->inverse);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $boolean);
	}
}

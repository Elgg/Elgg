<?php

namespace Elgg\Database;

use Elgg\AttributeLoader;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\Clauses\EntitySortByClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use ElggEntity;
use Psr\Log\InvalidArgumentException;

/**
 * Entities repository contains methods for fetching entities from database or performing
 * calculations on entity properties.
 *
 * API IN FLUX Do not access the methods directly, use elgg_get_entities() instead
 *
 * @todo   At a later stage, this class will contain additional shortcut methods to filter entities
 *         by relationship, metdataion, annotation, private settings etc. Until then, such filtering
 *         can be done via standard ege* options
 *
 *
 * @access private
 */
class Entities extends Repository {

	/**
	 * Build and execute a new query from an array of legacy options
	 *
	 * @param array $options Options
	 *
	 * @return ElggEntity[]|int|mixed
	 */
	public static function find(array $options = []) {
		try {
			return static::with($options)->execute();
		} catch (\DataFormatException $e) {
			return elgg_extract('count', $options) ? 0 : false;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function count() {
		$qb = Select::create('entities', 'e');

		$count_expr = $this->options->distinct ? "DISTINCT e.guid" : "*";
		$qb->select("COUNT({$count_expr}) AS total");

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		return (int) $result->total;
	}

	/**
	 * Performs a mathematical calculation on a set of entity properties
	 *
	 * @param string $function      Valid numeric function
	 * @param string $property      Property name
	 * @param string $property_type 'attribute'|'metadata'|'annotation'|'private_setting'
	 *
	 * @return int|float
	 * @throws \InvalidParameterException
	 */
	public function calculate($function, $property, $property_type = null) {

		if (!in_array(strtolower($function), QueryBuilder::$calculations)) {
			throw new InvalidArgumentException("'$function' is not a valid numeric function");
		}

		if (!isset($property_type)) {
			if (in_array($property, AttributeLoader::$primary_attr_names)) {
				$property_type = 'attribute';
			} else {
				$property_type = 'metadata';
			}
		}

		$qb = Select::create('entities', 'e');

		switch ($property_type) {
			case 'attribute':
				if (!in_array($property, AttributeLoader::$primary_attr_names)) {
					throw new \InvalidParameterException("'$property' is not a valid attribute");
				}

				$qb->addSelect("{$function}(e.{$property}) AS calculation");
				break;

			case 'metadata' :
				$alias = $qb->joinMetadataTable('e', 'guid', $property, 'inner', 'n_table');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;

			case 'annotation' :
				$alias = $qb->joinAnnotationTable('e', 'guid', $property, 'inner', 'n_table');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;

			case 'private_setting' :
				$alias = $qb->joinPrivateSettingsTable('e', 'guid', $property, 'inner', 'ps');
				$qb->addSelect("{$function}({$alias}.value) AS calculation");
				break;
		}

		$qb = $this->buildQuery($qb);

		$result = _elgg_services()->db->getDataRow($qb);

		return (int) $result->calculation;
	}

	/**
	 * Fetch entities
	 *
	 * @param int      $limit    Limite
	 * @param int      $offset   Offset
	 * @param callable $callback Custom callback
	 *
	 * @return ElggEntity[]
	 */
	public function get($limit = null, $offset = null, $callback = null) {

		$qb = Select::create('entities', 'e');

		$distinct = $this->options->distinct ? "DISTINCT" : "";
		$qb->select("$distinct e.*");

		foreach ($this->options->selects as $select_clause) {
			$select_clause->prepare($qb, 'e');
		}

		foreach ($this->options->group_by as $group_by_clause) {
			$group_by_clause->prepare($qb, 'e');
		}

		foreach ($this->options->having as $having_clause) {
			$having_clause->prepare($qb, 'e');
		}

		if (!empty($this->options->order_by)) {
			foreach ($this->options->order_by as $order_by_clause) {
				$order_by_clause->prepare($qb, 'e');
			}
		}

		$qb = $this->buildQuery($qb);

		// Keeping things backwards compatible
		$original_order = elgg_extract('order_by', $this->options->__original_options);
		if (empty($original_order) && $original_order !== false) {
			$qb->addOrderBy('e.time_created', 'desc');
		}

		if ($limit) {
			$qb->setMaxResults((int) $limit);
			$qb->setFirstResult((int) $offset);
		}

		$options = $this->options->getArrayCopy();

		$options['limit'] = (int) $limit;
		$options['offset'] = (int) $offset;
		$options['callback'] = $callback ? : $this->options->callback;
		if (!isset($options['callback'])) {
			$options['callback'] = 'entity_row_to_elggstar';
		}

		unset($options['count']);

		return _elgg_services()->entityTable->fetch($qb, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function batch($limit = null, $offset = null, $callback = null) {

		$options = $this->options->getArrayCopy();

		$options['limit'] = (int) $limit;
		$options['offset'] = (int) $offset;
		$options['callback'] = $callback;
		unset($options['count'],
			$options['batch'],
			$options['batch_size'],
			$options['batch_inc_offset']
		);

		$batch_size = $this->options->batch_size;
		$batch_inc_offset = $this->options->batch_inc_offset;

		return new \ElggBatch([static::class, 'find'], $options, null, $batch_size, $batch_inc_offset);
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
		} else if ($this->options->metadata_calculation) {
			$clauses = $this->options->metadata_name_value_pairs;
			if (count($clauses) > 1 && $this->options->metadata_name_value_pairs_operator !== 'OR') {
				throw new \LogicException("Metadata calculation can not be performed on multiple metadata name value pairs merged with AND");
			}

			$clause = array_shift($clauses);
			return $this->calculate($this->options->metadata_calculation, $clause->names, 'metadata');
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
			$join->prepare($qb, 'e');
		}

		foreach ($this->options->wheres as $where) {
			$ands[] = $where->prepare($qb, 'e');
		}

		$ands[] = $this->buildEntityClause($qb);
		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->metadata_name_value_pairs, $this->options->metadata_name_value_pairs_operator);
		$ands[] = $this->buildPairedMetadataClause($qb, $this->options->search_name_value_pairs, 'OR');
		$ands[] = $this->buildPairedAnnotationClause($qb, $this->options->annotation_name_value_pairs, $this->options->annotation_name_value_pairs_operator);
		$ands[] = $this->buildPairedPrivateSettingsClause($qb, $this->options->private_setting_name_value_pairs, $this->options->private_setting_name_value_pairs_operator);
		$ands[] = $this->buildPairedRelationshipClause($qb, $this->options->relationship_pairs);

		$ands = $qb->merge($ands);

		if (!empty($ands)) {
			$qb->andWhere($ands);
		}

		return $qb;
	}

	/**
	 * Build entity constraints
	 *
	 * @param QueryBuilder $qb
	 *
	 * @return \Closure|\Doctrine\DBAL\Query\Expression\CompositeExpression|mixed|null|string|void
	 */
	protected function buildEntityClause(QueryBuilder $qb) {
		$where = new EntityWhereClause();
		$where->guids = $this->options->guids;
		$where->owner_guids = $this->options->owner_guids;
		$where->container_guids = $this->options->container_guids;
		$where->type_subtype_pairs = $this->options->type_subtype_pairs;
		$where->created_after = $this->options->created_after;
		$where->created_before = $this->options->created_before;
		$where->updated_after = $this->options->updated_after;
		$where->updated_before = $this->options->updated_before;
		$where->last_action_after = $this->options->last_action_after;
		$where->last_action_before = $this->options->last_action_before;
		$where->access_ids = $this->options->access_ids;

		return $where->prepare($qb, 'e');
	}

	/**
	 * Process metadata name value pairs
	 *
	 * @param QueryBuilder          $qb
	 * @param MetadataWhereClause[] $pairs
	 * @param string                $operator
	 *
	 * @return \Doctrine\DBAL\Query\Expression\CompositeExpression
	 */
	protected function buildPairedMetadataClause(QueryBuilder $qb, $pairs, $operator = 'AND') {
		$parts = [];


		foreach ($pairs as $clause) {
			if ($clause instanceof MetadataWhereClause) {
				if (strtoupper($operator) === 'OR' || count($pairs) === 1) {
					$joined_alias = $qb->joinMetadataTable('e', 'guid', null, 'inner', 'n_table');
				} else {
					$joined_alias = $qb->joinMetadataTable('e', 'guid', $clause->names);
				}
			}

			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $operator);
	}

	protected function buildPairedAnnotationClause(QueryBuilder $qb, $pairs, $operator = 'AND') {
		$parts = [];

		foreach ($pairs as $clause) {
			if (strtoupper($operator) === 'OR' || count($pairs) === 1) {
				$joined_alias = $qb->joinAnnotationTable('e', 'guid', null, 'inner', 'n_table');
			} else {
				$joined_alias = $qb->joinAnnotationTable('e', 'guid', $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $operator);
	}

	protected function buildPairedPrivateSettingsClause(QueryBuilder $qb, $pairs, $operator = 'AND') {
		$parts = [];

		foreach ($pairs as $clause) {
			if (strtoupper($operator) === 'OR' || count($pairs) === 1) {
				$joined_alias = $qb->joinPrivateSettingsTable('e', 'guid', null, 'inner', 'ps');
			} else {
				$joined_alias = $qb->joinPrivateSettingsTable('e', 'guid', $clause->names);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $operator);
	}

	protected function buildPairedRelationshipClause(QueryBuilder $qb, $pairs, $operator = 'AND') {
		$parts = [];

		foreach ($pairs as $clause) {
			if (strtoupper($operator) == 'OR' || count($pairs) === 1) {
				$joined_alias = $qb->joinRelationshipTable('e', $clause->join_on, null, $clause->inverse, 'inner', 'r');
			} else {
				$joined_alias = $qb->joinRelationshipTable('e', $clause->join_on, $clause->names, $clause->inverse);
			}
			$parts[] = $clause->prepare($qb, $joined_alias);
		}

		return $qb->merge($parts, $operator);
	}
}
